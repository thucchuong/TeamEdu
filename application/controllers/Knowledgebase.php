<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Knowledgebase extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(['workspace_model', 'invoices_model', 'notifications_model', 'users_model', 'items_model', 'tax_model', 'units_model', 'projects_model', 'payments_model', 'knowledgebase_model']);
		$this->load->library(['ion_auth', 'form_validation', 'Pdf']);
		$this->load->helper(['url', 'language', 'form']);
		$this->load->library('session');

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}
	public function index()
	{
		if (!check_permissions("knowledgebase", "read", "", true)) {
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
			$admin_ids = explode(',', $current_workspace_id[0]->admin_id);
			$section = array_map('trim', $admin_ids);
			$data['not_in_workspace_user'] = $this->users_model->get_user_not_in_workspace($user_ids);
			$data['admin_ids'] = $admin_ids = $section;
			$data['groups'] = $this->knowledgebase_model->get_groups();

			$workspace_id = $this->session->userdata('workspace_id');

			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('knowledgebase', $data);
			} else {
				redirect('home', 'refresh');
				return false;
				exit();
			}
		}
	}

	public function create_article_group()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
			$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
			$this->session->set_flashdata('message_type', 'error');
			redirect('knowledgebase', 'refresh');
			return false;
			exit();
		}
		if (!check_permissions("knowledgebase", "create", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		$this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', str_replace(':', '', 'Description is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {

			$data = array(
				'title' => strip_tags($this->input->post('title', true)),
				'description' => strip_tags($this->input->post('description', true)),
			);

			$add_group = $this->knowledgebase_model->add_group($data);

			if ($add_group != false) {
				$response['error'] = false;
				$response['item_id'] = $item_id;
				$response['message'] = 'Item Added Successfully.';
				$this->session->set_flashdata('message', 'Article Group added successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$response['error'] = true;
				$response['message'] = 'Item could not added! Try again!';
				$this->session->set_flashdata('message', 'Item could not added! Try again!');
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

	public function article_group_delete()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
			$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
			$this->session->set_flashdata('message_type', 'error');
			redirect('knowledgebase', 'refresh');
			return false;
			exit();
		}
		if (!check_permissions("knowledgebase", "delete", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		$article_grp_id = $this->uri->segment(3);

		if (!empty($article_grp_id) && is_numeric($article_grp_id)  || $article_grp_id < 1) {
			if ($this->knowledgebase_model->delete_article_group($article_grp_id)) {
				$this->session->set_flashdata('message', 'Article Group deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Article Group could not be deleted! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
		}
		redirect('knowledgebase/article_groups', 'refresh');
	}

	public function article_groups()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (!check_permissions("knowledgebase", "read", "", true)) {
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
				$this->load->view('article_groups', $data);
			} else {
				redirect('home', 'refresh');
			}
		}
	}

	public function add_article()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
				$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
				$this->session->set_flashdata('message_type', 'error');
				redirect('knowledgebase', 'refresh');
				return false;
				exit();
			}
			if (!check_permissions("knowledgebase", "create", "", true)) {
				return response(PERMISSION_ERROR_MESSAGE);
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

			$current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
			$user_ids = explode(',', $current_workspace_id[0]->user_id);
			$section = array_map('trim', $user_ids);
			$user_ids = $section;
			$data['currency'] = get_currency_symbol();
			$projects = $this->projects_model->get_projects($workspace[0]->id);
			$data['projects'] = $projects;
			$data['all_user'] = $this->users_model->get_user($user_ids, ['3']);
			$data['groups'] = $this->knowledgebase_model->get_groups();

			$workspace_id = $this->session->userdata('workspace_id');
			if (!empty($workspace_id)) {
				$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
				$data['projects'] = $projects;
				$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
				$this->load->view('add-article', $data);
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
			redirect('knowledgebase', 'refresh');
			return false;
			exit();
		}
		if (!check_permissions("knowledgebase", "create", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}

		$this->form_validation->set_rules('title', str_replace(':', '', 'title is required'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {

			$data = array(
				'user_id' => $this->session->userdata('user_id'),
				'workspace_id' => $this->session->userdata('workspace_id'),
				'title' => strip_tags($this->input->post('title'), true),
				'description' =>  output_escaping($this->input->post('description', true)),
				'group_id' => strip_tags($this->input->post('group_id')),
			);

			$data['slug'] = slugify($data['title']);
			$data['description'] = json_encode($data['description']);

			$create_article = $this->knowledgebase_model->create_article($data);

			if ($create_article != false) {
				$response["error"]   = true;
				$this->session->set_flashdata('message', 'Article Added successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Couldnt Add Article! Try again!');
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

	public function get_article_list()
	{
		if (!check_permissions("knowledgebase", "read", "", true)) {
			return redirect(base_url(), 'refresh');
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (!check_permissions("knowledgebase", "read", "", true)) {
				return redirect(base_url(), 'refresh');
			}

			$workspace_id = $this->session->userdata('workspace_id');
			$user_id = $this->session->userdata('user_id');
			if (!is_admin() && !is_member() && !is_client() && !is_workspace_admin($user_id, $workspace_id)) {
				$this->session->set_flashdata('message_type', 'error');
				redirect('home', 'refresh');
				return false;
				exit();
			}

			$data = $this->knowledgebase_model->get_article_list('list', $workspace_id, $user_id);

			$response['error'] = false;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			$response['data'] = $data;
			$lists =  json_encode($response);
			return $lists;
		}
	}

	public function get_group_by_id($group_id = '')
	{
		if ($this->ion_auth->logged_in() && !empty($group_id) && is_numeric($group_id)) {

			$data = $this->knowledgebase_model->get_group_by_id($group_id);

			if (!empty($data)) {
				$data[0]['csrfName'] = $this->security->get_csrf_token_name();
				$data[0]['csrfHash'] = $this->security->get_csrf_hash();
				echo json_encode($data[0]);
			} else {
				$data[0]['csrfName'] = $this->security->get_csrf_token_name();
				$data[0]['csrfHash'] = $this->security->get_csrf_hash();
				echo json_encode($data[0]);
			}
		} else {
			$data[0]['csrfName'] = $this->security->get_csrf_token_name();
			$data[0]['csrfHash'] = $this->security->get_csrf_hash();
			echo json_encode($data[0]);
		}
	}

	public function get_article_by_id()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {

			$article_id = $this->uri->segment(3);

			if (empty($article_id) || !is_numeric($article_id)) {
				redirect('knowledgebase', 'refresh');
				return false;
				exit(0);
			}
			$data = $this->knowledgebase_model->get_article_by_id($article_id);
			$data[0]['date_published'] = date('Y-m-d\TH:i', strtotime($data[0]['date_published']));
			$data[0]['csrfName'] = $this->security->get_csrf_token_name();
			$data[0]['csrfHash'] = $this->security->get_csrf_hash();

			echo json_encode($data[0]);
		}
	}

	public function get_groups_list()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (!check_permissions("knowledgebase", "read", "", true)) {
				return redirect(base_url(), 'refresh');
			}

			$workspace_id = $this->session->userdata('workspace_id');
			return $this->knowledgebase_model->get_groups_list('list', $workspace_id, $user_id);
		}
	}

	public function edit_article_group()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
			$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
			$this->session->set_flashdata('message_type', 'error');
			redirect('knowledgebase', 'refresh');
			return false;
			exit();
		}
		if (!check_permissions("knowledgebase", "update", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}

		$this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', str_replace(':', '', 'Description is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'title' => strip_tags($this->input->post('title', true)),
				'description' => strip_tags($this->input->post('description', true)),
			);

			if ($this->knowledgebase_model->edit_article_group($data, $this->input->post('update_id'))) {
				$this->session->set_flashdata('message', 'Article Group Updated successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Article Group could not Updated! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}

			$response['error'] = false;

			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Item Updated successfully.';
			echo json_encode($response);
		} else {
			$response['error'] = true;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = validation_errors();
			echo json_encode($response);
		}
	}

	public function delete()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
			$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
			$this->session->set_flashdata('message_type', 'error');
			redirect('knowledgebase', 'refresh');
			return false;
			exit();
		}
		if (!check_permissions("knowledgebase", "delete", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}

		$article_id = $this->uri->segment(3);

		if (!empty($article_id) && is_numeric($article_id)  || $article_id < 1) {
			if ($this->knowledgebase_model->delete_article($article_id)) {
				$this->session->set_flashdata('message', 'Article deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Article could not be deleted! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
		}
		redirect('knowledgebase', 'refresh');
	}

	public function view_article()
	{
		$slug_id = $this->uri->segment(3);

		$data['article'] = $this->knowledgebase_model->get_article_by_slug($slug_id);

		$grp_id = $data['article']['0']['group_id'];
		$slug_id = $data['article']['0']['slug'];

		$data['group_vise_data'] = $this->knowledgebase_model->get_article_by_grp_id($grp_id, $slug_id);

		$this->load->view('view-article', $data);
	}

	public function edit_article()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
				$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
				$this->session->set_flashdata('message_type', 'error');
				redirect('knowledgebase', 'refresh');
				return false;
				exit();
			}
			if (!check_permissions("knowledgebase", "update", "", true)) {
				return response(PERMISSION_ERROR_MESSAGE);
			}

			$article_id = $this->uri->segment(3);

			if (empty($article_id) || !is_numeric($article_id) || $article_id < 1) {
				redirect('knowledgebase', 'refresh');
				return false;
				exit(0);
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
			$current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
			$user_ids = explode(',', $current_workspace_id[0]->user_id);
			$section = array_map('trim', $user_ids);
			$user_ids = $section;
			$article = $this->knowledgebase_model->get_article_by_id($article_id);
			$data['article_id'] = $article_id;
			$data['article'] = $article;
			$data['groups'] = $this->knowledgebase_model->get_groups();
			$projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
			$data['projects'] = $projects;
			$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
			$workspace_id = $this->session->userdata('workspace_id');
			$data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
			$this->load->view('edit-article', $data);
		}
	}

	public function edit()
	{
		if (!check_permissions("knowledgebase", "update", "", true)) {
			return response(PERMISSION_ERROR_MESSAGE);
		}
		if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
			$this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
			$this->session->set_flashdata('message_type', 'error');
			redirect('knowledgebase', 'refresh');
			return false;
			exit();
		}
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		$this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', str_replace(':', '', 'Description is empty.'), 'trim|required|xss_clean');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'user_id' => $this->session->userdata('user_id'),
				'title' => strip_tags($this->input->post('title', true)),
				'group_id' => strip_tags($this->input->post('group_id'), true),
				'description' =>  output_escaping($this->input->post('description')),

			);
			if ($this->knowledgebase_model->edit_article($data, $this->input->post('id'))) {
				$this->session->set_flashdata('message', 'Knowledgebase Updated successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'Knowledgebase could not Updated! Try again!');
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

	public function search()
	{
		$offset = 0;
		$limit = 10;
		$sort = 'id';
		$order = 'ASC';
		$where = '';
		$get = $this->input->get();
		if (isset($get['sort'])) {
			$sort = strip_tags($get['sort']);
		}
		if (isset($get['offset'])) {
			$offset = strip_tags($get['offset']);
		}
		if (isset($get['limit'])) {
			$limit = strip_tags($get['limit']);
		}
		if (isset($get['order'])) {
			$order = strip_tags($get['order']);
		}
		if (isset($get['search']) && !empty($get['search'])) {
			$search = strip_tags($get['search']);
			$where = "(title like '%" . $search . "%' OR description like '%" . $search . "%')";
		}

		$search = (isset($get['search']) && !empty($get['search'])) ? $get['search'] : '';
		$articles = $data['articles'] = fetch_details("articles", $where, "*", $limit, $offset, $sort, $order);
		$response['error'] = (!empty($articles)) ? false : true;
		$response['message'] = (!empty($articles)) ? "articles fetched successfully" : "No articles found";
		$response['articles'] = (!empty($articles)) ? $articles : [];
		print_r(json_encode($response));
	}
	public function duplicate_data()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		} else {
			$data = duplicate_row("articles", $_POST['id']);
			$response['data'] = $data;
			$response['error'] = false;

			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			echo json_encode($response);
		}
	}
}
