<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permissions extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'items_model', 'notifications_model', 'projects_model', 'users_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
        $this->config->load('taskhub');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function index()
    {
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
            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();

                $data['system_modules'] = $this->config->item('system_modules');
                $data['modules'] = $this->users_model->modules($this->session->userdata('user_id'));
                $this->load->view('permissions', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }

    public function update_members_permissions()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $response['error'] = true;
                $response['message'] = "This operation not allowed in demo version.";
                echo json_encode($response);
                redirect('home', 'refresh');
                return false;
            }

            if ($data['name'] = 'members') {
                
                $permission_data = [
                    'member_permissions' => json_encode($this->input->post('member_permissions', true))
                ];

            } else {
                $permission_data = [
                    'member_permissions' => NULL
                ];
            }
            
            $data = $this->db->set($permission_data)->where(['group_id' => '1', 'user_id' => $this->session->userdata('user_id')])->update('users_groups');
            if ($data) {
                $this->session->set_flashdata('message', 'Members permissions updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Members permissions could not updated! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }

            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }

    public function update_clients_permissions()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $response['error'] = true;
                $response['message'] = "This operation not allowed in demo version.";
                echo json_encode($response);
                redirect('home', 'refresh');
                return false;
            }
            if ($data['name'] = 'clients') {
                $permission_data = [
                    'client_permissions' => json_encode($this->input->post('client_permissions', true))
                ];
            } else {
                $permission_data = [
                    'client_permissions' => NULL
                ];
            }
            $data = $this->db->set($permission_data)->where(['group_id' => '1', 'user_id' => $this->session->userdata('user_id')])->update('users_groups');
            if ($data) {
                $this->session->set_flashdata('message', 'Client permissions updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Client permissions could not updated! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }

}
