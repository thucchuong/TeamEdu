<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tasks extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(['users_model', 'workspace_model', 'projects_model', 'statuses_model','milestones_model', 'notifications_model', 'tasks_model', 'activity_model']);
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);
		$this->load->library('session');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
	}

	public function get_tasks_list()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			return $this->tasks_model->get_tasks_list();
		}
	}

	public function index()
	{
		if (!check_permissions("tasks", "read", "", true)) {
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

			$current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
			$user_ids = explode(',', $current_workspace_id[0]->user_id);
			$data['all_user'] = $this->users_model->get_user($user_ids);

			if (!empty($this->session->has_userdata('workspace_id'))) {

				$data['total_user'] = $this->custom_funcation_model->get_count('id', 'users', 'FIND_IN_SET(' . $this->session->userdata('workspace_id') . ', workspace_id)');

				$data['total_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id'));

				$data['total_project'] = $total_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id'));

				$data['notes'] = $notes = $this->custom_funcation_model->get_count('id', 'notes', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and user_id=' . $this->session->userdata('user_id') . '');
			} else {
				$data['total_user'] = 0;

				$data['total_task'] = 0;

				$data['total_project'] = $total_project = 0;

				$data['notes'] = $notes = 0;
			}

			$user_names = $this->users_model->get_user_names();
            $data['user_names'] = $user_names;
			$workspace_id = $this->session->userdata('workspace_id');
			$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
            $data['projects'] = $projects;
			$data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
			$data['is_admin'] =  $this->ion_auth->is_admin();
			$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
			$data['projects'] = $projects;
			$data['statuses'] =  $this->statuses_model->get_statuses_task($workspace_id);
			$this->load->view('tasks-list', $data);
		}
	}

	public function create()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		$this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('budget', str_replace(':', '', 'budget is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('start_date', str_replace(':', '', 'start_date is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('end_date', str_replace(':', '', 'end_date is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {

			$user_ids = implode(",", $this->input->post('users'));
			$data = array(
				'title' => strip_tags($this->input->post('title', true)),
				'status' => $this->input->post('status'),
				'budget' => $this->input->post('budget'),
				'description' => strip_tags($this->input->post('description', true)),
				'user_id' => $user_ids,
				'workspace_id' => $this->session->userdata('workspace_id'),
				'start_date' => strip_tags($this->input->post('start_date', true)),
				'end_date' => strip_tags($this->input->post('end_date', true))
			);
			$project_id = $this->projects_model->create_project($data);

			if ($project_id != false) {
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

		$this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('budget', str_replace(':', '', 'budget is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('start_date', str_replace(':', '', 'start_date is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('end_date', str_replace(':', '', 'end_date is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {
			$user_ids = implode(",", $this->input->post('users'));
			$data = array(
				'title' => strip_tags($this->input->post('title', true)),
				'status' => $this->input->post('status'),
				'budget' => $this->input->post('budget'),
				'description' => strip_tags($this->input->post('description', true)),
				'user_id' => $user_ids,
				'workspace_id' => $this->session->userdata('workspace_id'),
				'start_date' => strip_tags($this->input->post('start_date', true)),
				'end_date' => strip_tags($this->input->post('end_date', true)),
				'updated_at' => strip_tags($this->input->post('updated_at', true))
			);

			if ($this->projects_model->edit_project($data, $this->input->post('update_id'))) {
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
			echo json_encode($data[0]);
		}
	}

	public function delete()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		$project_id = $this->uri->segment(3);
		if (!empty($project_id) && is_numeric($project_id)  || $project_id < 1) {
			if ($this->projects_model->delete_project($project_id)) {
				$this->session->set_flashdata('message', 'Project deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Project could not be deleted! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
		}
		redirect('projects', 'refresh');
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
			$project_id = $this->uri->segment(3);
			if (empty($project_id) || !is_numeric($project_id) || $project_id < 1) {
				redirect('projects', 'refresh');
				return false;
				exit(0);
			}
			$projects = $this->projects_model->get_project_by_id($project_id);
			$data['projects'] = $projects[0];

			$milestones = $this->milestones_model->get_milestone_by_project_id($project_id, $this->session->userdata('workspace_id'));
			$data['milestones'] = $milestones;
			$workspace_id = $this->session->userdata('workspace_id');
			$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
            $data['projects'] = $projects;
			$data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
			$this->load->view('project-details', $data);
		}
	}

	public function create_milestone()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		$this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('cost', str_replace(':', '', 'cost is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'title' => strip_tags($this->input->post('title', true)),
				'status' => $this->input->post('status'),
				'cost' => $this->input->post('cost'),
				'description' => strip_tags($this->input->post('description', true)),
				'workspace_id' => $this->session->userdata('workspace_id'),
				'project_id' => $this->uri->segment(3)
			);
			$milestone_id = $this->milestones_model->create_milestone($data);

			if ($milestone_id != false) {
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
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		$this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('status', str_replace(':', '', 'status is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('cost', str_replace(':', '', 'cost is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'title' => strip_tags($this->input->post('title', true)),
				'status' => $this->input->post('status'),
				'cost' => $this->input->post('cost'),
				'description' => strip_tags($this->input->post('description', true))
			);

			if ($this->milestones_model->edit_milestone($data, $this->input->post('update_id'))) {
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
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		$milestone_id = $this->uri->segment(3);
		$project_id = $this->uri->segment(4);
		if (!empty($milestone_id) && is_numeric($milestone_id) && !empty($project_id) && is_numeric($project_id)) {
			if ($this->milestones_model->delete_milestone($milestone_id)) {
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
			echo json_encode($data[0]);
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
				'project_id' => strip_tags($this->input->post('project_id', true)),
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
				'project_id' => strip_tags($this->input->post('project_id', true)),
				'project_title' => get_project_title($data['project_id']),
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
					$project = $this->projects_model->get_project_by_id($data['project_id']);
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
						'type_id' => $data['project_id'],
						'notification' => $notification,
					);
					if (!empty($user_ids)) {
						$user_ids = explode(",", $user_ids);
						$this->tasks_model->send_email($user_ids, $task_id, $admin_id,$subject);
						$this->notifications_model->store_notification($notification_data);
					}
				}
				$this->projects_model->project_task_count_update($data['project_id']);
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
	public function get_milestone_and_user_data()
	{

		if ($this->input->post('project_id') && $this->input->post('project_id') != '') {

			$user_ids = fetch_details('projects',['id'=>$this->input->post('project_id')],'user_id');
			$user_ids = explode(',', isset($user_ids) ? $user_ids[0]['user_id'] : '');
			
			$response['error'] = false;
			$response['milestone'] =  $this->milestones_model->get_milestone_by_project_id($this->input->post('project_id'), $this->session->userdata('workspace_id'));
			$response['all_user'] = $this->users_model->get_user($user_ids);
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			echo json_encode($response);
		}
	}
}
