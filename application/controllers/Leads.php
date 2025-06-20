<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Leads extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'leads_model', 'workspace_model', 'projects_model', 'notifications_model']);
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
        if (!check_permissions("leads", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');

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
                $this->session->set_flashdata('message_type', 'error');
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
            $data['leads'] = $leads = $this->leads_model->get_leads($workspace_id);
            $new = 0;
            $qualified = 0;
            $discussion = 0;
            $won = 0;
            $lost = 0;
            $i = 0;
            foreach ($leads as $lead) {

                if ($lead->status == 'new') {
                    $data['new'] = $new = $new + 1;
                } elseif ($lead->status == 'qualified') {
                    $data['qualified'] = $qualified = $qualified + 1;
                } elseif ($lead->status == 'discussion') {
                    $data['discussion'] = $discussion = $discussion + 1;
                } elseif ($lead->status == 'won') {
                    $data['won'] = $won = $won + 1;
                } else {
                    $data['lost'] = $lost = $lost + 1;
                }

                $i++;
            }
            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('leads', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }
    public function lead_status_update()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!check_permissions("leads", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $id = $this->input->post('id');
        $data = array(
            'status' => $this->input->post('status')
        );
        if ($this->leads_model->lead_status_update($data, $id)) {
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
    public function lists()
    {
        if (!check_permissions("leads", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');

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
                $this->session->set_flashdata('message_type', 'error');
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
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('leads-list', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }

    public function create()
    {
        if (!check_permissions("leads", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $workspace_id = $this->session->userdata('workspace_id');
        $admin_id = get_admin_id_by_workspace_id($workspace_id);

        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;
        $this->form_validation->set_rules('title', str_replace(':', '', 'title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']|xss_clean');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|xss_clean');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|xss_clean|valid_email|is_unique[' . $tables['users'] . '.email]', array('is_unique' => 'Email already registered.'));
        }
        $this->form_validation->set_rules('phone', str_replace(':', '', 'phone is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('assigned_date', str_replace(':', '', 'assigned_date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
        $workspace_id = $this->session->userdata('workspace_id');
        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'description' => strip_tags($this->input->post('description', true)),
                'phone' => strip_tags($this->input->post('phone', true)),
                'email' => strtolower($this->input->post('email', true)),
                'status' => strip_tags($this->input->post('status', true)),
                'assigned_date' => $this->input->post('assigned_date', true),
                'user_id' => (!empty($this->input->post('users'))) ? implode(",", $this->input->post('users')) : '',
                'workspace_id' => $workspace_id,
            );
            $lead_id = $this->leads_model->create_leads($data);
            if ($lead_id != false) {
                $this->session->set_flashdata('message', 'Leads Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Leads could not Created! Try again!');
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
        if (!check_permissions("leads", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('title', str_replace(':', '', 'title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', str_replace(':', '', 'email is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', str_replace(':', '', 'phone is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('assigned_date', str_replace(':', '', 'assigned_date is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'email' => strip_tags($this->input->post('email', true)),
                'phone' => strip_tags($this->input->post('phone', true)),
                'description' => strip_tags($this->input->post('description', true)),
                'user_id' => (!empty($this->input->post('users'))) ? implode(",", $this->input->post('users')) : '',
                'status' => strip_tags($this->input->post('status', true)),
                'assigned_date' => $this->input->post('assigned_date', true),
            );

            if ($this->leads_model->edit_leads($data, $this->input->post('update_id'))) {
                $this->session->set_flashdata('message', 'Leads Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Leads could not Updated! Try again!');
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

    public function get_leads_list()
    {
        if (!check_permissions("leads", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            if (!is_admin() && !is_member() && !is_client() && !is_workspace_admin($user_id, $workspace_id)) {
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
            $data = $this->leads_model->get_leads_list('list', $workspace_id, $user_id);
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            $response['data'] = $data;
            $lists =  json_encode($response);
            return $lists;
        }
    }

    public function get_leads_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $lead_id = $this->input->post('id');

            if (empty($lead_id) || !is_numeric($lead_id) || $lead_id < 1) {
                redirect('leads', 'refresh');
                return false;
                exit(0);
            }
            $data = $this->leads_model->get_leads_by_id($lead_id);

            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }
    public function delete()
    {
        if (!check_permissions("leads", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $id = $this->uri->segment(3);
        if (!empty($id) && is_numeric($id)  && $id > 0) {
            if ($this->leads_model->delete_leads($id)) {
                $this->session->set_flashdata('message', 'Leads deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Leads could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect('leads', 'refresh');
    }

    public function convert_to_client_id()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect('leads', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("clients", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $lead_id = $this->input->post('id');
            $data = $this->leads_model->convert_to_client_id($lead_id);

            $response['error'] = true;
            $response['data'] = $data;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = validation_errors();
            echo json_encode($response);
        }
    }

    public function duplicate_data()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $data = duplicate_row("leads", $_POST['id']);
            $response['data'] = $data;
            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }
}
