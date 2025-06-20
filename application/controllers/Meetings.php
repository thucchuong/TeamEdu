<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Meetings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'notifications_model', 'projects_model', 'users_model', 'meetings_model']);
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
        if (!check_permissions("meetings", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');

            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
                $workspace_id = $this->session->userdata('workspace_id');
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $data['is_admin'] =  $this->ion_auth->is_admin();
                $data['user_id'] =  $user_id;
                $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
                $user_ids = explode(',', $current_workspace_id[0]->user_id);
                $section = array_map('trim', $user_ids);
                $user_ids = $section;
                $data['all_user'] = $this->users_model->get_user($user_ids);
                $this->load->view('meetings', $data);
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
        }
    }

    public function create()
    {
        if (!check_permissions("meetings", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', str_replace(':', '', 'start_date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_date', str_replace(':', '', 'end_date is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {

            $start_date = strip_tags($this->input->post('start_date', true));
            $end_date = strip_tags($this->input->post('end_date', true));

            if ($end_date < $start_date) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'End date should not be lesser then start date.';
                echo json_encode($response);
                return false;
            }
            $start_date = date("Y-m-d H:i:s", strtotime($start_date));
            $end_date = date("Y-m-d H:i:s", strtotime($end_date));
            $title = strip_tags($this->input->post('title', true));
            $admin_id = $this->session->userdata('user_id');
            $slug = url_title($title, 'dash', true);
            $title = $this->db->escape_str($title);
            $meeting = $this->meetings_model->get_meeting_by_title($title);
            if (isset($meeting[0]) && !empty($meeting[0])) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Meeting with this title already exists please try diffrent title.';
                echo json_encode($response);
                return false;
            }
            $link = "";
            $link1 =  $this->input->post('link1');
            $link2 =  $this->input->post('link2');
            $link3 =  $this->input->post('link3');
            if (isset($link1) && !empty($link1)) {
                $link = !empty($link1) ? strip_tags($link1) : '';
            } else if (isset($link2) && !empty($link2)) {
                $link = !empty($link2) ? strip_tags($link2) : '';
            } else if (isset($link3) && !empty($link3)) {
                $link = !empty($link3) ? strip_tags($link3) : '';
            }
            $data = array(
                'admin_id' => $admin_id,
                'title' => $title,
                'slug' => $slug,
                'type' => (!empty($this->input->post('type'))) ? $this->input->post('type') : '',
                'platform' => (!empty($this->input->post('platform'))) ? $this->input->post('platform') : '',
                'link' => $this->db->escape_str($link),
                'venue' => (!empty($this->input->post('venue'))) ? $this->input->post('venue') : '',
                'user_ids' => (!empty($this->input->post('users'))) ? implode(",", $this->input->post('users')) : '',
                'client_ids' => (!empty($this->input->post('clients'))) ? implode(",", $this->input->post('clients')) : '',
                'workspace_id' => $this->session->userdata('workspace_id'),
                'start_date' => $start_date,
                'end_date' => $end_date
            );
            $meeting_id = $this->meetings_model->create_meeting($data);

            if ($meeting_id != false) {
                $this->session->set_flashdata('message', 'Meeting Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Meeting could not Created! Try again!');
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
        if (!check_permissions("meetings", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', str_replace(':', '', 'start_date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_date', str_replace(':', '', 'end_date is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            $title = strip_tags($this->input->post('title', true));
            $title = $this->db->escape_str($title);
            $res = $this->db->where('title', $title)->where('id != ', $this->input->post('update_id'))->get('meetings')->row_array();
            if (!empty($res)) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Meeting with this title already exists please try diffrent title';
                echo json_encode($response);
                return false;
                exit();
            }
            $slug = url_title($title, 'dash', true);
            $start_date = strip_tags($this->input->post('start_date', true));
            $end_date = strip_tags($this->input->post('end_date', true));
            $start_date = date("Y-m-d H:i:s", strtotime($start_date));
            $end_date = date("Y-m-d H:i:s", strtotime($end_date));
            $link1 =  $this->input->post('link1');
            $link2 =  $this->input->post('link2');
            $link3 =  $this->input->post('link3');
            if (isset($link1) && !empty($link1)) {
                $link = !empty($link1) ? strip_tags($link1) : '';
            } else if (isset($link2) && !empty($link2)) {
                $link = !empty($link2) ? strip_tags($link2) : '';
            } else if (isset($link3) && !empty($link3)) {
                $link = !empty($link3) ? strip_tags($link3) : '';
            }
            $data = array(
                'title' => $title,
                'slug' => $slug,
                'type' => (!empty($this->input->post('type'))) ? $this->input->post('type') : '',
                'platform' => (!empty($this->input->post('platform'))) ? $this->input->post('platform', true) : '',
                'link' => $this->db->escape_str($link),
                'venue' => (!empty($this->input->post('venue'))) ? $this->input->post('venue', true) : '',
                'user_ids' => (!empty($this->input->post('users'))) ? implode(",", $this->input->post('users')) : '',
                'client_ids' => (!empty($this->input->post('clients'))) ? implode(",", $this->input->post('clients')) : '',
                'start_date' => $start_date,
                'end_date' => $end_date
            );

            if ($this->meetings_model->edit_meeting($data, $this->input->post('update_id'))) {
                $this->session->set_flashdata('message', 'Meeting Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Meeting could not Updated! Try again!');
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

    public function delete()
    {
        if (!check_permissions("meetings", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $id = $this->uri->segment(3);
        $user_id = $_SESSION['user_id'];

        if (!empty($id) && is_numeric($id)  || $id < 1) {
            if ($this->meetings_model->delete_meeting($id, $user_id)) {
                $this->session->set_flashdata('message', 'Meeting deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Meeting could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
    }

    public function get_meeting_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $meeting_id = $this->input->post('id');

            if (empty($meeting_id) || !is_numeric($meeting_id) || $meeting_id < 1) {
                redirect('meetings', 'refresh');
                return false;
                exit(0);
            }

            $data = $this->meetings_model->get_meeting_by_id($meeting_id);
            $data[0]['title'] = stripslashes($data[0]['title']);
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }
    public function get_meetings_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            return $this->meetings_model->get_meetings_list($workspace_id, $user_id);
        }
    }
    public function get_meeting_participants_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            return $this->users_model->get_participants_list();
        }
    }
    public function join()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');

            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);

            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
                $slug = $this->uri->segment(3);

                $workspace_id = $this->session->userdata('workspace_id');
                $user_id = $this->session->userdata('user_id');
                $is_allowed = 0;
                if (!empty($slug)) {
                    $meeting = $this->meetings_model->get_meeting_by_slug($slug);
                    if (isset($meeting[0]) && !empty($meeting[0])) {
                        $time = time();
                        $start = strtotime($meeting[0]['start_date']);
                        $end = strtotime($meeting[0]['end_date']);

                        if ($time > $start) {
                            if ($time < $end) {
                                $user_ids = $meeting[0]['user_ids'];
                                $user_ids = explode(',', $user_ids);
                                $client_ids = $meeting[0]['client_ids'];
                                $client_ids = explode(',', $client_ids);
                                if (is_admin() || is_workspace_admin($user_id, $workspace_id)) {
                                    $is_allowed = 1;
                                }

                                if ($user_id == $meeting[0]['admin_id']) {
                                    $is_allowed = 1;
                                } else if (is_member()) {
                                    if (in_array($user_id, $user_ids)) {
                                        $is_allowed = 1;
                                    }
                                } elseif (is_client()) {
                                    if (in_array($user_id, $client_ids)) {
                                        $is_allowed = 1;
                                    }
                                }
                                if ($is_allowed == 1) {
                                    $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                                    $data['projects'] = $projects;
                                    $data['notifications'] = !empty($workspace_id) ?
                                        $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                                    $data['is_admin'] =  $this->ion_auth->is_admin();
                                    $data['is_meeting_admin'] =  $meeting[0]['admin_id'] == $user_id ? 'true' : 'false';
                                    $data['meeting_id'] =  $meeting[0]['id'];
                                    $data['room_name'] =  $meeting[0]['title'];
                                    $data['user_email'] =  $user->email;
                                    $data['user_display_name'] =  $user->first_name . ' ' . $user->last_name;
                                    $this->load->view('join-meeting', $data);
                                } else {
                                    $this->session->set_flashdata('message', 'You are not allowed to join this meeting');
                                    $this->session->set_flashdata('message_type', 'error');
                                    redirect('meetings', 'refresh');
                                    return false;
                                    exit();
                                }
                            } else {
                                $this->session->set_flashdata('message', 'Meeting ended');
                                $this->session->set_flashdata('message_type', 'error');
                                redirect('meetings', 'refresh');
                                return false;
                                exit();
                            }
                        } else {
                            $this->session->set_flashdata('message', 'Meeting yet to start');
                            $this->session->set_flashdata('message_type', 'error');
                            redirect('meetings', 'refresh');
                            return false;
                            exit();
                        }
                    } else {
                        $this->session->set_flashdata('message', 'Meeting doesn\'t exists');
                        $this->session->set_flashdata('message_type', 'error');
                        redirect('meetings', 'refresh');
                        return false;
                        exit();
                    }
                } else {
                    $this->session->set_flashdata('message', 'Something went wrong');
                    $this->session->set_flashdata('message_type', 'error');
                    redirect('meetings', 'refresh');
                    return false;
                    exit();
                }
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
        }
    }
}
