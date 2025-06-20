<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Time_tracker extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['Time_tracker_model', 'users_model', 'projects_model', 'workspace_model', 'notifications_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        if ($this->ion_auth->logged_in()) {
            if (is_admin()) {
                $admin_id = $this->session->userdata('user_id');
            } else {
                $admin_id = get_admin_id_by_workspace_id($this->session->userdata('workspace_id'));
            }
            $this->data['admin_id'] = $admin_id;
        } else {
            redirect('auth', 'refresh');
        }
    }

    public function index()
    {
        if (!check_permissions("time_tracker", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;
            $data['all_user'] = $this->users_model->get_user($user_ids);
            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;
            $today = date('Y-m-d');
            if (!empty($this->session->userdata('workspace_id'))) {
                // Today's duration
                $this->db->select('SUM(TIME_TO_SEC(duration)) AS total_seconds', FALSE);
                $this->db->from('time_tracker_sheet');
                $this->db->where('workspace_id', $this->session->userdata('workspace_id')); 
                $this->db->where('date', $today);
                $query = $this->db->get();
                $result = $query->row();
                $totalSeconds = $result->total_seconds;
                $data['today_sheet'] = ($result && isset($result->total_seconds)) ? gmdate('H:i:s', $result->total_seconds) : '00:00:00';

                // Weekly duration
                $this->db->select('SUM(TIME_TO_SEC(duration)) AS total_seconds', FALSE);
                $this->db->from('time_tracker_sheet');
                $this->db->where('workspace_id', $this->session->userdata('workspace_id'));
                $this->db->where('DATE(date) >=', date('Y-m-d', strtotime('monday this week')));
                $this->db->where('DATE(date) <=', date('Y-m-d', strtotime('sunday this week')));
                $query = $this->db->get();
                $result = $query->row();
                $totalSeconds = $result->total_seconds;
                $data['weekly_sheet'] = ($result && isset($result->total_seconds)) ? gmdate('H:i:s', $result->total_seconds) : '00:00:00';

                // Monthly duration
                $this->db->select('SUM(TIME_TO_SEC(duration)) AS total_seconds', FALSE);
                $this->db->from('time_tracker_sheet');
                $this->db->where('workspace_id', $this->session->userdata('workspace_id'));
                $this->db->where('MONTH(date)', date('m'));
                $query = $this->db->get();
                $result = $query->row();
                $totalSeconds = $result->total_seconds;
                $data['monthly_sheet'] = ($result && isset($result->total_seconds)) ? gmdate('H:i:s', $result->total_seconds) : '00:00:00';

                // Yearly duration
                $this->db->select('SUM(TIME_TO_SEC(duration)) AS total_seconds', FALSE);
                $this->db->from('time_tracker_sheet');
                $this->db->where('workspace_id', $this->session->userdata('workspace_id'));
                $this->db->where('YEAR(date)', date('Y'));
                $query = $this->db->get();
                $result = $query->row();
                $totalSeconds = $result->total_seconds;
                $data['yearly_sheet'] = ($result && isset($result->total_seconds)) ? gmdate('H:i:s', $result->total_seconds) : '00:00:00';
            } else {
                $data['today_sheet'] = '00:00:00';
                $data['weekly_sheet'] = '00:00:00';
                $data['monthly_sheet'] = '00:00:00';
                $data['yearly_sheet'] = '00:00:00';
            }
            
            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            if (!empty($workspace)) {

                $workspace_id = $this->session->userdata('workspace_id');
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $data['is_admin'] =  $this->ion_auth->is_admin();
                $this->load->view('time_tracker', $data);
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
        }
    }
    public function get_record_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $time_tracker_id = $this->input->post('id');

            if (empty($time_tracker_id) || !is_numeric($time_tracker_id) || $time_tracker_id < 1) {
                redirect('time_tracker', 'refresh');
                return false;
                exit(0);
            }
            $data = $this->Time_tracker_model->get_record_by_id($time_tracker_id);

            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }
    public function create()
    {
        if (!check_permissions("time_tracker", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $this->form_validation->set_rules('message', str_replace(':', '', 'message is empty.'), 'trim|required|xss_clean');
        $start_time_str = $this->input->post('start_date');
        $end_time_str = $this->input->post('end_date');

        $start_time = new DateTime($start_time_str);
        $end_time = new DateTime($end_time_str);

        $duration = $start_time->diff($end_time)->format('%H:%I:%S');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                "start_time" => $start_time_str,
                "end_time" => $end_time_str,
                "duration" => $duration,
                "message" => $this->input->post('message'),
                "project_id" => $this->input->post('project_id'),
                "date" => $this->input->post('date'),
            );
            $time_tracker_id = $this->Time_tracker_model->create_record($data);

            if ($time_tracker_id != false) {
                $this->session->set_flashdata('message', 'Time Tracker Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Time Tracker could not Created! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }

            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        } else {
            $response['error'] = true;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = validation_errors();
            echo json_encode($response);
        }
    }

    public function edit()
    {
        if (!check_permissions("time_tracker", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('message', str_replace(':', '', 'message is empty.'), 'trim|required|xss_clean');
        $start_time_str = $this->input->post('start_date');
        $end_time_str = $this->input->post('end_date');
        $start_time = new DateTime($start_time_str);
        $end_time = new DateTime($end_time_str);

        $duration = $start_time->diff($end_time)->format('%H:%I:%S');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                "start_time" => $start_time_str,
                "end_time" => $end_time_str,
                "duration" => $duration,
                "message" => $this->input->post('message'),
                "project_id" => $this->input->post('project_id'),
                "date" => $this->input->post('date'),
            );

            if ($this->Time_tracker_model->edit_record($data, $this->input->post('update_id'))) {
                $this->session->set_flashdata('message', 'Time Tracker Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Time Tracker could not Updated! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }

            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        } else {
            $response['error'] = true;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = validation_errors();
            echo json_encode($response);
        }
    }

    // this function here will store time when you started yor work
    public function add_start_time()
    {
        if (!check_permissions("time_tracker", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        if (is_admin() || is_member() || is_client() || is_workspace_admin($user_id, $workspace_id)) {
            $start_time = date("d-m-Y h:i:s");
            $dateTime = new DateTime($start_time);
            $date = $dateTime->format('Y-m-d');
            $time = $dateTime->format('H:i:s');
            $pickup_time = $date . 'T' . $time;


            $this->load->model('Time_tracker_model');
            $data =
                [
                    "user_id" => $user_id,
                    "workspace_id" => $workspace_id,
                    "start_time" => $time,
                    "end_time" => 00,
                    "duration" => 00,
                    "message" => $this->input->post('message'),
                    "project_id" => $this->input->post('project_id'),
                    "date" => $date,
                ];
            $id = $this->Time_tracker_model->create_record($data);
            if ($id) {
                $this->session->set_flashdata('message', 'Your Time has started');
                $this->session->set_flashdata('message_type', 'success');
                $response['error'] = false;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = "Time has started";
                $response['record_id'] = $id;
                echo json_encode($response);
                return false;
                exit();
            } else {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = "Time has started again!";
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            $admin_id = get_admin_id_by_workspace_id($workspace_id);
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = "Time has started again!";
            echo json_encode($response);
            return false;
            exit();
        }
    }

    public function add_end_time()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        $this->load->model('Time_tracker_model');
        if (is_admin() || is_member() || is_client() || is_workspace_admin($user_id, $workspace_id)) {
            $start_time = date("d-m-Y h:i:s");
            $dateTime = new DateTime($start_time);
            $date = $dateTime->format('Y-m-d');
            $time = $dateTime->format('H:i:s');
            $pickup_time = $date . 'T' . $time;
            $record_id =  $_POST['record_id'];
            $record = $this->Time_tracker_model->get_record_by_id($record_id);
            $st_time = $record[0]['start_time'];

            $t1  = strtotime($st_time);
            $t2 = strtotime($time);
            // Calculate the time difference in seconds
            $differenceInSeconds = $t2 - $t1;

            // Handle negative time difference
            if ($differenceInSeconds < 0) {
                $differenceInSeconds += 24 * 60 * 60; // Add 24 hours in seconds
            }

            // Convert the time difference to "HH:MM:SS" format
            $hours = floor($differenceInSeconds / 3600);
            $minutes = floor(($differenceInSeconds % 3600) / 60);
            $seconds = $differenceInSeconds % 60;

            // Format the time difference as "HH:MM:SS"
            $final_duration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
            $data =
                [
                    "id" => $record_id,
                    "end_time" => $time,
                    "duration" => $final_duration,
                    "message" => $this->input->post('message'),
                    "project_id" => $this->input->post('project_id'),
                    "date" => $date,
                ];

            if ($this->Time_tracker_model->edit_record($data, $record_id)) {
                $this->session->set_flashdata('message', 'Your Time has stopped');
                $this->session->set_flashdata('message_type', 'success');
                $response['error'] = false;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = "Time has started ";
                echo json_encode($response);
                return false;
                exit();
            } else {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = "Time has started again!";
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            $admin_id = get_admin_id_by_workspace_id($workspace_id);
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = "Time has started again!";
            echo json_encode($response);
            return false;
            exit();
        }
    }

    public function get_time_tracker_sheet_list()
    {
        if (!check_permissions("time_tracker", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $this->load->model('Time_tracker_model');
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            if (is_admin() && is_member() && is_client()) {
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
            return $this->Time_tracker_model->get_user_records($workspace_id, $user_id);
        }
    }

    public function delete()
    {
        if (!check_permissions("time_tracker", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $id = $this->uri->segment(3);
        if (!empty($id) && is_numeric($id)  && $id > 0) {
            if ($this->Time_tracker_model->delete_time_tracker($id)) {
                $this->session->set_flashdata('message', 'Time Tracker deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Time Tracker could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect('time_tracker', 'refresh');
    }
}
