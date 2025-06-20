<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gantt_chart extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'workspace_model', 'notifications_model', 'projects_model', 'tasks_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function index()
    {
        if (!check_permissions("projects", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
            $data['projects'] = $projects;
            $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $this->load->view('gantt_chart', $data);
        }
    }
    public function get_project()
    {
        $workspace_id = $this->session->userdata('workspace_id');
        $user_id = $this->session->userdata('user_id');
        $user_type = is_client() ? 'client' : 'normal';
        $data['projects'] = $projects = $this->projects_model->fetch_projects($workspace_id, $user_id, $user_type);

        $i = 0;
        foreach ($projects as $row) {
            $data['projects'][$i] = $row;
            $data['projects'][$i]['project_progress'] = $this->projects_model->get_project_progress($this->session->userdata('workspace_id'), $row['id']);
            $data['projects'][$i]['tasks'] = fetch_details('tasks', ['project_id' => $data['projects'][$i]['id']], '*');
            $i++;
        }
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $response['data'] = $data;
        echo json_encode($response);
    }

    public function edit_project_gantt()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if (!check_permissions("projects", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $this->form_validation->set_rules('starting_date', str_replace(':', '', 'starting date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('ending_date', str_replace(':', '', 'ending date is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {

            $data = array(
                'start_date' => date('Y-m-d', strtotime($this->input->post('starting_date', true))),
                'end_date' => date('Y-m-d', strtotime($this->input->post('ending_date', true))),
                'workspace_id' => $this->session->userdata('workspace_id')
            );
            $id = $this->projects_model->edit_project($data, $this->input->post('update_id'));
            if ($id) {
                $this->session->set_flashdata('message', 'projects Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'projects could not Updated! Try again!');
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

    public function edit_task_gantt()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!check_permissions("tasks", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $this->form_validation->set_rules('starting_date', str_replace(':', '', 'starting date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('ending_date', str_replace(':', '', 'ending date is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {

            $data = array(
                'start_date' => date('Y-m-d', strtotime($this->input->post('starting_date', true))),
                'due_date' => date('Y-m-d', strtotime($this->input->post('ending_date', true))),
                'workspace_id' => $this->session->userdata('workspace_id')
            );
            $id = $this->tasks_model->edit_task($data, $this->input->post('update_id'));
            if ($id) {
                $this->session->set_flashdata('message', 'tasks Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'tasks could not Updated! Try again!');
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

    public function get_project_by_id($project_id)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!check_permissions("projects", "read", "", true)) {
                redirect('/home', 'refresh');
            }

            $data['projects'] = $projects = $this->projects_model->get_project_by_id($project_id);

            $data['projects'] = $projects;
            $data['projects'][0]['project_progress'] = $this->projects_model->get_project_progress($this->session->userdata('workspace_id'), $project_id);
            $data['projects'][0]['tasks'] = fetch_details('tasks', ['project_id' => $project_id], '*');
            echo json_encode($data);
        }
    }
}
