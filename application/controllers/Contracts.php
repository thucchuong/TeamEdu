<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Contracts extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'contracts_model', 'projects_model', 'workspace_model', 'notifications_model']);
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
        if (!check_permissions("contracts", "read", "", true)) {
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
                if (is_client($this->session->userdata['user_id'])) {
                    $projects = $this->projects_model->get_project($this->session->userdata('workspace_id'), $this->session->userdata['user_id'], '', 'client');
                } else {
                    $projects = $this->projects_model->get_project($this->session->userdata('workspace_id'), $this->session->userdata['user_id']);
                }
                // $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['contracts'] =  $this->contracts_model->get_contracts_type();
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('contracts', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }

    public function get_contracts_list()
    {
        if (!check_permissions("contracts", "read", "", true)) {
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
            $role = $this->session->userdata('role');
            $users_id = $this->session->userdata['user_id'];
            $data = $this->contracts_model->get_contracts_list($role, $users_id);
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            $response['data'] = $data;
            $lists =  json_encode($response);
            return $lists;
        }
    }

    public function contracts_type()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!check_permissions("contracts", "read", "", true)) {
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
                $this->load->view('contracts-type', $data);
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
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect('contracts', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("contracts", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }

        $this->form_validation->set_rules('title', str_replace(':', '', 'title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('value', str_replace(':', '', 'value is empty.'), 'trim|required|xss_clean');
        $workspace_id = $this->session->userdata('workspace_id');
        $start_date = strip_tags($this->input->post('start_date', true));
        $end_date = strip_tags($this->input->post('end_date', true));
        $start_date = date("Y-m-d H:i:s", strtotime($start_date));
        $end_date = date("Y-m-d H:i:s", strtotime($end_date));
        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'description' => output_escaping($this->input->post('description', true)),
                'project_id' => strip_tags($this->input->post('project_id', true)),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'value' => $this->input->post('value', true),
                'users_id' => isset($_POST['client_id']) && !empty($_POST['client_id']) ? $this->input->post('client_id', true) : $this->session->userdata['user_id'],
                'contract_type_id' => $this->input->post('contract_type_id'),
                'workspace_id' => $workspace_id,
            );

            $contracts_id = $this->contracts_model->create_contracts($data);
            if ($contracts_id != false) {
                $this->session->set_flashdata('message', 'contracts Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'contracts could not Created! Try again!');
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
        redirect('knowledgebase', 'refresh');
    }

    public function edit()
    {
        if (!check_permissions("knowledgebase", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect('contracts', 'refresh');
            return false;
            exit();
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $this->form_validation->set_rules('title', str_replace(':', '', 'title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('value', str_replace(':', '', 'value is empty.'), 'trim|required|xss_clean');
        $workspace_id = $this->session->userdata('workspace_id');
        $start_date = strip_tags($this->input->post('start_date'));
        $end_date = strip_tags($this->input->post('end_date'));
        $start_date = date("Y-m-d H:i:s", strtotime($start_date));
        $end_date = date("Y-m-d H:i:s", strtotime($end_date));


        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'title' => strip_tags($this->input->post('title')),
                'description' => output_escaping($this->input->post('description')),
                'project_id' => strip_tags($this->input->post('project_id')),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'value' => $this->input->post('value'),
                'users_id' => isset($_POST['client_id']) && !empty($_POST['client_id']) ? $this->input->post('client_id', true) : $this->session->userdata['user_id'],
                'contract_type_id' => $this->input->post('contract_type_id'),
                'workspace_id' => $workspace_id,
            );
            if ($this->contracts_model->edit_contracts($data, $this->input->post('id'))) {
                $this->session->set_flashdata('message', 'contracts Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'contracts could not Updated! Try again!');
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
    public function get_contracts_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $contracts_id = $this->input->post('id');

            if (empty($contracts_id) || !is_numeric($contracts_id) || $contracts_id < 1) {
                redirect('contracts', 'refresh');
                return false;
                exit(0);
            }
            $data = $this->contracts_model->get_contracts_by_id($contracts_id);
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }

    public function get_contracts_type_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!check_permissions("contracts", "read", "", true)) {
                return redirect(base_url(), 'refresh');
            }
            return $this->contracts_model->get_contracts_type_list();
        }
    }

    public function contracts_type_create()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect('contracts', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("contracts", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $this->form_validation->set_rules('type', str_replace(':', '', 'Type is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {

            $data = array(
                'type' => strip_tags($this->input->post('type', true))
            );
            $contracts_id = $this->contracts_model->create_contracts_type($data);
            if ($contracts_id != false) {
                $response['error'] = false;
                $response['contracts_id'] = $contracts_id;
                $response['message'] = 'Contracts Type Successfully.';
                $this->session->set_flashdata('message', 'Contracts Type added successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $response['error'] = true;
                $response['message'] = 'Contracts type could not added! Try again!';
                $this->session->set_flashdata('message', 'Contracts type could not added! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        } else {
            $response['error'] = true;
            $response['message'] = validation_errors();
        }
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function edit_contracts_type()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect('contracts', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("contracts", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }

        $this->form_validation->set_rules('type', str_replace(':', '', 'type is empty.'), 'trim|required|xss_clean');
        $workspace_id = $this->session->userdata('workspace_id');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'type' => strip_tags($this->input->post('type')),
            );
            if ($this->contracts_model->edit_contracts_type($data, $this->input->post('id'))) {
                $this->session->set_flashdata('message', 'contracts Type Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'contracts Type could not Updated! Try again!');
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

    public function get_contracts_type_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $contracts_id = $this->input->post('id');

            if (empty($contracts_id) || !is_numeric($contracts_id) || $contracts_id < 1) {
                redirect('contracts-type', 'refresh');
                return false;
                exit(0);
            }
            $data = $this->contracts_model->get_contracts_type_by_id($contracts_id);
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }
    public function delete_contracts_type()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!check_permissions("contracts", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $id = $this->uri->segment(3);

        if (!empty($id) && is_numeric($id)  || $id < 1) {
            if ($this->contracts_model->delete_contracts_type($id)) {
                $this->session->set_flashdata('message', 'contracts Type deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'contracts Type could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
            redirect('contracts/contracts-type', 'refresh');
        }
    }

    public function contracts_sign()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!check_permissions("contracts", "read", "", true)) {
                return redirect(base_url(), 'refresh');
            }

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
            $contracts_id = $this->uri->segment(3);
            $data['company_logo'] = get_compnay_logo();
            $contracts = $this->contracts_model->contracts_sign($contracts_id);
            $data['contracts'] = $contracts[0];
            $data['my_fonts'] = file_get_contents("assets/fonts/my-fonts.json");

            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('contracts_sign', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }
    public function get_contracts_sign_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $contracts_id = $this->input->post('id');

            if (empty($contracts_id) || !is_numeric($contracts_id) || $contracts_id < 1) {
                redirect('contracts', 'refresh');
                return false;
                exit(0);
            }
            $data = $this->contracts_model->get_contracts_sign_by_id($contracts_id);
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }

    public function create_contracts_sign()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                redirect('contracts', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("contracts", "create", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $contracts_id = fetch_details('contracts', ['id' => $_POST['id']], '*');
            $imagedata = $this->input->post('signatureImage');
            $decoded_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagedata));
            $target_path = "assets/sign/";

            if (!is_dir($target_path)) {
                mkdir($target_path, 0777, TRUE);
            }

            $file_name =  "Sign" . time() . "-" . rand(100, 999) . '.png';
            file_put_contents($target_path . $file_name, $decoded_image);

            $data = array(
                'id' => $contracts_id[0]['id'],
                'provider_sign' => $file_name,
                'provider_first_name' => strip_tags($this->input->post('provider_first_name')),
                'provider_last_name' => strip_tags($this->input->post('provider_last_name'))

            );
            if ($this->contracts_model->edit_contracts($data, $this->input->post('id'))) {
                $this->session->set_flashdata('message', 'Contracts Sign Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Contracts Sign could not be created! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }

            // Prepare the JSON response
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }

    public function create_client_contracts_sign()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                redirect('contracts', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("contracts", "create", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $contracts_id = fetch_details('contracts', ['id' => $_POST['id']], '*');
            $imagedata = $this->input->post('clientSignatureImage');
            $decoded_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagedata));
            $target_path = "assets/sign/";

            if (!is_dir($target_path)) {
                mkdir($target_path, 0777, TRUE);
            }

            $file_name =  "Sign" . time() . "-" . rand(100, 999) . '.png';
            file_put_contents($target_path . $file_name, $decoded_image);

            $data = array(
                'id' => $contracts_id[0]['id'],
                'client_sign' => $file_name,
                'client_first_name' => strip_tags($this->input->post('client_first_name')),
                'client_last_name' => strip_tags($this->input->post('client_last_name'))

            );
            if ($this->contracts_model->edit_contracts($data, $this->input->post('id'))) {
                $this->session->set_flashdata('message', 'Contracts Sign Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Contracts Sign could not be created! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }

            // Prepare the JSON response
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }

    public function delete_provider_sign_contracts()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                redirect('contracts', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("contracts", "delete", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $id = $this->uri->segment(3);
            if (!empty($id) && is_numeric($id) && $id > 0) {
                if ($this->contracts_model->delete_provider_sign_contracts($id)) {
                    $this->session->set_flashdata('message', 'contracts provider sign deleted successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                } else {
                    $this->session->set_flashdata('message', 'contracts provider sign could not be deleted! Try again!');
                    $this->session->set_flashdata('message_type', 'error');
                }
            }
            redirect('contracts', 'refresh');
        }
    }

    public function delete_client_sign_contracts()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                redirect('contracts', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("contracts", "delete", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $id = $this->uri->segment(3);
            if (!empty($id) && is_numeric($id) && $id > 0) {
                if ($this->contracts_model->delete_client_sign_contracts($id)) {
                    $this->session->set_flashdata('message', 'contracts Client sign deleted successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                } else {
                    $this->session->set_flashdata('message', 'contracts Client sign could not be deleted! Try again!');
                    $this->session->set_flashdata('message_type', 'error');
                }
            }
            redirect('contracts', 'refresh');
        }
    }

    public function get_contracts_type()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                redirect('contracts', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("contracts", "read", "", true)) {
                redirect('home', 'refresh');
            }
            $data['contracts'] =  $this->contracts_model->get_contracts_type();
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['data'] = $data;
            print_r(json_encode($response));
        }
    }

    public function delete()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                redirect('contracts', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("contracts", "delete", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $id = $this->uri->segment(3);
            if (!empty($id) && is_numeric($id) && $id > 0) {
                if ($this->contracts_model->delete_contracts($id)) {
                    $this->session->set_flashdata('message', 'contracts deleted successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                } else {
                    $this->session->set_flashdata('message', 'contracts could not be deleted! Try again!');
                    $this->session->set_flashdata('message_type', 'error');
                }
            }
            redirect('admin/contracts', 'refresh');
        }
    }
    public function duplicate_data()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $data = duplicate_row("contracts", $_POST['id']);
            $response['data'] = $data;
            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }

    public function get_project_by_client_id()
    {
        if ($this->input->post('client_id') && $this->input->post('client_id') != '') {

            $response['error'] = false;
            $response['projects']  = $this->projects_model->get_project($this->session->userdata('workspace_id'), $this->input->post('client_id'), '', 'client');
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        }
    }
}
