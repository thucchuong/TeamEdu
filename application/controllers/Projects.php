<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'workspace_model', 'statuses_model', 'projects_model', 'milestones_model', 'tasks_model', 'activity_model', 'notifications_model', 'ion_auth_model']);
        $this->load->library(['ion_auth', 'form_validation', 'pagination']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

    public function lists()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $filter = (isset($_GET['filter']) && !empty($_GET['filter'])) ? $_GET['filter'] : '';
            $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : '';
            $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : '';

            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $project_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $project_ids);

            $project_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($project_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
            }
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;
            $data['all_user'] = $this->users_model->get_user($user_ids);
            $data['client_ids'] = $client_ids = fetch_details('users', ['id' => $this->session->userdata('user_id')]);
            $admin_ids = explode(',', $current_workspace_id[0]->admin_id);
            $section = array_map('trim', $admin_ids);
            $data['admin_ids'] = $admin_ids = $section;

            $this->config->load('taskhub');
            $data['progress_bar_classes'] = $this->config->item('progress_bar_classes');

            $data['projects'] = $projects = $this->projects_model->get_project($this->session->userdata('workspace_id'), $this->session->userdata('user_id'), $filter, $sort, $order);
            $i = 0;
            foreach ($projects as $row) {
                $projects_user_ids = explode(',', $row['user_id']);
                $data['projects'][$i] = $row;
                $data['projects'][$i]['project_progress'] = $this->projects_model->get_project_progress($this->session->userdata('workspace_id'), $row['id']);
                $data['projects'][$i]['projects_users'] = $this->users_model->get_user_array_responce($projects_user_ids);
                $i++;
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $data['statuses_project'] =  $this->statuses_model->get_statuses_project($workspace_id);
            $data['statuses'] = $this->statuses_model->get_statuses($workspace_id);
            $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
            $this->load->view('projects-list', $data);
        }
    }

    public function get_projects_list($id = '')
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            // 			$user_id = !empty($id && is_numeric($id)) ? $id : !empty($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : $this->session->userdata('user_id');
            $user_id = !empty($id && is_numeric($id)) ? $id : (!empty($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : $this->session->userdata('user_id'));

            return $this->projects_model->get_projects_list($workspace_id, $user_id);
        }
    }

    public function index()
    {
        if (!check_permissions("projects", "read", "", true)) {
            redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $filter = (isset($_GET['filter']) && !empty($_GET['filter'])) ? $_GET['filter'] : '';
            $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : '';
            $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : '';

            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $project_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $project_ids);

            $project_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($project_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
            }

            $data['is_admin'] =  $this->ion_auth->is_admin();
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $data['client_ids'] = $client_ids = fetch_details('users', ['id' => $this->session->userdata('user_id')]);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;
            $data['all_user'] = $this->users_model->get_user($user_ids);
            $admin_ids = explode(',', $current_workspace_id[0]->admin_id);
            $section = array_map('trim', $admin_ids);
            $data['admin_ids'] = $admin_ids = $section;

            $data['per_page'] = 10;
            $this->config->load('taskhub');
            $data['progress_bar_classes'] = $this->config->item('progress_bar_classes');
            $user_type = is_client() ? 'client' : 'normal';

            $projects_counts = $this->projects_model->get_projects_count($this->session->userdata('workspace_id'), $this->session->userdata('user_id'), $filter, $user_type);
            $data['total_projects'] = $projects_counts[0]['total'];

            $config = array();
            $config["base_url"] = base_url('projects');
            $config["total_rows"] = $data['total_projects'];
            $config["per_page"] = $data['per_page'];
            $config["uri_segment"] = 2;
            $config["num_links"] = 5;

            $config['next_link']        = '›';
            $config['prev_link']        = '‹';
            $config['first_link']       = false;
            $config['last_link']        = false;
            $config['full_tag_open']    = '<ul class="pagination justify-content-center">';
            $config['full_tag_close']   = '</ul>';
            $config['attributes']       = ['class' => 'page-link'];
            $config['first_tag_open']   = '<li class="page-item">';
            $config['first_tag_close']  = '</li>';
            $config['prev_tag_open']    = '<li class="page-item">';
            $config['prev_tag_close']   = '</li>';
            $config['next_tag_open']    = '<li class="page-item">';
            $config['next_tag_close']   = '</li>';
            $config['last_tag_open']    = '<li class="page-item">';
            $config['last_tag_close']   = '</li>';
            $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
            $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
            $config['num_tag_open']     = '<li class="page-item">';
            $config['num_tag_close']    = '</li>';
            $config['reuse_query_string'] = true;

            $this->pagination->initialize($config);

            $data['page'] = ($this->uri->segment(2) && is_numeric($this->uri->segment(2))) ? $this->uri->segment(2) : 0;
            $data["links"] = $this->pagination->create_links();

            $data['projects'] = $projects = $this->projects_model->get_project($this->session->userdata('workspace_id'), $this->session->userdata('user_id'), $filter, $user_type, $sort, $order, $config["per_page"], $data['page']);
            $i = 0;


            foreach ($projects as $row) {

                $is_favorite = fetch_details('favourite_projects', ['user_id' => $this->session->userdata('user_id'), 'workspace_id' => $this->session->userdata('workspace_id'), 'project_id' => $row['id']]);

                $projects_user_ids = explode(',', $row['user_id']);
                $projects_client_ids = explode(',', $row['client_id']);
                $data['projects'][$i] = $row;
                $data['projects'][$i]['is_favorite'] = isset($is_favorite[0]) && !empty($is_favorite[0]) ? 1 : 0;
                $data['projects'][$i]['project_progress'] = $this->projects_model->get_project_progress($this->session->userdata('workspace_id'), $row['id']);
                $data['projects'][$i]['projects_users'] = $this->users_model->get_user_array_responce($projects_user_ids);
                $data['projects'][$i]['projects_clients'] = $this->users_model->get_user_array_responce($projects_client_ids);
                $i++;
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $data['statuses_project'] =  $this->statuses_model->get_statuses_project($workspace_id);
            $data['statuses_task'] =  $this->statuses_model->get_statuses_task($workspace_id);
            $data['statuses'] = $this->statuses_model->get_statuses($workspace_id);
            $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
            $this->load->view('projects', $data);
        }
    }

    public function create()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("projects", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', str_replace(':', '', 'start_date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_date', str_replace(':', '', 'end_date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

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

            $priority = $this->input->post('priority');
            if ($priority == 'default') {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Please Select priority';
                echo json_encode($response);
                return false;
            }
            $admin_id = $this->session->userdata('user_id');

            if (!empty($this->input->post('users'))) {
                $user_ids = implode(",", $this->input->post('users')) . ',' . $admin_id;
            } else {
                $user_ids = $this->session->userdata('user_id');
            }

            $class_sts = $this->input->post('status');
            if ($class_sts == 'onhold' || $class_sts == 'cancelled') {
                $class = 'danger';
            } elseif ($class_sts == 'finished') {
                $class = 'success';
            } else {
                $class = 'info';
            }
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'status' => $this->input->post('status'),
                'class' => $class,
                'priority' => $priority,
                'budget' => $this->input->post('budget') ? $this->input->post('budget') : 0,
                'description' => strip_tags($this->input->post('description', true)),
                'user_id' => $user_ids,
                'client_id' => (!empty($this->input->post('clients'))) ? implode(",", $this->input->post('clients')) : '',
                'workspace_id' => $this->session->userdata('workspace_id'),
                'start_date' => strip_tags($this->input->post('start_date', true)),
                'end_date' => strip_tags($this->input->post('end_date', true)),
            );
            $project_id = $this->projects_model->create_project($data);

            if ($project_id != false) {
                // preparing activity log data
                $activity_data = array(
                    'user_id' => $this->session->userdata('user_id'),
                    'workspace_id' => $this->session->userdata('workspace_id'),
                    'user_name' => get_user_name(),
                    'type' => 'Project',
                    'project_id' => $project_id,
                    'project_title' => strip_tags($this->input->post('title', true)),
                    'activity' => 'Created',
                    'message' => get_user_name() . ' Created Project ' . strip_tags($this->input->post('title', true)),
                );
                $this->activity_model->store_activity($activity_data);

                //preparing notification data
                if (!empty($this->input->post('users')) || !empty($this->input->post('clients'))) {

                    $user_ids = !empty($this->input->post('users')) ? $this->input->post('users') : array();
                    $client_ids = !empty($this->input->post('clients')) ? $this->input->post('clients') : array();
                    $user_ids = array_merge($user_ids, $client_ids);
                    if (($key = array_search($admin_id, $user_ids)) !== false) {
                        unset($user_ids[$key]);
                    }
                    $user_ids = implode(",", $user_ids);
                    $project = $this->projects_model->get_project_by_id($project_id);
                    $admin = $this->users_model->get_user_by_id($admin_id);
                    $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];

                    $email_templates =  fetch_details('email_templates', ['type' => "project_create"]);

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));

                    $subject = isset($title) ? $title : 'New project assigned';

                    $email_first_name = '{first_name}';
                    $email_last_name = '{last_name}';
                    $email_project_title = '{project_title}';
                    $email_project_id = '{project_id}';

                    $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);

                    $message = (!empty($email_templates)) ? str_replace(array($email_first_name, $email_last_name, $email_project_title, $email_project_id), array($admin[0]['first_name'], $admin[0]['last_name'], $project[0]['title'], $project[0]['id']), $hashtag)  : $admin_name . " assigned you new project - <b>" . $project[0]['title'] . "</b> ID <b>#" . $project[0]['id'] . "</b>.";
                    // $title = $admin_name . " assigned you new project <b>" . $project[0]['title'] . "</b>.";

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));
                    $title = isset($title) ? $title : $admin_name . " assigned you new project <b>" . $project[0]['title'] . "</b>.";

                    $notification = output_escaping(trim($message, '"'));

                    $notification_data = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'workspace_id' => $this->session->userdata('workspace_id'),
                        'title' => $title,
                        'user_ids' => $user_ids,
                        'type' => 'project',
                        'type_id' => $project_id,
                        'notification' => $notification,
                    );

                    if (!empty($user_ids)) {
                        $user_ids = explode(",", $user_ids);
                        $this->projects_model->send_email($user_ids, $project_id, $admin_id, $subject);
                        $this->notifications_model->store_notification($notification_data);
                    }
                }
                $this->session->set_flashdata('message', 'Project Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Project could not Created! Try again!');
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
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("projects", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', str_replace(':', '', 'start_date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_date', str_replace(':', '', 'end_date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

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

            $priority = $this->input->post('priority');
            if ($priority == 'default') {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Please Select priority';
                echo json_encode($response);
                return false;
            }

            $admin_id = $this->session->userdata('user_id');



            $class_sts = $this->input->post('status');
            if ($class_sts == 'onhold' || $class_sts == 'cancelled') {
                $class = 'danger';
            } elseif ($class_sts == 'finished') {
                $class = 'success';
            } else {
                $class = 'info';
            }
            // checking for new users

            $user_ids = !empty($this->input->post('users')) ? $this->input->post('users') : array();
            $client_ids = !empty($this->input->post('clients')) ? $this->input->post('clients') : array();
            if (($key = array_search($admin_id, $user_ids)) !== false) {
                unset($user_ids[$key]);
            }
            $user_ids = array_merge($user_ids, $client_ids);

            $project_users = $this->projects_model->get_project_users($this->input->post('update_id'));
            $project_users = explode(",", $project_users[0]['user_id']);
            if (($key = array_search($admin_id, $project_users)) !== false) {
                unset($project_users[$key]);
            }
            $project_clients = $this->projects_model->get_project_clients($this->input->post('update_id'));


            $project_clients = explode(",", $project_clients[0]['client_id']);
            $project_users = array_merge($project_clients, $project_users);
            if (!empty($user_ids)) {
                $new_users = array();
                for ($i = 0; $i < count($user_ids); $i++) {
                    if (!in_array($user_ids[$i], $project_users)) {
                        array_push($new_users, $user_ids[$i]);
                    }
                }
            }
            if (!empty($this->input->post('users'))) {
                $user_ids = implode(",", $this->input->post('users')) . ',' . $admin_id;
            } else {
                $user_ids = $this->session->userdata('user_id');
            }
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'status' => $this->input->post('status'),
                'class' => $class,
                'priority' => $priority,
                'budget' => $this->input->post('budget') ? $this->input->post('budget') : 0,
                'description' => strip_tags($this->input->post('description', true)),
                'user_id' => $user_ids,
                'client_id' => (!empty($this->input->post('clients'))) ? implode(",", $this->input->post('clients')) : '',
                'workspace_id' => $this->session->userdata('workspace_id'),
                'start_date' => strip_tags($this->input->post('start_date', true)),
                'end_date' => strip_tags($this->input->post('end_date', true)),
                'updated_at' => strip_tags($this->input->post('updated_at', true))
            );
            //preparing activity log data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Project',
                'project_id' => $this->input->post('update_id'),
                'project_title' => get_project_title($this->input->post('update_id')),
                'activity' => 'Updated',
                'message' => get_user_name() . ' Updated Project ' . get_project_title($this->input->post('update_id')),
            );
            if ($this->projects_model->edit_project($data, $this->input->post('update_id'))) {
                $this->activity_model->store_activity($activity_data);

                //preparing notfication data

                // $project_users = $this->projects_model->get_project_users($this->input->post('update_id'));

                // if (!empty($project_users)) {
                // 	$project_users = explode(",", $project_users[0]['user_id']);
                // 	if (($key = array_search($admin_id, $project_users)) !== false) {
                // 		unset($project_users[$key]);
                // 	}
                // 	$project_users = array_diff( $project_users, $new_users);
                // 	$user_ids = implode(",", $project_users);
                // 	$project = $this->projects_model->get_project_by_id($this->input->post('update_id'));
                // 	$admin = $this->users_model->get_user_by_id($admin_id);
                // 	$admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];
                // 	$notification = $admin_name . " updated project - <b>" . $project[0]['title'] . "</b> ID <b>#" . $project[0]['id'] . "</b>.";
                // 	$title = $admin_name . " updated project <b>" . $project[0]['title'] . "</b>.";
                // 	$notification_data = array(
                // 		'user_id' => $this->session->userdata('user_id'),
                //		'workspace_id' => $this->session->userdata('workspace_id'),
                // 		'title' => $title,
                // 		'user_ids' => $user_ids,
                // 		'type' => 'project',
                // 		'type_id' => $this->input->post('update_id'),
                // 		'notification' => $notification,
                // 	);
                // 	$this->notifications_model->store_notification($notification_data);
                // }


                // print_r($new_users);
                if (!empty($new_users)) {
                    $user_ids = implode(",", $new_users);
                    $project = $this->projects_model->get_project_by_id($this->input->post('update_id'));
                    $admin = $this->users_model->get_user_by_id($admin_id);
                    $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];

                    $email_templates =  fetch_details('email_templates', ['type' => "project_edit"]);

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));

                    $subject = isset($title) ? $title : 'New project assigned';

                    $email_first_name = '{first_name}';
                    $email_last_name = '{last_name}';
                    $email_project_title = '{project_title}';
                    $email_project_id = '{project_id}';

                    $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);

                    $message = (!empty($email_templates)) ? str_replace(array($email_first_name, $email_last_name, $email_project_title, $email_project_id), array($admin[0]['first_name'], $admin[0]['last_name'], $project[0]['title'], $project[0]['id']), $hashtag)  : $admin_name . " assigned you new project - <b>" . $project[0]['title'] . "</b> ID <b>#" . $project[0]['id'] . "</b>.";
                    // $title = $admin_name . " assigned you new project <b>" . $project[0]['title'] . "</b>.";

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));
                    $title = isset($title) ? $title : $admin_name . " assigned you new project <b>" . $project[0]['title'] . "</b>.";

                    $notification = output_escaping(trim($message, '"'));

                    $notification_data = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'workspace_id' => $this->session->userdata('workspace_id'),
                        'title' => $title,
                        'user_ids' => $user_ids,
                        'type' => 'project',
                        'type_id' => $this->input->post('update_id'),
                        'notification' => $notification,
                    );
                    $this->projects_model->send_email($new_users, $this->input->post('update_id'), $admin_id, $subject);
                    $this->notifications_model->store_notification($notification_data);
                }

                $this->session->set_flashdata('message', 'Project Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Project could not Updated! Try again!');
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

    public function get_project_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $project_id = $this->input->post('id');

            if (empty($project_id) || !is_numeric($project_id) || $project_id < 1) {
                redirect('projects', 'refresh');
                return false;
                exit(0);
            }

            $data = $this->projects_model->get_project_by_id($project_id);

            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }

    public function delete()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("projects", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        $project_id = $this->uri->segment(3);
        if (!empty($project_id) && is_numeric($project_id)  || $project_id < 1) {
            //preparing activity data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Project',
                'project_id' => $project_id,
                'project_title' => get_project_title($project_id),
                'activity' => 'Deleted',
                'message' => get_user_name() . ' Deleted Project ' . get_project_title($project_id),
            );
            //preparing notification data

            $project_users = $this->projects_model->get_project_users($project_id);
            $admin_id = $this->session->userdata('user_id');
            if (!empty($project_users)) {
                $project_users = explode(",", $project_users[0]['user_id']);
                if (($key = array_search($admin_id, $project_users)) !== false) {
                    unset($project_users[$key]);
                }

                $admin = $this->users_model->get_user_by_id($admin_id);
                $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];
                $user_ids = implode(",", $project_users);
                $project = $this->projects_model->get_project_by_id($project_id);
                $admin = $this->users_model->get_user_by_id($admin_id);
                $notification = $admin_name . " deleted project - <b>" . $project[0]['title'] . "</b> ID <b>#" . $project[0]['id'] . "</b>.";
                $title = $admin_name . " deleted project <b>" . $project[0]['title'] . "</b>.";
                $notification_data = array(
                    'user_id' => $this->session->userdata('user_id'),
                    'workspace_id' => $this->session->userdata('workspace_id'),
                    'title' => $title,
                    'user_ids' => $user_ids,
                    'type' => 'project',
                    'type_id' => $project_id,
                    'notification' => $notification,
                );
            }

            if ($this->projects_model->delete_project($project_id)) {
                $this->activity_model->store_activity($activity_data);
                // if(!empty($project_users)){
                // 	$this->notifications_model->store_notification($notification_data);
                // }

                $this->session->set_flashdata('message', 'Project deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Project could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect('projects', 'refresh');
    }

    public function delete_project_file()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $file_id = $this->uri->segment(3);
        if (!empty($file_id) && is_numeric($file_id)  || $file_id < 1) {
            $project_id = get_project_id_by_file_id($file_id);
            //preparing activity data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Project File',
                'project_id' => $project_id,
                'project_title' => get_project_title($project_id),
                'file_id' => $file_id,
                'file_name' => get_file_name($file_id),
                'activity' => 'Deleted',
                'message' => get_user_name() . ' Deleted File ' . get_file_name($file_id),
            );
            if ($this->projects_model->delete_file($file_id)) {
                $this->activity_model->store_activity($activity_data);
                $this->session->set_flashdata('message', 'File deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'File could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
    }

    public function details()
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
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;
            $data['all_user'] = $this->users_model->get_user($user_ids);
            $data['client_ids'] = $client_ids = fetch_details('users', ['id' => $this->session->userdata('user_id')]);
            $project_id = $this->uri->segment(3);

            if (empty($project_id) || !is_numeric($project_id) || $project_id < 1) {
                redirect('projects', 'refresh');
                return false;
                exit(0);
            }
            $user_id = $this->session->userdata('user_id');
            $notification_id = $this->notifications_model->get_id_by_type_id($project_id, 'project', $user_id);
            if (!empty($notification_id) && isset($notification_id[0])) {
                $notification_id = $notification_id[0]['id'];
                $this->notifications_model->mark_notification_as_read($notification_id, $this->session->userdata('user_id'));
            }
            $projects = $this->projects_model->get_project_by_id($project_id);


            if (!empty($projects) && isset($projects[0])) {

                $projects_user_ids = explode(',', $projects[0]['user_id']);
                $projects_client_ids = explode(',', $projects[0]['client_id']);
                $project_users = array_merge($projects_user_ids, $projects_client_ids);

                if (in_array($user_id, $project_users) || is_admin()) {
                    $data['projects_data'] = $projects[0];

                    $data['projects_data']['projects_users'] = $this->users_model->get_user_array_responce($projects_user_ids);

                    $data['projects_data']['projects_clients'] = $this->users_model->get_user_array_responce($projects_client_ids);

                    $milestones = $this->milestones_model->get_milestone_by_project_id($project_id, $this->session->userdata('workspace_id'));
                    $data['milestones'] = $milestones;

                    $type = 'project';
                    $data['files'] = $this->projects_model->get_files($project_id, $type);

                    $workspace_id = $this->session->userdata('workspace_id');
                    $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
                    $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                    $data['projects'] = $projects;
                    $data['statuses_project'] =  $this->statuses_model->get_statuses_project($workspace_id);
                    $data['statuses'] = $this->statuses_model->get_statuses($workspace_id);
                    $data['statuses_task'] =  $this->statuses_model->get_statuses_task($workspace_id);
                    $this->load->view('project-details', $data);
                } else {
                    $this->session->set_flashdata('message', 'You are not authorized to view this project.');
                    $this->session->set_flashdata('message_type', 'error');
                    redirect('projects', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', 'This project was deleted.');
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
            }
        }
    }
    public function filter_by_task_status()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $project_id = $this->input->get('project_id');
            $statuses = $this->statuses_model->get_statuses_task_project($project_id);

            $data = [];
            $statusTypes = [];

            foreach ($statuses as $status) {
                $statusTypes[] = $status['status'];
                $this->db->select('COUNT(id) as total,status');
                $this->db->from('tasks');
                $this->db->where('status', $status['status']);
                $this->db->where('project_id', $project_id);
                $this->db->order_by('id');

                $query = $this->db->get();
                $taskCount = $query->result_array();

                $data[] = $taskCount ? $taskCount[0]['total'] : 0;
            }

            $response = [
                'labels' => $statusTypes,
                'data' => $data
            ];

            echo json_encode($response);
        }
    }
    public function create_milestone()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("milestone", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('cost', str_replace(':', '', 'cost is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            $class_sts = $this->input->post('status');
            if ($class_sts == 'incomplete') {
                $class = 'danger';
            } else {
                $class = 'success';
            }
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'status' => $this->input->post('status'),
                'class' => $class,
                'cost' => $this->input->post('cost'),
                'description' => strip_tags($this->input->post('description', true)),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'project_id' => $this->uri->segment(3)
            );
            $milestone_id = $this->milestones_model->create_milestone($data);
            //preparing activity data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Project Milestone',
                'project_id' => $this->uri->segment(3),
                'project_title' => get_project_title($this->uri->segment(3)),
                'milestone_id' => $milestone_id,
                'milestone' => get_milestone_title($milestone_id),
                'activity' => 'Created',
                'message' => get_user_name() . ' Created Milestone ' . get_milestone_title($milestone_id),
            );
            if ($milestone_id != false) {

                $this->activity_model->store_activity($activity_data);
                $this->session->set_flashdata('message', 'Milestone Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Milestone could not Created! Try again!');
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

    public function edit_milestone()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("milestone", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('cost', str_replace(':', '', 'cost is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            $class_sts = $this->input->post('status');
            if ($class_sts == 'incomplete') {
                $class = 'danger';
            } else {
                $class = 'success';
            }

            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'status' => $this->input->post('status'),
                'class' => $class,
                'cost' => $this->input->post('cost'),
                'description' => strip_tags($this->input->post('description', true))
            );
            $project_id = get_project_id_by_milestone_id($this->input->post('update_id'));
            //preparing activity data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Project Milestone',
                'project_id' => $project_id,
                'project_title' => get_project_title($project_id),
                'milestone_id' => $this->input->post('update_id'),
                'milestone' => get_milestone_title($this->input->post('update_id')),
                'activity' => 'Updated',
                'message' => get_user_name() . ' Updated Milestone ' . get_milestone_title($this->input->post('update_id')),
            );
            if ($this->milestones_model->edit_milestone($data, $this->input->post('update_id'))) {
                $this->activity_model->store_activity($activity_data);
                $this->session->set_flashdata('message', 'Milestone Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Milestone could not Updated! Try again!');
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

    public function delete_milestone()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("milestone", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $milestone_id = $this->uri->segment(3);
        $project_id = $this->uri->segment(4);

        if (!empty($milestone_id) && is_numeric($milestone_id) && !empty($project_id) && is_numeric($project_id)) {
            //preparing activity data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Project Milestone',
                'project_id' => $project_id,
                'project_title' => get_project_title($project_id),
                'milestone_id' => $milestone_id,
                'milestone' => get_milestone_title($milestone_id),
                'activity' => 'Deleted',
                'message' => get_user_name() . ' Deleted Milestone ' . get_milestone_title($milestone_id),
            );
            if ($this->milestones_model->delete_milestone($milestone_id)) {
                $this->activity_model->store_activity($activity_data);
                $this->session->set_flashdata('message', 'Milestone deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Milestone could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect('projects/details/' . $project_id, 'refresh');
    }

    public function get_milestone_by_id()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $milestone_id = $this->input->post('id');


            if (empty($milestone_id) || !is_numeric($milestone_id) || $milestone_id < 1) {
                redirect('projects', 'refresh');
                return false;
                exit(0);
            }

            $data = $this->milestones_model->get_milestone_by_id($milestone_id);


            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }

    public function tasks($project_id = '')
    {
        if (!check_permissions("tasks", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            if (!isset($project_id) || empty($project_id) || !is_numeric($project_id) || $project_id < 1) {
                redirect('tasks', 'refresh');
                return false;
                exit(0);
            }
            $user_id = $this->session->userdata('user_id');

            $projects = $this->projects_model->get_project_by_id($project_id);

            if (!empty($projects) && isset($projects[0])) {
                $notification_id = $this->notifications_model->get_id_by_type_id($project_id, 'task', $user_id);
                if (!empty($notification_id) && isset($notification_id[0])) {
                    $notification_id = $notification_id[0]['id'];
                    $this->notifications_model->mark_notification_as_read($notification_id, $this->session->userdata('user_id'));
                }
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
                }
                $data['is_admin'] =  $this->ion_auth->is_admin();

                $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
                $user_ids = explode(',', $current_workspace_id[0]->user_id);


                $project_user_ids = explode(',', isset($projects[0]['user_id']) ? $projects[0]['user_id'] : '');

                $section = array_map('trim', $user_ids);
                $user_ids = $section;
                $data['all_user'] = $this->users_model->get_user($project_user_ids);
                $data['client_ids'] = $client_ids = fetch_details('users', ['id' => $this->session->userdata('user_id')]);
                $admin_ids = explode(',', $current_workspace_id[0]->admin_id);
                $section = array_map('trim', $admin_ids);
                $data['admin_ids'] = $admin_ids = $section;
                $filter = '';
                $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : '';
                $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : '';
                $data['all_projects'] = $this->projects_model->get_project($this->session->userdata('workspace_id'), $this->session->userdata('user_id'), $filter, $sort, $order);

                $projects = $this->projects_model->get_project_by_id($project_id);
                $data['current_project'] = $projects[0];

                $data['milestones'] = $this->milestones_model->get_milestone_by_project_id($project_id, $this->session->userdata('workspace_id'));

                $data['tasks'] = $tasks = $this->tasks_model->get_task_by_project_id($project_id);
                $workspace_id = $this->session->userdata('workspace_id');
                $data['statuses'] = $this->statuses_model->get_statuses_task($workspace_id);
                $data['statuses_task'] = $this->statuses_model->get_statuses($workspace_id);
                $data['status_data'] = array();
                foreach ($data['statuses'] as &$status) {
                    $status['count'] = 0;
                    foreach ($tasks as $task) {
                        if (isset($task['status']) && $task['status'] == $status['status']) {
                            $status['count']++;
                        }
                    }
                }
                $user_names = $this->users_model->get_user_names();
                $data['user_names'] = $user_names;
                if (!empty($workspace_id)) {
                    $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                    $data['projects'] = $projects;
                    $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();

                    $this->load->view('tasks', $data);
                } else {
                    redirect('home', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', 'No tasks found.');
                $this->session->set_flashdata('message_type', 'error');
                redirect('projects/tasks', 'refresh');
            }
        }
    }

    public function create_task()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("tasks", "create", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('priority', str_replace(':', '', 'priority is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', str_replace(':', '', 'start date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('due_date', str_replace(':', '', 'due date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            $class_sts = $this->input->post('priority');
            if ($class_sts == 'high') {
                $class = 'danger';
            } elseif ($class_sts == 'medium') {
                $class = 'success';
            } else {
                $class = 'info';
            }
            $user_ids = (!empty($_POST['user_id'])) ? implode(",", $this->input->post('user_id')) : "";
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'priority' => $this->input->post('priority'),
                'status' => $this->input->post('status'),
                'class' => $class,
                'project_id' => $this->uri->segment(3),
                'description' => strip_tags($this->input->post('description', true)),
                'user_id' => $user_ids,
                'workspace_id' => $this->session->userdata('workspace_id'),
                'milestone_id' => $this->input->post('milestone_id'),
                'start_date' => strip_tags($this->input->post('start_date', true)),
                'due_date' => strip_tags($this->input->post('due_date', true))
            );
            $task_id = $this->tasks_model->create_task($data);

            // preparing activity log data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Task',
                'project_id' => $this->uri->segment(3),
                'project_title' => get_project_title($this->uri->segment(3)),
                'task_id' => $task_id,
                'task_title' => strip_tags($this->input->post('title', true)),
                'activity' => 'Created',
                'message' => get_user_name() . ' Created Task ' . strip_tags($this->input->post('title', true)),
            );
            $admin_id = $this->session->userdata('user_id');
            $user_ids = $this->input->post('user_id');
            $task_title = strip_tags($this->input->post('title', true));
            if ($task_id != false) {
                $this->activity_model->store_activity($activity_data);
                if (!empty($user_ids)) {
                    if (($key = array_search($admin_id, $user_ids)) !== false) {
                        unset($user_ids[$key]);
                    }

                    //preparing notification data
                    $user_ids = implode(",", $user_ids);
                    $project = $this->projects_model->get_project_by_id($this->uri->segment(3));
                    $admin = $this->users_model->get_user_by_id($admin_id);
                    $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];

                    $email_templates =  fetch_details('email_templates', ['type' => "task_create"]);
                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));

                    $subject = isset($title) ? $title : 'New Task Assigned';

                    $email_first_name = '{first_name}';
                    $email_last_name = '{last_name}';
                    $email_task_title = '{task_title}';
                    $email_task_id = '{task_id}';
                    $email_project_title = '{project_title}';
                    $email_project_id = '{project_id}';

                    $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);

                    $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];
                    // $title = $admin_name . " assigned you new task <b>" . $task_title . "</b>.";

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));
                    $title = isset($title) ? $title : $admin_name . " assigned you new task <b>" . $task_title . "</b>.";

                    $message = (!empty($email_templates)) ? str_replace(array($email_first_name, $email_last_name, $email_task_title, $email_task_id, $email_project_title, $email_project_id), array($admin[0]['first_name'], $admin[0]['last_name'], $task_title, $task_id, $project[0]['title'], $project[0]['id']), $hashtag)  :  $admin_name . " assigned you new task - <b>" . $task_title . "</b> ID <b>#" . $task_id . "</b> , Project - <b>" . $project[0]['title'] . "</b> ID <b>#" . $project[0]['id'] . "</b>";
                    $notification = output_escaping(trim($message, '"'));

                    $notification_data = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'workspace_id' => $this->session->userdata('workspace_id'),
                        'title' => $title,
                        'user_ids' => $user_ids,
                        'type' => 'task',
                        'type_id' => $this->uri->segment(3),
                        'notification' => $notification,
                    );
                    if (!empty($user_ids)) {
                        $user_ids = explode(",", $user_ids);
                        $this->tasks_model->send_email($user_ids, $task_id, $admin_id);
                        $this->notifications_model->store_notification($notification_data);
                    }
                }
                $this->projects_model->project_task_count_update($this->uri->segment(3));
                $this->session->set_flashdata('message', 'Task Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Task could not Created! Try again!');
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

    public function edit_task()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("tasks", "update", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('priority', str_replace(':', '', 'priority is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', str_replace(':', '', 'start date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('due_date', str_replace(':', '', 'due date is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            $class_sts = $this->input->post('priority');
            if ($class_sts == 'high') {
                $class = 'danger';
            } elseif ($class_sts == 'medium') {
                $class = 'success';
            } else {
                $class = 'info';
            }
            // checking for new users
            $task_users = $this->tasks_model->get_task_users($this->input->post('update_id'));
            $user_ids = (!empty($_POST['user_id'])) ? $this->input->post('user_id') : "";
            $task_users = explode(",", $task_users[0]['user_id']);
            if (!empty($user_ids)) {
                $new_users = array();
                for ($i = 0; $i < count($user_ids); $i++) {
                    if (!in_array($user_ids[$i], $task_users)) {
                        array_push($new_users, $user_ids[$i]);
                    }
                }
            }
            $admin_id = $this->session->userdata('user_id');
            if (($key = array_search($admin_id, $new_users)) !== false) {
                unset($new_users[$key]);
            }
            $user_ids = (!empty($_POST['user_id'])) ? implode(",", $this->input->post('user_id')) : "";
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'priority' => $this->input->post('priority'),
                'status' => $this->input->post('status'),
                'class' => $class,
                'description' => strip_tags($this->input->post('description', true)),
                'user_id' => $user_ids,
                'milestone_id' => $this->input->post('milestone_id'),
                'start_date' => strip_tags($this->input->post('start_date', true)),
                'due_date' => strip_tags($this->input->post('due_date', true))
            );
            $project_id = get_project_id_by_task_id($this->input->post('update_id'));
            //preparing activity log data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Task',
                'project_id' => $project_id,
                'project_title' => get_project_title($project_id),
                'task_id' => $this->input->post('update_id'),
                'task_title' => get_task_title($this->input->post('update_id')),
                'activity' => 'Updated',
                'message' => get_user_name() . ' Updated Task ' . get_task_title($this->input->post('update_id')),
            );
            $task_id = $this->input->post('update_id');
            $admin_id = $this->session->userdata('user_id');
            $task_title = strip_tags($this->input->post('title', true));

            if ($this->tasks_model->edit_task($data, $this->input->post('update_id'))) {
                $this->activity_model->store_activity($activity_data);
                if (!empty($new_users)) {
                    //preparing notification data
                    $user_ids = implode(",", $new_users);
                    $project = $this->projects_model->get_project_by_id($project_id);
                    $admin = $this->users_model->get_user_by_id($admin_id);
                    $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];

                    $email_templates =  fetch_details('email_templates', ['type' => "task_edit"]);

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));

                    $subject = isset($title) ? $title : 'New Task Assigned';

                    $email_first_name = '{first_name}';
                    $email_last_name = '{last_name}';
                    $email_task_title = '{task_title}';
                    $email_task_id = '{task_id}';
                    $email_project_title = '{project_title}';
                    $email_project_id = '{project_id}';

                    $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);

                    $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];
                    // $title = $admin_name . " assigned you new task <b>" . $task_title . "</b>.";

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));
                    $title = isset($title) ? $title : $admin_name . " assigned you new task <b>" . $task_title . "</b>.";

                    $message = (!empty($email_templates)) ? str_replace(array($email_first_name, $email_last_name, $email_task_title, $email_task_id, $email_project_title, $email_project_id), array($admin[0]['first_name'], $admin[0]['last_name'], $task_title, $task_id, $project[0]['title'], $project[0]['id']), $hashtag)  :  $admin_name . " assigned you new task - <b>" . $task_title . "</b> ID <b>#" . $task_id . "</b> , Project - <b>" . $project[0]['title'] . "</b> ID <b>#" . $project[0]['id'] . "</b>";

                    $notification = output_escaping(trim($message, '"'));
                    $notification_data = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'workspace_id' => $this->session->userdata('workspace_id'),
                        'title' => $title,
                        'user_ids' => $user_ids,
                        'type' => 'task',
                        'type_id' => $project_id,
                        'notification' => $notification,
                    );

                    $this->notifications_model->store_notification($notification_data);
                    $this->tasks_model->send_email($new_users, $this->input->post('update_id'), $this->session->userdata('user_id'), $subject);
                }
                $this->session->set_flashdata('message', 'Task Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Task could not Updated! Try again!');
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

    public function delete_task()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
            $this->session->set_flashdata('message_type', 'error');
            redirect('home', 'refresh');
            return false;
            exit();
        }
        if (!check_permissions("tasks", "delete", "", true)) {
            return response(PERMISSION_ERROR_MESSAGE);
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $task_id = $this->uri->segment(3);
        $project_id = $this->uri->segment(4);
        if (!empty($task_id) && is_numeric($task_id) && !empty($project_id) && is_numeric($project_id)) {
            //preparing activity log data
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'user_name' => get_user_name(),
                'type' => 'Task',
                'project_id' => $project_id,
                'project_title' => get_project_title($project_id),
                'task_id' => $task_id,
                'task_title' => get_task_title($task_id),
                'activity' => 'Deleted',
                'message' => get_user_name() . ' Deleted Task ' . get_task_title($task_id),
            );
            //preparing notification data
            $task_users = $this->tasks_model->get_task_users($task_id);
            $task_users = $task_users[0]['user_id'];
            $admin_id = $this->session->userdata('user_id');
            $admin = $this->users_model->get_user_by_id($admin_id);
            $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];
            $notification = $admin_name . " deleted task - <b>" . get_task_title($task_id) . "</b> ID <b>#" . $task_id . "</b>.";
            $title = $admin_name . " deleted task <b>" . get_task_title($task_id) . "</b>.";

            $notification_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'workspace_id' => $this->session->userdata('workspace_id'),
                'title' => $title,
                'user_ids' => $task_users,
                'type' => 'task',
                'type_id' => $task_id,
                'notification' => $notification,
            );
            if ($this->tasks_model->delete_task($task_id)) {
                $this->activity_model->store_activity($activity_data);
                // $this->notifications_model->store_notification($notification_data);
                $this->projects_model->project_task_count_decreas($project_id);
                $this->session->set_flashdata('message', 'Task deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Task could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect('projects/tasks/' . $project_id, 'refresh');
    }

    public function get_task_by_id()
    {
        if (!check_permissions("tasks", "read", "", true)) {
            return redirect(base_url(), 'refresh');
        }
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $task_id = $this->input->post('id');

            if (empty($task_id) || !is_numeric($task_id) || $task_id < 1) {
                redirect('projects/tasks', 'refresh');
                return false;
                exit(0);
            }
            $data = $this->tasks_model->get_task_by_id($task_id);

            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            echo json_encode($data[0]);
        }
    }

    public function task_status_update()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $id = $this->input->post('id');
        $data = array(
            'status' => $this->input->post('status')
        );
        $project_id = get_project_id_by_task_id($id);
        $activity_data = array(
            'user_id' => $this->session->userdata('user_id'),
            'workspace_id' => $this->session->userdata('workspace_id'),
            'user_name' => get_user_name(),
            'type' => 'Task Status',
            'project_id' => $project_id,
            'project_title' => get_project_title($project_id),
            'task_id' => $id,
            'task_title' => get_task_title($id),
            'activity' => 'Updated',
            'message' => get_user_name() . ' Updated Task Status For Task ' . get_task_title($id),
        );
        if ($this->tasks_model->task_status_update($data, $id)) {
            $this->activity_model->store_activity($activity_data);
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

    public function add_task_details()
    {
        $id = '';
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('workspace_id', str_replace(':', '', 'workspace_id is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('task_id', str_replace(':', '', 'task_id is empty.'), 'trim|required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            if (!empty($this->input->post('comment'))) {
                $data = array(
                    'comment' => output_escaping($this->input->post('comment', true)),
                    'workspace_id' => $this->input->post('workspace_id'),
                    'project_id' => $this->input->post('project_id'),
                    'task_id' => $this->input->post('task_id'),
                    'user_id' => $this->session->userdata('user_id')
                );

                $id = $this->tasks_model->add_task_comment($data);
                $comment = output_escaping($this->input->post('comment', true));
                $lenght = strlen($comment);
                $comment = $lenght > 25 ? mb_substr($comment, 0, 25) . '...' : $comment;
                $activity_data = array(
                    'user_id' => $this->session->userdata('user_id'),
                    'workspace_id' => $this->session->userdata('workspace_id'),
                    'user_name' => get_user_name(),
                    'type' => 'Comment',
                    'project_id' => $this->input->post('project_id'),
                    'project_title' => get_project_title($this->input->post('project_id')),
                    'task_id' => $this->input->post('task_id'),
                    'task_title' => get_task_title($this->input->post('task_id')),
                    'comment_id' => $id,
                    'comment' => $comment,
                    'activity' => 'Created',
                    'message' => get_user_name() . ' Created Comment ' . strip_tags($this->input->post('comment', true)),
                );
                $this->activity_model->store_activity($activity_data);
                $this->projects_model->project_comment_count_update($this->input->post('project_id'));
                $this->tasks_model->task_comment_count_update($this->input->post('task_id'));
            }
            $this->config->load('taskhub');
            if (!empty($_FILES['file']['name'])) {

                $config['upload_path']          = './assets/project/';
                $config['allowed_types']        = $this->config->item('allowed_types');
                $config['overwrite']             = false;
                $config['max_size']             = 10000;
                $config['max_width']            = 0;
                $config['max_height']           = 0;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $file_data = $this->upload->data();
                    $data = array(
                        'original_file_name' => $file_data['orig_name'],
                        'file_name' => $file_data['file_name'],
                        'file_extension' => $file_data['file_ext'],
                        'file_size' => $this->custom_funcation_model->format_size_units($file_data['file_size']),
                        'user_id' => $this->session->userdata('user_id'),
                        'workspace_id' => $this->input->post('workspace_id'),
                        'type' => 'task',
                        'type_id' => $this->input->post('task_id')
                    );
                    $id = $this->projects_model->add_file($data);
                } else {
                    $this->session->set_flashdata('message', 'Image Could not Added! Try again!');
                    $this->session->set_flashdata('message_type', 'error');
                    $response['error'] = false;

                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $response['message'] = 'Successful';
                    echo json_encode($response);
                }
            }

            if ($id != false && $id != '') {
                $this->session->set_flashdata('message', 'Added successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Could not Added! Try again!');
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


    public function add_project_file()
    {

        $id = '';
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->form_validation->set_rules('workspace_id', str_replace(':', '', 'workspace_id is empty.'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('project_id', str_replace(':', '', 'project_id is empty.'), 'trim|required|xss_clean');
        $this->config->load('taskhub');
        if ($this->form_validation->run() === TRUE) {
            if (!empty($_FILES['file']['name'])) {

                if (!is_dir('./assets/project/')) {
                    mkdir('./assets/project/', 0777, TRUE);
                }

                $config['upload_path']          = './assets/project/';
                $config['allowed_types']        = $this->config->item('allowed_types');
                $config['overwrite']             = false;
                $config['max_size']             = 900000;
                $config['max_width']            = 0;
                $config['max_height']           = 0;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $file_data = $this->upload->data();
                    $data = array(
                        'original_file_name' => $file_data['orig_name'],
                        'file_name' => $file_data['file_name'],
                        'file_extension' => $file_data['file_ext'],
                        'file_size' => $this->custom_funcation_model->format_size_units($file_data['file_size']),
                        'user_id' => $this->session->userdata('user_id'),
                        'workspace_id' => $this->input->post('workspace_id'),
                        'type' => 'project',
                        'type_id' => $this->input->post('project_id')
                    );
                    $activity_data = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'workspace_id' => $this->session->userdata('workspace_id'),
                        'user_name' => get_user_name(),
                        'type' => 'Project File',
                        'project_id' => $this->input->post('project_id'),
                        'project_title' => get_project_title($this->input->post('project_id')),
                        'file_id' => $id,
                        'file_name' => get_file_name($id),
                        'activity' => 'Uploaded',
                        'message' => get_user_name() . ' Uploaded File ' . get_file_name($id),
                    );

                    $id = $this->projects_model->add_file($data);
                    if ($id != false) {
                        $this->activity_model->store_activity($activity_data);
                        $this->session->set_flashdata('message', 'File(s) uploaded successfully.');
                        $this->session->set_flashdata('message_type', 'success');
                    } else {
                        $this->session->set_flashdata('message', 'Something went wrong please try again.');
                        $this->session->set_flashdata('message_type', 'error');
                    }
                } else {
                    $response['error'] = false;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $response['message'] = 'Successful';
                    echo json_encode($response);
                }
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

    public function get_project_files()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $this->form_validation->set_rules('project_id', str_replace(':', '', 'project_id is empty.'), 'trim|required|xss_clean');

            if ($this->form_validation->run() === TRUE) {
                $type = 'project';
                $project_id = $this->input->post('project_id');
                $data = $this->projects_model->get_files($project_id, $type);

                $response['error'] = false;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['data'] = $data;
                $response['message'] = 'Successful';
                echo json_encode($response);
            } else {
                $response['error'] = false;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = validation_errors();
                echo json_encode($response);
            }
        }
    }


    public function calendar()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
            if (!check_permissions("projects", "read", "", true)) {
                redirect('home', 'refresh');
            }
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
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;
            $data['all_user'] = $this->users_model->get_user($user_ids);
            $data['client_ids'] = $client_ids = fetch_details('users', ['id' => $this->session->userdata('user_id')]);
            $admin_ids = explode(',', $current_workspace_id[0]->admin_id);
            $user_id = $this->session->userdata('user_id');
            $user_type = is_client() ? 'client' : 'normal';
            $workspace_id = $this->session->userdata('workspace_id');
            $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
            $data['projects'] = $projects;
            $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
            if (!empty($workspace_id)) {
                $projects = $this->projects_model->fetch_projects($workspace_id, $user_id, $user_type);
                $data['projects'] = $projects;
                $data['statuses_project'] =  $this->statuses_model->get_statuses_project($workspace_id);
                $data['statuses'] = $this->statuses_model->get_statuses($workspace_id);
                $this->load->view('projects-calendar', $data);
                return false;
                exit();
            } else {
                redirect('home', 'refresh');
            }
        }
    }

    public function favorite_projects()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!check_permissions("projects", "read", "", true)) {
                redirect('home', 'refresh');
            }
            $role = $this->session->userdata('role');
            $filter = (isset($_GET['filter']) && !empty($_GET['filter'])) ? $_GET['filter'] : '';
            $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : '';
            $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : '';
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
            $data['client_ids'] = $client_ids = fetch_details('users', ['id' => $this->session->userdata('user_id')]);
            $admin_ids = explode(',', $current_workspace_id[0]->admin_id);
            $section = array_map('trim', $admin_ids);
            $data['admin_ids'] = $admin_ids = $section;

            $this->config->load('taskhub');
            $data['progress_bar_classes'] = $this->config->item('progress_bar_classes');
            $user_type = is_client() ? 'client' : 'normal';
            $projects_counts = $this->projects_model->get_favorite_project($this->session->userdata('workspace_id'), $this->session->userdata('user_id'), $filter, $user_type);

            $config = array();
            $config["base_url"] = base_url('projects/favorite_projects');
            $config["total_rows"] = count($projects_counts);
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;

            $config['next_link']        = '›';
            $config['prev_link']        = '‹';
            $config['first_link']       = false;
            $config['last_link']        = false;
            $config['full_tag_open']    = '<ul class="pagination justify-content-center">';
            $config['full_tag_close']   = '</ul>';
            $config['attributes']       = ['class' => 'page-link'];
            $config['first_tag_open']   = '<li class="page-item">';
            $config['first_tag_close']  = '</li>';
            $config['prev_tag_open']    = '<li class="page-item">';
            $config['prev_tag_close']   = '</li>';
            $config['next_tag_open']    = '<li class="page-item">';
            $config['next_tag_close']   = '</li>';
            $config['last_tag_open']    = '<li class="page-item">';
            $config['last_tag_close']   = '</li>';
            $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
            $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
            $config['num_tag_open']     = '<li class="page-item">';
            $config['num_tag_close']    = '</li>';
            $config['reuse_query_string'] = true;

            $page = ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : 0;
            if (!is_numeric($page)) {
                redirect(base_url('projects/favorite_projects'));
            }
            $offset = ($page - 1) * $config["per_page"];
            $this->pagination->initialize($config);
            $data["links"] = $this->pagination->create_links();
            $data['projects'] = $projects = $this->projects_model->get_favorite_project($this->session->userdata('workspace_id'), $this->session->userdata('user_id'), $filter, $user_type, $config["per_page"], $page);

            $i = 0;
            foreach ($projects as $row) {
                $projects_user_ids = explode(',', $row['user_id']);
                $projects_client_ids = explode(',', $row['client_id']);
                $data['projects'][$i] = $row;
                $data['projects'][$i]['project_progress'] = $this->projects_model->get_favorite_project($this->session->userdata('workspace_id'), $row['id']);
                $data['projects'][$i]['projects_users'] = $this->users_model->get_user_array_responce($projects_user_ids);
                $data['projects'][$i]['projects_clients'] = $this->users_model->get_user_array_responce($projects_client_ids);
                $i++;
            }
            $workspace_id = $this->session->userdata('workspace_id');
            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_favorite_project($workspace_id, $this->session->userdata['user_id']);

                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $data['statuses_project'] =  $this->statuses_model->get_statuses_project($workspace_id);
                $data['statuses'] = $this->statuses_model->get_statuses($workspace_id);
                $this->load->view('favorite_projects', $data);
            } else {
                redirect('home', 'refresh');
            }
        }
    }

    public function add_favorite()
    {
        // $id = $this->input->post('project_id');
        // $data = [
        //     'is_favorite' => 1,
        // ];

        $data = [
            'user_id' => $this->session->userdata['user_id'],
            'workspace_id' => $this->session->userdata('workspace_id'),
            'project_id' => $this->input->post('project_id'),
        ];

        if ($this->projects_model->add_to_favourites($data)) {
            $this->session->set_flashdata('message', 'Added to favorites .');
            $this->session->set_flashdata('message_type', 'success');
            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('message', 'Could not add to favorites .');
            $this->session->set_flashdata('message_type', 'error');
            $response['error'] = true;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }
    public function remove_favorite()
    {
        $data = [
            'user_id' => $this->session->userdata['user_id'],
            'workspace_id' => $this->session->userdata('workspace_id'),
            'project_id' => $this->input->post('project_id'),
        ];

        if ($this->projects_model->remove_from_favourites($data)) {

            $this->session->set_flashdata('message', 'Removed from the favorites.');
            $this->session->set_flashdata('message_type', 'success');

            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('message', 'Could not removed from favorites .');
            $this->session->set_flashdata('message_type', 'error');

            $response['error'] = true;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }

    public function duplicate_data()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $related_data = [];
            $tasks = fetch_details('tasks', ['project_id' => $_POST['id']]);
            $milestones = fetch_details('milestones', ['project_id' => $_POST['id']]);
            $project_media = fetch_details('project_media', ['type_id' => $_POST['id']]);

            if (!empty($tasks)) {
                $related_data['tasks'] = $tasks;
            }

            if (!empty($milestones)) {
                $related_data['milestones'] = $milestones;
            }

            if (!empty($project_media)) {
                $related_data['project_media'] = $project_media;
            }

            // print_r($related_data);
            // return;

            $data = duplicate_row("projects", $_POST['id'], $related_data);
            $response['data'] = $data;
            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }

    public function duplicate_task()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $data = duplicate_row("tasks", $_POST['id']);
            $response['data'] = $data;
            $response['error'] = false;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        }
    }
    public function get_activity_logs_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $project_id = $this->input->post('id');
            return $this->projects_model->get_activity_list($this->session->userdata('user_id'), $workspace_id, $project_id);
        }
    }
    public function get_task_activity_logs_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $task_id = $this->input->get('id');
            $data =  $this->tasks_model->get_activity_list($this->session->userdata('user_id'), $workspace_id, $task_id);
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            $response['data'] = $data;
            $lists =  json_encode($response);
            return $lists;
        }
    }

    public function bulk_project_upload()
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
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;
            $data['all_user'] = $this->users_model->get_user($user_ids);
            $data['client_ids'] = $client_ids = fetch_details('users', ['id' => $this->session->userdata('user_id')]);
            $workspace_id = $this->session->userdata('workspace_id');
            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('bulk_projects', $data);
            } else {
                redirect('projects', 'refresh');
            }
        }
    }

    public function create_bulk_project_update()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!check_permissions("projects", "create", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $this->form_validation->set_rules('bulk_upload', '', 'xss_clean');
            $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
            if (empty($_FILES['upload_file']['name'])) {
                $this->form_validation->set_rules('upload_file', 'File', 'trim|required|xss_clean', array('required' => 'Please choose file'));
            }

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $allowed_mime_type_arr = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv');
                $mime = get_mime_by_extension($_FILES['upload_file']['name']);
                if (!in_array($mime, $allowed_mime_type_arr)) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = 'Invalid file format!';
                    print_r(json_encode($this->response));
                    return false;
                }
                $csv = $_FILES['upload_file']['tmp_name'];
                $temp = 0;
                $temp1 = 0;
                $handle = fopen($csv, "r");
                $this->response['message'] = '';
                $type = $_POST['type'];
                if ($type == 'upload') {
                    while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row values
                    {
                        if ($temp != 0) {
                            if (empty($row[0])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'workspace id is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[3])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'title is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[4])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'description is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[5])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'status is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[12])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'start_date is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[13])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'end_date is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                        }
                        $temp++;
                    }
                    $handle = fopen($csv, "r");
                    while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
                    {
                        if ($temp1 != 0) {
                            $data['workspace_id'] = $row[0];
                            $data['user_id'] = $row[1];
                            $data['client_id'] = $row[2];
                            $data['title'] = $row[3];
                            $data['description'] = $row[4];
                            $data['status'] = $row[5];
                            $data['budget'] = $row[6];
                            $data['class'] = $row[7];
                            $data['priority'] = $row[8];
                            $data['task_count'] = $row[9];
                            $data['comment_count'] = $row[10];
                            $data['is_favorite'] = $row[11];
                            $data['start_date'] = $row[12];
                            $data['end_date'] = $row[13];

                            $this->db->insert('projects', $data);
                        }
                        $temp1++;
                    }

                    $this->response['error'] = false;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = 'projects uploaded successfully!';
                    print_r(json_encode($this->response));
                    return false;
                } else { // bulk_update
                    while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
                    {
                        if ($temp != 0) {
                            if (empty($row[0])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'id is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[1])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'workspace id is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[4])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'title is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[5])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'description is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[6])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'status is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[13])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'start_date is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[14])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'end_date is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                        }
                        $temp++;
                    }
                    $handle = fopen($csv, "r");
                    while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row values
                    {
                        if (
                            $temp1 != 0
                        ) {
                            $project_id = $row[0];
                            $projects = fetch_details('projects', ['id' => $project_id], '*');
                            if (isset($projects[0]) && !empty($projects[0])) {
                                if (!empty($row[1])) {
                                    $data['workspace_id'] = $row[1];
                                } else {
                                    $data['workspace_id'] = $projects[0]['workspace_id'];
                                }
                                if (!empty($row[2])) {
                                    $data['user_id'] = $row[2];
                                } else {
                                    $data['user_id'] = $projects[0]['user_id'];
                                }
                                if (!empty($row[3])) {
                                    $data['client_id'] = $row[3];
                                } else {
                                    $data['client_id'] = $projects[0]['client_id'];
                                }
                                if (!empty($row[4])) {
                                    $data['title'] = $row[4];
                                } else {
                                    $data['title'] = $projects[0]['title'];
                                }
                                if (!empty($row[5])) {
                                    $data['description'] = $row[5];
                                } else {
                                    $data['description'] = $projects[0]['description'];
                                }
                                if (!empty($row[6])) {
                                    $data['status'] = $row[6];
                                } else {
                                    $data['status'] = $projects[0]['status'];
                                }
                                if (!empty($row[7])) {
                                    $data['budget'] = $row[7];
                                } else {
                                    $data['budget'] = $projects[0]['budget'];
                                }
                                if (!empty($row[8])) {
                                    $data['class'] = $row[8];
                                } else {
                                    $data['class'] = $projects[0]['class'];
                                }
                                if (!empty($row[9])) {
                                    $data['priority'] = $row[9];
                                } else {
                                    $data['priority'] = $projects[0]['priority'];
                                }
                                if (!empty($row[10])) {
                                    $data['task_count'] = $row[10];
                                } else {
                                    $data['task_count'] = $projects[0]['task_count'];
                                }
                                if (!empty($row[11])) {
                                    $data['comment_count'] = $row[11];
                                } else {
                                    $data['comment_count'] = $projects[0]['comment_count'];
                                }
                                if (!empty($row[12])) {
                                    $data['is_favorite'] = $row[12];
                                } else {
                                    $data['is_favorite'] = $projects[0]['is_favorite'];
                                }
                                if (!empty($row[13])) {
                                    $data['start_date'] = $row[13];
                                } else {
                                    $data['start_date'] = $projects[0]['start_date'];
                                }
                                if (!empty($row[14])) {
                                    $data['end_date'] = $row[14];
                                } else {
                                    $data['end_date'] = $projects[0]['end_date'];
                                }
                                $this->db->where('id', $row[0])->update('projects', $data);
                            }
                        }
                        $temp1++;
                    }
                    $this->response['error'] = false;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = 'projects updated successfully!';
                    print_r(json_encode($this->response));
                    return false;
                }
            }
        } else {
        }
    }

    public function bulk_project_download()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!check_permissions("projects", "create", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
            $this->load->model('projects_model');
            $projectsData = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
            $csvHeaders = [
                'id', 'workspace_id', 'user_id', 'client_id', 'title', 'description', 'status', 'budget', 'class', 'priority', 'task_count', 'comment_count', 'is_favorite', 'start_date', 'end_date'
            ];
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=download-data.csv');
            $output = fopen('php://output', 'w');
            fputcsv($output, $csvHeaders);
            foreach ($projectsData as $project) {
                $data = [
                    $project['id'], $project['workspace_id'], $project['user_id'], $project['client_id'], $project['title'], $project['description'], $project['status'], $project['budget'], $project['class'], $project['priority'], $project['task_count'], $project['comment_count'], $project['is_favorite'], $project['start_date'], $project['end_date']
                ];
                fputcsv($output, $data);
            }
        }
    }

    public function bulk_task_upload()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', 'This operation not allowed in demo version.');
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
                return false;
                exit();
            }
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
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;
            $data['all_user'] = $this->users_model->get_user($user_ids);
            $workspace_id = $this->session->userdata('workspace_id');
            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('bulk_tasks', $data);
            } else {
                redirect('tasks', 'refresh');
            }
        }
    }

    public function create_bulk_task_update()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!check_permissions("tasks", "create", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $this->form_validation->set_rules('bulk_upload', '', 'xss_clean');
            $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
            if (empty($_FILES['upload_file']['name'])) {
                $this->form_validation->set_rules('upload_file', 'File', 'trim|required|xss_clean', array('required' => 'Please choose file'));
            }

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $allowed_mime_type_arr = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv');
                $mime = get_mime_by_extension($_FILES['upload_file']['name']);
                if (!in_array($mime, $allowed_mime_type_arr)) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = 'Invalid file format!';
                    print_r(json_encode($this->response));
                    return false;
                }
                $csv = $_FILES['upload_file']['tmp_name'];
                $temp = 0;
                $temp1 = 0;
                $handle = fopen($csv, "r");
                $this->response['message'] = '';
                $type = $_POST['type'];
                if ($type == 'upload') {
                    while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row values
                    {
                        if ($temp != 0) {
                            if (empty($row[0])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'workspace id is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[1])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'project id is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[4])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'title is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[5])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'description is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }

                            if (empty($row[7])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'due_date is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[11])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'start_date is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                        }
                        $temp++;
                    }
                    $handle = fopen($csv, "r");
                    while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
                    {
                        if ($temp1 != 0) {
                            $data['workspace_id'] = $row[0];
                            $data['project_id'] = $row[1];
                            $data['user_id'] = $row[2];
                            $data['milestone_id'] = $row[3];
                            $data['title'] = $row[4];
                            $data['description'] = $row[5];
                            $data['priority'] = $row[6];
                            $data['due_date'] = $row[7];
                            $data['status'] = $row[8];
                            $data['class'] = $row[9];
                            $data['comment_count'] = $row[10];
                            $data['start_date'] = $row[11];

                            $this->db->insert('tasks', $data);
                        }
                        $temp1++;
                    }

                    $this->response['error'] = false;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = 'tasks uploaded successfully!';
                    print_r(json_encode($this->response));
                    return false;
                } else { // bulk_update
                    while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
                    {
                        if ($temp != 0) {
                            if (empty($row[0])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'id is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[1])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'workspace id is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[2])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'project id is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[5])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'title is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[6])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'description is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }

                            if (empty($row[8])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'due_date is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                            if (empty($row[12])) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'start_date is empty at row ' . $temp;
                                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                                print_r(json_encode($this->response));
                                return false;
                            }
                        }
                        $temp++;
                    }
                    $handle = fopen($csv, "r");
                    while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row values
                    {
                        if (
                            $temp1 != 0
                        ) {
                            $task_id = $row[0];
                            $tasks = fetch_details('tasks', ['id' => $task_id], '*');
                            if (isset($tasks[0]) && !empty($tasks[0])) {
                                if (!empty($row[1])) {
                                    $data['workspace_id'] = $row[1];
                                } else {
                                    $data['workspace_id'] = $tasks[0]['workspace_id'];
                                }
                                if (!empty($row[2])) {
                                    $data['project_id'] = $row[2];
                                } else {
                                    $data['project_id'] = $tasks[0]['project_id'];
                                }
                                if (!empty($row[3])) {
                                    $data['user_id'] = $row[3];
                                } else {
                                    $data['user_id'] = $tasks[0]['user_id'];
                                }
                                if (!empty($row[4])) {
                                    $data['milestone_id'] = $row[4];
                                } else {
                                    $data['milestone_id'] = $tasks[0]['milestone_id'];
                                }
                                if (!empty($row[5])) {
                                    $data['title'] = $row[5];
                                } else {
                                    $data['title'] = $tasks[0]['title'];
                                }
                                if (!empty($row[6])) {
                                    $data['description'] = $row[6];
                                } else {
                                    $data['description'] = $tasks[0]['description'];
                                }
                                if (!empty($row[7])) {
                                    $data['priority'] = $row[7];
                                } else {
                                    $data['priority'] = $tasks[0]['priority'];
                                }
                                if (!empty($row[8])) {
                                    $data['due_date'] = $row[8];
                                } else {
                                    $data['due_date'] = $tasks[0]['due_date'];
                                }
                                if (!empty($row[9])) {
                                    $data['status'] = $row[9];
                                } else {
                                    $data['status'] = $tasks[0]['status'];
                                }
                                if (!empty($row[10])) {
                                    $data['class'] = $row[10];
                                } else {
                                    $data['class'] = $tasks[0]['class'];
                                }
                                if (!empty($row[11])) {
                                    $data['comment_count'] = $row[11];
                                } else {
                                    $data['comment_count'] = $tasks[0]['comment_count'];
                                }
                                if (!empty($row[12])) {
                                    $data['start_date'] = $row[12];
                                } else {
                                    $data['start_date'] = $tasks[0]['start_date'];
                                }
                                $this->db->where('id', $row[0])->update('tasks', $data);
                            }
                        }
                        $temp1++;
                    }
                    $this->response['error'] = false;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = 'tasks updated successfully!';
                    print_r(json_encode($this->response));
                    return false;
                }
            }
        } else {
        }
    }

    public function bulk_task_download()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!check_permissions("tasks", "create", "", true)) {
                return response(PERMISSION_ERROR_MESSAGE);
            }
            $this->load->model('tasks_model');
            $tasksData = $this->tasks_model->get_tasks($this->session->userdata('workspace_id'));
            $csvHeaders = [
                'id', 'workspace_id', 'project_id', 'user_id', 'milestone_id', 'title', 'description', 'priority', 'due_date', 'status', 'class', 'comment_count', 'start_date'
            ];
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=download-data.csv');
            $output = fopen('php://output', 'w');
            fputcsv($output, $csvHeaders);
            foreach ($tasksData as $task) {
                $data = [
                    $task['id'], $task['workspace_id'], $task['project_id'], $task['user_id'], $task['milestone_id'], $task['title'], $task['description'], $task['priority'], $task['due_date'], $task['status'], $task['class'], $task['comment_count'], $task['start_date']
                ];
                fputcsv($output, $data);
            }
        }
    }
}
