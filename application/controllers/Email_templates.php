<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Email_templates extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['email_templates_model', 'users_model', 'workspace_model', 'projects_model', 'notifications_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
        $this->config->load('taskhub');

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
                $this->load->view('email-templates', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }

    public function create()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $this->form_validation->set_rules('type', str_replace(':', '', 'type is empty.'), 'trim|required|is_unique[email_templates.type]|xss_clean');
        $this->form_validation->set_rules('subject', str_replace(':', '', 'subject is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', str_replace(':', '', 'message is empty.'), 'trim|required|xss_clean');
        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'type' => strip_tags($this->input->post('type', true)),
                'subject' => strip_tags($this->input->post('subject', true)),
                'message' => output_escaping($this->input->post('message', true)),
            );
            $email_id = $this->email_templates_model->create_email_templates($data);

            if ($email_id != false) {
                $response["error"]   = true;
                $response["message"] = "Type Already Exist ! Provide a unique type";
                $this->session->set_flashdata('message', 'Email templates Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Email templates could not Created! Try again!');
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
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }


        $this->form_validation->set_rules('type', 'Type', 'required|trim|xss_clean');
        $this->form_validation->set_rules('subject', str_replace(':', '', 'subject is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', '', 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'type' => $this->input->post('type', true),
                'subject' => strip_tags($this->input->post('subject', true)),
                'message' => output_escaping($this->input->post('message', true)),
            );

            if ($this->email_templates_model->edit_email_templates($data, $this->input->post('update_id'))) {
                $this->session->set_flashdata('message', 'Email templates Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Email templates could not Updated! Try again!');
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

    public function get_mail_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!is_admin()) {
                $this->session->set_flashdata('message', 'You are not authorized to access this page!');
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
            return $this->email_templates_model->get_mail_list('list');
        }
    }
    public function delete()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!is_admin()) {
            $this->session->set_flashdata('message', 'You are not authorized to access this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }

        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)  || $id < 1) {
            if ($this->email_templates_model->delete_email_templates($id)) {
                $this->session->set_flashdata('message', 'Email templates deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Email templates could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect('email-templates', 'refresh');
    }

    public function get_email_templates_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $id = $this->input->post('id', true);
            $type = $this->input->post('name', true);
            $data = $this->email_templates_model->get_email_templates_by_id($id, $type);

            if (!empty($data)) {
                $response['error'] = false;
                $response['data'] = $data;
                $response['message'] = 'Email template found.';
            } else {
                $response['error'] = true;
                $response['data'] = [
                    'id' => "",
                    'type' => $type,
                    'subject' => "",
                    'message' => "",
                    'date_sent' => "",
                ];
                $response['message'] = 'No template found!';
            }
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        }
    }
}
