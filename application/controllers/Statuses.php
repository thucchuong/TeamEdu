<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Statuses extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'statuses_model', 'projects_model', 'workspace_model', 'notifications_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        if ($this->ion_auth->logged_in()) {
            if (is_admin() || is_member()) {
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
        if (!check_permissions("statuses", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
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
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;
            $data['all_user'] = $this->users_model->get_user($user_ids);
            $admin_ids = explode(',', $current_workspace_id[0]->admin_id);
            $section = array_map('trim', $admin_ids);
            $data['not_in_workspace_user'] = $this->users_model->get_user_not_in_workspace($user_ids);
            $data['admin_ids'] = $admin_ids = $section;

            $workspace_id = $this->session->userdata('workspace_id');

            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['statuses'] =  $this->statuses_model->get_statuses($workspace_id);
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('statuses', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }

    public function statuses()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!check_permissions("statuses", "read", "", true)) {
                return redirect(base_url(), 'refresh');
            }

            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $product_ids = explode(',', $user->workspace_id);
            $section = array_map('trim', $product_ids);
            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            $workspace_id = $this->session->userdata('workspace_id');

            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('statuses-type', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }

    public function get_statuses_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!check_permissions("statuses", "read", "", true)) {
                return redirect(base_url(), 'refresh');
            }
            return $this->statuses_model->get_statuses_list();
        }
    }

    public function statuses_create()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message', 'error');
            redirect('statuses', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("statuses", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $this->form_validation->set_rules('type', str_replace(':', '', 'Type is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {

            $data = array(
                'workspace_id' => $this->session->userdata('workspace_id'),
                'type' => strip_tags($this->input->post('type', true)),
                'text_color' => strip_tags($this->input->post('text_color', true)),
            );
            $statuses_id = $this->statuses_model->create_statuses($data);
            if ($statuses_id != false) {
                $response['error'] = false;
                $response['statuses_id'] = $statuses_id;
                $response['message'] = 'statuses Type Successfully.';
                $this->session->set_flashdata('message', 'statuses Type added successfully.');
                $this->session->set_flashdata('message', 'success');
            } else {
                $response['error'] = true;
                $response['message'] = 'statuses type could not added! Try again!';
                $this->session->set_flashdata('message', 'statuses type could not added! Try again!');
                $this->session->set_flashdata('message', 'error');
            }
        } else {
            $response['error'] = true;
            $response['message'] = validation_errors();
        }
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function edit_statuses()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message', 'error');
            redirect('statuses', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("statuses", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }

        $this->form_validation->set_rules('type', str_replace(':', '', 'type is empty.'), 'trim|required|xss_clean');
        $workspace_id = $this->session->userdata('workspace_id');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'workspace_id' => $this->session->userdata('workspace_id'),
                'type' => strip_tags($this->input->post('type')),
                'text_color' => strip_tags($this->input->post('text_color')),
            );
            if ($this->statuses_model->edit_statuses($data, $this->input->post('id'))) {
                $this->session->set_flashdata('message', 'statuses Type Updated successfully.');
                $this->session->set_flashdata('message', 'success');
            } else {
                $this->session->set_flashdata('message', 'statuses Type could not Updated! Try again!');
                $this->session->set_flashdata('message', 'error');
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

    public function get_statuses_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $statuses_id = $this->input->post('id');

            if (empty($statuses_id) || !is_numeric($statuses_id) || $statuses_id < 1) {
                redirect('statuses-type', 'refresh');
                return false;
                exit(0);
            }
            $data = $this->statuses_model->get_statuses_by_id($statuses_id);
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }
    public function delete_statuses()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!check_permissions("statuses", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $id = $this->uri->segment(3);

        if (!empty($id) && is_numeric($id)  || $id < 1) {
            if ($this->statuses_model->delete_statuses($id)) {
                $this->session->set_flashdata('message', 'statuses Type deleted successfully.');
                $this->session->set_flashdata('message', 'success');
            } else {
                $this->session->set_flashdata('message', 'statuses Type could not be deleted! Try again!');
                $this->session->set_flashdata('message', 'error');
            }
            redirect('statuses/statuses-type', 'refresh');
        }
    }

    public function get_statuses()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message', 'error');
                redirect('statuses', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("statuses", "read", "", true)) {
                redirect('home', 'refresh');
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $data['statuses'] =  $this->statuses_model->get_statuses($workspace_id);
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['data'] = $data;
            print_r(json_encode($response));
        }
    }

    public function get_statuses_task()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message', 'error');
                redirect('statuses', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("statuses", "read", "", true)) {
                redirect('home', 'refresh');
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $data['statuses'] =  $this->statuses_model->get_statuses_task($workspace_id);
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['data'] = $data;
            print_r(json_encode($response));
        }
    }

    public function get_statuses_project()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message', 'error');
                redirect('statuses', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("statuses", "read", "", true)) {
                redirect('home', 'refresh');
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $data['statuses'] =  $this->statuses_model->get_statuses_project($workspace_id);
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['data'] = $data;
            print_r(json_encode($response));
        }
    }
    public function duplicate_data()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $data = duplicate_row("statuses", $_POST['id']);
            $response['data'] = $data;
            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }
}
