<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(['workspace_model', 'todo_model', 'tasks_model', 'statuses_model', 'projects_model', 'users_model', 'notifications_model', 'leads_model', 'meetings_model']);
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);
		$this->load->library('session');
	}


	public function get_tasks_list($id = '')
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$workspace_id = $this->session->userdata('workspace_id');
			$user_id = !empty($id && is_numeric($id)) ? $id : (!empty($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : $this->session->userdata('user_id'));
			$role = $this->users_model->get_role_by_user_id($user_id);

			return $this->tasks_model->get_tasks_list($workspace_id, $this->db->escape($user_id), $role);
		}
	}

	public function get_todo_list($id = '')
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$workspace_id = $this->session->userdata('workspace_id');
			$user_id = !empty($id && is_numeric($id)) ? $id : (!empty($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : $this->session->userdata('user_id'));
			return $this->todo_model->get_todo_list($workspace_id, $user_id);
		}
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

			$product_ids = isset($user->workspace_id) ? explode(',', $user->workspace_id) : [];

			$data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
			if (!empty($workspace)) {
				if (!$this->session->has_userdata('workspace_id')) {
					$this->session->set_userdata('workspace_id', $workspace[0]->id);
				}
			}
			$user_id = $this->session->userdata('user_id');

			if (!empty($this->session->has_userdata('workspace_id'))) {

				$workspace_id = $this->session->userdata('workspace_id');
				$current_workspace_id =  isset($workspace_id) ? $this->workspace_model->get_workspace($this->session->userdata('workspace_id')) : '';

				$data['total_user'] = isset($current_workspace_id[0]) && !empty($current_workspace_id[0]->user_id) ? $this->custom_funcation_model->get_count('id', 'users_groups', 'user_id IN (' . ltrim($current_workspace_id[0]->user_id, ',') . ') AND group_id!=3') : 0;

				$data['total_client'] = isset($current_workspace_id[0]) && !empty($current_workspace_id[0]->user_id) ? $this->custom_funcation_model->get_count('id', 'users_groups', 'user_id IN (' . ltrim($current_workspace_id[0]->user_id, ',') . ') AND group_id=3') : 0;
				$current_workspace_id = isset($workspace_id) ? $this->workspace_model->get_workspace($this->session->userdata('workspace_id')) : [];
				$user_ids = isset($current_workspace_id[0]) ? explode(',', $current_workspace_id[0]->user_id) : [];
				$section = array_map('trim', $user_ids);
				$user_ids = $section;
				$user_type = is_client() ? 'client' : 'normal';

				$projects_counts = $this->projects_model->get_projects_count($this->session->userdata('workspace_id'), $this->session->userdata('user_id'), '', $user_type);
				$data['total_projects'] = $projects_counts[0]['total'] ? $projects_counts[0]['total'] : 0;

				$tasks_counts = $this->tasks_model->get_tasks_count($this->session->userdata('workspace_id'), $this->session->userdata('user_id'));
				$data['total_tasks'] = $tasks_counts[0]['total'] ? $tasks_counts[0]['total'] : 0;
				if (is_client()) {
					$data['total_notes'] = $this->custom_funcation_model->get_count('id', 'notes', 'user_id = ' . $user->id . ' and workspace_id=' . $this->session->userdata('workspace_id') . ' ');
				}
			} else {
				$data['total_user'] = 0;
				$data['total_client'] = 0;
				$data['total_tasks'] = 0;
				$data['total_projects'] = 0;
				$data['notes'] = 0;
			}
			$data['tasks'] =  $this->tasks_model->tasks_list($workspace_id, $user_id, $this->session->userdata('role'));
			$data['projects'] =  $this->projects_model->projects_list($workspace_id, $user_id, $this->session->userdata('role'));
			$data['todos'] =  $this->todo_model->todos_list($workspace_id, $user_id);

			$today = date('d');
			$this_month = date('m');
			$birthdays = $this->db->select('u.*,CURDATE() AS today, DATE_SUB(CURDATE(), INTERVAL 1 DAY) AS yesterday, DATE_ADD(CURDATE(), INTERVAL 1 DAY) AS tomorrow, DATE_ADD(CURDATE(), INTERVAL 2 DAY) AS after_tomorrow')
				->from('users u')
				->where("MONTH(u.date_of_birth) = '$this_month'")
				->where("DAY(u.date_of_birth) >= '$today'")
				->where('u.workspace_id', $workspace_id)
				->order_by('u.date_of_birth', 'asc')
				->get();
			$date_of_birth = $birthdays->result();
			$work_anniversary = $this->db->select('u.*,CURDATE() AS today, DATE_SUB(CURDATE(), INTERVAL 1 DAY) AS yesterday, DATE_ADD(CURDATE(), INTERVAL 1 DAY) AS tomorrow, DATE_ADD(CURDATE(), INTERVAL 2 DAY) AS after_tomorrow')
				->from('users u')
				->where("MONTH(u.date_of_joining) = '$this_month'")
				->where("DAY(u.date_of_joining) >= '$today'")
				->where('u.workspace_id', $workspace_id)
				->order_by('u.date_of_joining', 'asc')
				->get();
			$date_of_joining = $work_anniversary->result();

			$currentDate = date('Y-m-d');
			$leave_today_query = $this->db->select('`l`.*, `u`.`first_name`, `u`.`last_name`,u.designation,u.profile')
				->from('leaves l')
				->join('users u', 'u.id = l.user_id', 'left')
				->where('l.workspace_id', $workspace_id)
				->where('l.leave_from <=', $currentDate)
				->where('l.leave_to >=', $currentDate)
				->where('l.status', 1)
				->order_by('l.id', 'desc')
				->limit(10, 0)
				->get();

			$tomorrow = date('Y-m-d', strtotime('+1 day'));
			$leave_tomorrow_query = $this->db->select('`l`.*, `u`.`first_name`, `u`.`last_name`,u.designation,u.profile')
				->from('leaves l')
				->join('users u', 'u.id = l.user_id', 'left')
				->where('l.workspace_id', $workspace_id)
				->where('l.leave_from <=', $tomorrow)
				->where('l.leave_to >=', $tomorrow)
				->where('l.status', 1)
				->order_by('l.id', 'desc')
				->limit(10, 0)
				->get();

			$after_tomorrow = date('Y-m-d', strtotime('+2 day'));
			$leave_after_tomorrow_query = $this->db->select('`l`.*, `u`.`first_name`, `u`.`last_name`,u.designation,u.profile')
				->from('leaves l')
				->join('users u', 'u.id = l.user_id', 'left')
				->where('l.workspace_id', $workspace_id)
				->where('l.leave_from <=', $after_tomorrow)
				->where('l.leave_to >=', $after_tomorrow)
				->where('l.status', 1)
				->order_by('l.id', 'desc')
				->limit(10, 0)
				->get();

			$data['birthdays'] = $date_of_birth;
			$data['work_anniversaries'] = $date_of_joining;
			$data['leave_today'] = $leave_today_query;
			$data['leave_tomorrow'] = $leave_tomorrow_query;
			$data['leave_after_tomorrow'] = $leave_after_tomorrow_query;
			$workspace_id = $this->session->userdata('workspace_id');
			$data['statuses'] =  $this->statuses_model->get_statuses($workspace_id);
			$data['statuses_task'] =  $this->statuses_model->get_statuses_task($workspace_id);
			$data['statuses_project'] =  $this->statuses_model->get_statuses_project($workspace_id);
			$data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);

			$data['is_admin'] =  $this->ion_auth->is_admin();
			$this->load->view('home', $data);
		}
	}

	public function filter_by_project_status()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$workspace_id = $this->session->userdata('workspace_id');
			$user_id = $this->session->userdata('user_id');
			$role = $this->session->userdata('role');
			$statuses = $this->statuses_model->get_statuses_project($workspace_id);
			$data = [];
			$statusTypes = [];
			foreach ($statuses as $status) {
				$statusTypes[] = $status['status'];
				$this->db->select('COUNT(id) as total, status');
				$this->db->from('projects');
				$this->db->where('status', $status['status']);
				if ($role == "members") {
					$this->db->where("FIND_IN_SET($user_id, `user_id`)");
				}
				if ($role == "clients") {
					$this->db->where("FIND_IN_SET($user_id, `client_id`)");
				}
				$this->db->where('workspace_id', $workspace_id);
				$this->db->order_by('id');
				$query = $this->db->get();
				$projectCount = $query->result_array();

				$data[] = $projectCount ? $projectCount[0]['total'] : 0;
			}
			$response = [
				'labels' => $statusTypes,
				'data' => $data
			];
			echo json_encode($response);
		}
	}

	public function filter_by_task_status()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$workspace_id = $this->session->userdata('workspace_id');
			$user_id = $this->session->userdata('user_id');
			$role = $this->session->userdata('role');
			$statuses = $this->statuses_model->get_statuses_task($workspace_id);
			$data = [];
			$statusTypes = [];

			foreach ($statuses as $status) {
				$statusTypes[] = $status['status'];
				$this->db->select('COUNT(id) as total,status');
				$this->db->from('tasks');
				$this->db->where('status', $status['status']);
				if ($role == "members") {
					$this->db->where("FIND_IN_SET($user_id, `user_id`)");
				}
				if ($role == "clients") {
					$this->db->where("FIND_IN_SET($user_id, `user_id`)");
				}
				$this->db->where('workspace_id', $workspace_id);
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

	public function calendar()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$user_id = $this->session->userdata('user_id');
			$workspace_id = $this->session->userdata('workspace_id');
			if (!check_permissions("projects", "read", "", true)) {
				redirect('/home', 'refresh');
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
			} else {
				$this->session->set_flashdata('message', NO_WORKSPACE);
				$this->session->set_flashdata('message_type', 'error');
				redirect('/home', 'refresh');
				return false;
				exit();
			}
			$current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
			$user_ids = explode(',', $current_workspace_id[0]->user_id);
			$section = array_map('trim', $user_ids);
			$user_ids = $section;
			$data['all_user'] = $this->users_model->get_user($user_ids);
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
				$this->load->view('projects-calendar', $data);

				$task = $this->tasks_model->fetch_tasks($workspace_id, $user_id, $user_type);
				$data['tasks'] = $task;
				$this->load->view('projects-calendar', $data);

				$meetings = $this->meetings_model->fetch_meeting($workspace_id, $user_id, $user_type);
				$data['meetings'] = $meetings;
				$this->load->view('projects-calendar', $data);

				$leads = $this->leads_model->fetch_leads($workspace_id, $user_id, $user_type);
				$data['leads'] = $leads;
				$this->load->view('projects-calendar', $data);
			} else {
				redirect('/home', 'refresh');
			}
		}
	}
}
