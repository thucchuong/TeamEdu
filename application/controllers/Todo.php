<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Todo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'workspace_model', 'todo_model', 'projects_model', 'notifications_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }



    public function index()
    {
        if (!check_permissions("todos", "read", "", true)) {
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
            $user_id = $this->session->userdata('user_id');
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $data['all_user'] = $this->users_model->get_user($user_ids);
            // $data['todo_data'] = $this->todo_model->get_todo_list($workspace_id, $user_id);
            // $data_main= json_decode($data['todo_data'],1);
            // $data['todo_data'] = ($data_main['rows']);
            // print_R($data['data']);
            if (!empty($this->session->has_userdata('workspace_id'))) {

                $data['total_user'] = $this->custom_funcation_model->get_count('id', 'users', 'FIND_IN_SET(' . $this->session->userdata('workspace_id') . ', workspace_id)');

                $data['total_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id'));

                $data['todo_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="todo"');

                $data['inprogress_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="inprogress"');

                $data['review_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="review"');

                $data['done_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="done"');

                $data['total_project'] = $total_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id'));

                $data['notes'] = $notes = $this->custom_funcation_model->get_count('id', 'notes', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and user_id=' . $this->session->userdata('user_id') . '');
            } else {
                $data['total_user'] = 0;

                $data['total_task'] = 0;

                $data['todo_task'] = 0;

                $data['inprogress_task'] = 0;

                $data['review_task'] = 0;

                $data['done_task'] = 0;

                $data['total_project'] = $total_project = 0;

                $data['notes'] = $notes = 0;
            }

            if ($total_project != 0) {
                $finished_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="finished"');
                $finished_project = $finished_project * 100 / $total_project;
                $data['finished_project'] = bcdiv($finished_project, 1, 2);

                $ongoing_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="ongoing"');
                $ongoing_project =  $ongoing_project * 100 / $total_project;
                $data['ongoing_project'] = bcdiv($ongoing_project, 1, 2);

                $onhold_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="onhold"');
                $onhold_project = $onhold_project * 100 / $total_project;
                $data['onhold_project'] = bcdiv($onhold_project, 1, 2);
            } else {
                $data['finished_project'] = 0;
                $data['ongoing_project'] = 0;
                $data['onhold_project'] = 0;
            }

            $this->load->model('Todo_model');
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            $todo_list =  $this->Todo_model->get_todo_list($workspace_id, $user_id);



            $lists =  json_decode($todo_list, true);
            
            $data['lists'] = $lists;
            // print_r($data['lists']);
            // return;
            $workspace_id = $this->session->userdata('workspace_id');
            $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
            $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $this->load->view('todo-list', $data);
        }
    }

    public function add_todo_list()
    {
        if (!check_permissions("todos", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $this->load->helper(array('form', 'url'));
            // $this->load->library('Security');

            $this->load->library('form_validation');
            $this->form_validation->set_rules('todo_text_in', 'todo_text_in', 'required|xss_clean');

            if ($this->form_validation->run() === false) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Description cannot be empty';
                echo json_encode($response);
                return;
            }

            $this->db->select('COUNT(t.id) as total');
            $query = $this->db->get('todos t');
            $total_row_present = $query->result_array()[0]['total'];
            $id = $this->input->post('id');
            $text =  $this->input->post('todo_text_in');
            $text = $this->security->xss_clean($text);
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if(empty($workspace_id)){
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Create workspace to use Todos feature!';
                $this->session->set_flashdata('message', 'Create workspace to use Todos feature!');
                $this->session->set_flashdata('message_type', 'error');
                echo json_encode($response);
                return;
            }
            $data = [
                'user_id' => $user_id,
                'workspace_id' => $workspace_id,
                'description' => $text,
                "status" => 0,
            ];

            if ($id == '') {
                $todo_id  = $this->todo_model->add($data);

                $this->db->set('position', $todo_id);
                $this->db->where('id', $todo_id);
                $this->db->update('todos');
                if ($todo_id) {
                    $response['error'] = false;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $response['message'] = "Todo created succesfully";
                    $response['id'] = $todo_id;
                    print_r(json_encode($response));
                    return;
                } else {
                    $response['error'] = true;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->session->set_flashdata('message', 'Todos could not be created! ');
                    $this->session->set_flashdata('message_type', 'error');
                    echo json_encode($response);
                    return;
                }
            } else {
                $this->db->set($data);
                $this->db->where('id', $id);
                $todo_id =  $this->db->update('todos');
                if ($todo_id) {
                    $response['error'] = false;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $response['message'] = "Todo upadted succesfully";
                    $response['id'] = $todo_id;
                    print_r(json_encode($response));
                    return;
                } else {
                    $response['error'] = true;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->session->set_flashdata('message', 'Todo could not be upadted! ');
                    $this->session->set_flashdata('message_type', 'error');
                    echo json_encode($response);
                    return;
                }
            }
        }
    }

    public function list()
    {
        if (!check_permissions("todos", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $this->load->model('Todo_model');
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            if (!is_admin() && !is_workspace_admin($user_id, $workspace_id)) {
                // $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
            $data =  $this->Todo_model->get_todo_list($workspace_id, $user_id);

            $response['error'] = false;
            // print_r($text);
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            $response['data'] = $data;
            $lists =  json_encode($response);
            return $lists;
        }
    }

    //Delete function

    public function delete()
    {
        if (!check_permissions("todos", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $id = $this->input->post('id');
            $todo_id =  $this->db->delete('todos', ['id' => $id]);

            if ($todo_id) {
                $response['error'] = false;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Successful';
                $this->session->set_flashdata('message', 'Todo deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
                echo json_encode($response);
                return;
            } else {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Failed';
                $this->session->set_flashdata('message', 'Failed to delete todo.');
                $this->session->set_flashdata('message_type', 'error');
                echo json_encode($response);
                return;
            }
        }
    }

    public function edit()
    {
        if (!check_permissions("todos", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $text =  $this->input->post('todo_text_in');
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            $data = array(
                'user_id' => $user_id,
                'workspace_id' => $workspace_id,
                'description' => $text,
                "status" => 0,
            );


            $id = strip_tags($this->input->post('user_id', true));
            if ($this->todo_model->edit($data, $id)) {
                $this->session->set_flashdata('message', 'Todo Updated Successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Todo could not be updated! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }

            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }


    public function status()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $id = $this->input->post('id');
        $data = [
            'status' => 1
        ];
        $data = escape_array($data);
        $todo_id =    $this->db->where(['id' => $id])->update('todos', $data);


        if ($todo_id) {
            $response['error'] = false;
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = "Status updated succesfully";
            print_r(json_encode($response));
        } else {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
            return;
        }
    }

    public function unchecked()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $id = $this->input->post('id');
        $data = [
            'status' => 0
        ];
        $data = escape_array($data);
        $todo_id =    $this->db->where(['id' => $id])->update('todos', $data);


        if ($todo_id) {
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
            return;
        } else {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
            return;
        }
    }

    public function update()
    {
        if (!check_permissions("todos", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->uri->segment(3);

            if (!empty($user_id) && is_numeric($user_id)) {

                $data = array(
                    'action_by' => $this->session->userdata('user_id'),
                    'status' => 1
                );

                if ($this->todo_model->update($user_id, $data)) {
                    $this->session->set_flashdata('message', 'Status updated successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                } else {
                    $this->session->set_flashdata('message', 'Status could not be updated! Try again!');
                    $this->session->set_flashdata('message_type', 'error');
                }
            }
            redirect('todo', 'refresh');
        }
    }

    public function update_position()
    {
        $user_id = $_SESSION['user_id'];
        $workspace_id = $_SESSION['workspace_id'];
        $sequence = $this->input->post('sequence', true);
        $data = explode(',', $sequence);

        for ($i = 0; $i < count($data); $i++) {
            $this->db->set('position', $i + 1);
            $this->db->where('id', $data[$i]);
            $this->db->update('todos');
        }
        $response['error'] = false;
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $response['message'] = "Position updated succesfully";
        print_r(json_encode($response));
    }
}
