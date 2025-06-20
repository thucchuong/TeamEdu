<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['projects_model', 'users_model', 'workspace_model', 'statuses_model', 'notifications_model', 'leads_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }
    public function index()
    {
    }
    public function project_report()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            if (!check_permissions("projects", "read", "", true)) {
                redirect('/home', 'refresh');
            }
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', (string)$user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);

            $filter = (isset($_GET['filter']) && !empty($_GET['filter'])) ? $_GET['filter'] : '';
            $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : '';
            $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : '';
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
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
            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $this->load->view('project_report', $data);
            } else {
                redirect('/home', 'refresh');
            }
        }
    }

    public function filter_by_project_year_status()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $year = $this->input->get('year');
            $workspace_id = $this->session->userdata('workspace_id');

            $statuses = $this->statuses_model->get_statuses_project($workspace_id);
            $data = [];
            $statusTypes = [];

            foreach ($statuses as $status) {
                $statusTypes[] = $status['status'];
                $this->db->select('COUNT(id) as total, YEAR(date_created) AS year, status');
                $this->db->from('projects');

                if ($year == '') {
                    $this->db->where("YEAR(date_created) =", date('Y'), FALSE);
                } else {
                    $this->db->where('YEAR(date_created) =', $year);
                }
                $this->db->where('status', $status['status']);
                $this->db->where('workspace_id', $workspace_id);
                $this->db->group_by('year');
                $this->db->order_by('year');
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

    public function filter_by_project_year_priority()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $year = $this->input->get('year');
            $workspace_id = $this->session->userdata('workspace_id');

            $prioritys = ['low', 'medium', 'high'];
            $data = [];

            foreach ($prioritys as $priority) {
                $this->db->select('COUNT(id) as total, YEAR(date_created) AS year');
                $this->db->from('projects');

                if ($year == '') {
                    $this->db->where("YEAR(date_created) =", date('Y'), FALSE);
                } else {
                    $this->db->where('YEAR(date_created) =', $year);
                }

                $this->db->where('priority', $priority);
                $this->db->where('workspace_id', $workspace_id);
                $this->db->group_by('year');
                $this->db->order_by('year');

                $query = $this->db->get();
                $projectCount = $query->result_array();

                $data[] = $projectCount ? $projectCount[0]['total'] : 0;
            }

            $response = [
                'labels' => $prioritys,
                'data' => $data
            ];

            echo json_encode($response);
        }
    }
    public function filter_by_month()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $month = $this->input->get('month');
            $year = $this->input->get('year');
            $user_id = $this->input->get('user_id');
            $client_id = $this->input->get('client_id');
            $workspace_id = $this->session->userdata('workspace_id');

            $this->db->select('CONCAT(YEAR(start_date), "-", LPAD(MONTH(start_date), 2, "0")) AS month_year,MONTHNAME(start_date) as month_name, COUNT(*) AS month_count,client_id,user_id');
            $this->db->from('projects');

            if ($month == '') {
                $this->db->where("YEAR(start_date) =", date('Y'), FALSE);
            } else {
                $this->db->where('CONCAT(YEAR(start_date), "-", LPAD(MONTH(start_date), 2, "0")) =', $month);
            }

            if ($client_id) {
                $this->db->where("FIND_IN_SET($client_id, client_id)");
            }
            if ($user_id) {
                $this->db->where("FIND_IN_SET($user_id, user_id)");
            }
            $this->db->where("workspace_id", $workspace_id);
            $this->db->group_by('month_year');
            $this->db->order_by('month_year');

            $query = $this->db->get();
            $projects = $query->result_array();

            $labels = [];
            $data = [];

            foreach ($projects as $project) {
                $labels[] = $project['month_name'];
                $data[] = $project['month_count'];
            }

            $response = [
                'labels' => $labels,
                'data' => $data
            ];

            echo json_encode($response);
        }
    }

    public function filter_by_end_month()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $month = $this->input->get('month');
            $year = $this->input->get('year');
            $user_id = $this->input->get('user_id');
            $client_id = $this->input->get('client_id');
            $workspace_id = $this->session->userdata('workspace_id');

            $this->db->select('CONCAT(YEAR(end_date), "-", LPAD(MONTH(end_date), 2, "0")) AS month_year,MONTHNAME(end_date) as month_name, COUNT(*) AS month_count,client_id,user_id');
            $this->db->from('projects');

            if ($year == '') {
                $this->db->where("YEAR(start_date) =", date('Y'), FALSE);
            } else {
                $this->db->where('YEAR(start_date) =', $year);
            }

            if ($month == '') {
                $this->db->where("YEAR(end_date) =", date('Y'), FALSE);
            } else {
                $this->db->where('CONCAT(YEAR(end_date), "-", LPAD(MONTH(end_date), 2, "0")) =', $month);
            }

            if ($client_id) {
                $this->db->where("FIND_IN_SET($client_id, client_id)");
            }
            if ($user_id) {
                $this->db->where("FIND_IN_SET($user_id, user_id)");
            }
            $this->db->where("workspace_id", $workspace_id);
            $this->db->group_by('month_year');
            $this->db->order_by('month_year');

            $query = $this->db->get();
            $projects = $query->result_array();

            $labels = [];
            $data = [];

            foreach ($projects as $project) {
                $labels[] = $project['month_name'];
                $data[] = $project['month_count'];
            }

            $response = [
                'labels' => $labels,
                'data' => $data
            ];

            echo json_encode($response);
        }
    }

    public function tasks_report()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            if (!check_permissions("tasks", "read", "", true)) {
                redirect('/home', 'refresh');
            }

            $product_ids = explode(',', (string)$user->workspace_id);

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

            $user_names = $this->users_model->get_user_names();
            $data['user_names'] = $user_names;
            $workspace_id = $this->session->userdata('workspace_id');
            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $data['is_admin'] =  $this->ion_auth->is_admin();
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $this->load->view('tasks_report', $data);
            } else {
                redirect('/home', 'refresh');
            }
        }
    }

    public function filter_by_task_year_status()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $year = $this->input->get('year');
            $workspace_id = $this->session->userdata('workspace_id');

            $statuses = $this->statuses_model->get_statuses_task($workspace_id);
            $data = [];
            $statusTypes = [];

            foreach ($statuses as $status) {
                $statusTypes[] = $status['status'];
                $this->db->select('COUNT(id) as total, YEAR(date_created) AS year,status');
                $this->db->from('tasks');

                if ($year == '') {
                    $this->db->where("YEAR(date_created) =", date('Y'), FALSE);
                } else {
                    $this->db->where('YEAR(date_created) =', $year);
                }

                $this->db->where('status', $status['status']);
                $this->db->where('workspace_id', $workspace_id);
                $this->db->group_by('year');
                $this->db->order_by('year');

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

    public function filter_by_task_month()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $month = $this->input->get('month');
            $user_id = $this->input->get('user_id');
            $workspace_id = $this->session->userdata('workspace_id');

            $this->db->select('CONCAT(YEAR(start_date), "-", LPAD(MONTH(start_date), 2, "0")) AS month_year,MONTHNAME(start_date) as month_name, COUNT(*) AS month_count,user_id');
            $this->db->from('tasks');

            if ($month == '') {
                $this->db->where("YEAR(start_date) =", date('Y'), FALSE);
            } else {
                $this->db->where('CONCAT(YEAR(start_date), "-", LPAD(MONTH(start_date), 2, "0")) =', $month);
            }
            if ($user_id) {
                $this->db->where("FIND_IN_SET($user_id, user_id)");
            }
            $this->db->where("workspace_id", $workspace_id);
            $this->db->group_by('month_year');
            $this->db->order_by('month_year');

            $query = $this->db->get();
            $projects = $query->result_array();

            $labels = [];
            $data = [];

            foreach ($projects as $project) {
                $labels[] = $project['month_name'];
                $data[] = $project['month_count'];
            }

            $response = [
                'labels' => $labels,
                'data' => $data
            ];

            echo json_encode($response);
        }
    }

    public function filter_by_task_end_month()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $month = $this->input->get('month');
            $user_id = $this->input->get('user_id');
            $workspace_id = $this->session->userdata('workspace_id');

            $this->db->select('CONCAT(YEAR(due_date), "-", LPAD(MONTH(due_date), 2, "0")) AS month_year,MONTHNAME(due_date) as month_name, COUNT(*) AS month_count,user_id');
            $this->db->from('tasks');

            if ($month == '') {
                $this->db->where("YEAR(due_date) =", date('Y'), FALSE);
            } else {
                $this->db->where('CONCAT(YEAR(due_date), "-", LPAD(MONTH(due_date), 2, "0")) =', $month);
            }
            if ($user_id) {
                $this->db->where("FIND_IN_SET($user_id, user_id)");
            }
            $this->db->where("workspace_id", $workspace_id);
            $this->db->group_by('month_year');
            $this->db->order_by('month_year');

            $query = $this->db->get();
            $projects = $query->result_array();

            $labels = [];
            $data = [];

            foreach ($projects as $project) {
                $labels[] = $project['month_name'];
                $data[] = $project['month_count'];
            }

            $response = [
                'labels' => $labels,
                'data' => $data
            ];

            echo json_encode($response);
        }
    }

    public function leads_report()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            if (!check_permissions("leads", "read", "", true)) {
                redirect('/home', 'refresh');
            }
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', (string)$user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            if (!empty($workspace)) {

                $data['new'] = $this->custom_funcation_model->get_count('id', 'leads', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="new"');

                $data['qualified'] = $this->custom_funcation_model->get_count('id', 'leads', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="qualified"');

                $data['discussion'] = $this->custom_funcation_model->get_count('id', 'leads', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="discussion"');

                $data['won'] = $this->custom_funcation_model->get_count('id', 'leads', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="won"');

                $data['lost'] = $this->custom_funcation_model->get_count('id', 'leads', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="lost"');
            } else {
                $data['new'] = 0;

                $data['qualified'] = 0;

                $data['discussion'] = 0;

                $data['won'] = 0;

                $data['lost'] = 0;
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

            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('leads_report', $data);
            } else {
                redirect('/home', 'refresh');
            }
        }
    }

    public function filter_by_lead_year_status()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $year = $this->input->get('year');
            $workspace_id = $this->session->userdata('workspace_id');

            $statuses = ['new', 'qualified', 'discussion', 'won', 'lost'];
            $data = [];

            foreach ($statuses as $status) {
                $this->db->select('COUNT(id) as total, YEAR(created_at) AS year');
                $this->db->from('leads');

                if ($year == '') {
                    $this->db->where("YEAR(created_at) =", date('Y'), FALSE);
                } else {
                    $this->db->where('YEAR(created_at) =', $year);
                }

                $this->db->where('status', $status);
                $this->db->where('workspace_id', $workspace_id);
                $this->db->group_by('year');
                $this->db->order_by('year');

                $query = $this->db->get();
                $taskCount = $query->result_array();

                $data[] = $taskCount ? $taskCount[0]['total'] : 0;
            }

            $response = [
                'labels' => $statuses,
                'data' => $data
            ];

            echo json_encode($response);
        }
    }

    public function filter_by_lead_assigned_month()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $month = $this->input->get('month');
            $user_id = $this->input->get('user_id');
            $workspace_id = $this->session->userdata('workspace_id');

            $this->db->select('CONCAT(YEAR(assigned_date), "-", LPAD(MONTH(assigned_date), 2, "0")) AS month_year,MONTHNAME(assigned_date) as month_name, COUNT(*) AS month_count,user_id');
            $this->db->from('leads');

            if ($month == '') {
                $this->db->where("YEAR(assigned_date) =", date('Y'), FALSE);
            } else {
                $this->db->where('CONCAT(YEAR(assigned_date), "-", LPAD(MONTH(assigned_date), 2, "0")) =', $month);
            }
            if ($user_id) {
                $this->db->where("FIND_IN_SET($user_id, user_id)");
            }
            $this->db->where("workspace_id", $workspace_id);
            $this->db->group_by('month_year');
            $this->db->order_by('month_year');

            $query = $this->db->get();
            $projects = $query->result_array();

            $labels = [];
            $data = [];

            foreach ($projects as $project) {
                $labels[] = $project['month_name'];
                $data[] = $project['month_count'];
            }

            $response = [
                'labels' => $labels,
                'data' => $data
            ];

            echo json_encode($response);
        }
    }

    public function invoices_report()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            if (!check_permissions("invoices", "read", "", true)) {
                redirect('/home', 'refresh');
            }
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', (string)$user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);

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

            if (!empty($workspace_id)) {
                $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
                $data['projects'] = $projects;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('invoices_report', $data);
            } else {
                redirect('/home', 'refresh');
            }
        }
    }

    public function filter_by_income_invoice()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $month = $this->input->get('month');
            $client_id = $this->input->get('client_id');
            $workspace_id = $this->session->userdata('workspace_id');

            $statuses = ['1', '2', '3', '4', '5'];
            $data = [];

            foreach ($statuses as $status) {
                $this->db->select('SUM(amount) as total,client_id');
                $this->db->from('invoices');

                if ($month == '') {
                    $this->db->where("YEAR(invoice_date) =", date('Y'), FALSE);
                } else {
                    $this->db->where('CONCAT(YEAR(invoice_date), "-", LPAD(MONTH(invoice_date), 2, "0")) =', $month);
                }

                if ($client_id) {
                    $this->db->where("FIND_IN_SET($client_id, client_id)");
                }
                $this->db->where('status', $status);
                $this->db->where("workspace_id", $workspace_id);

                $query = $this->db->get();
                $projectCount = $query->result_array();

                $data[] = isset($projectCount[0]['total']) ? $projectCount[0]['total'] : 0;
            }

            $response = [
                'data' => $data
            ];

            echo json_encode($response);
        }
    }
}
