<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Projects_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'file']);
    }

    function get_projects_list($workspace_id, $user_id)
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $where = '';
        $get = $this->input->get();
        if (isset($get['sort']))
            $sort = strip_tags($get['sort']);
        if (isset($get['offset']))
            $offset = strip_tags($get['offset']);
        if (isset($get['limit']))
            $limit = strip_tags($get['limit']);
        if (isset($get['order']))
            $order = strip_tags($get['order']);
        if (isset($get['search']) &&  !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where = " and (id like '%" . $search . "%' OR title like '%" . $search . "%' OR description like '%" . $search . "%' OR status like '%" . $search . "%' OR task_count like '%" . $search . "%')";
        }

        if (isset($get['type']) && !empty($get['type'])) {
            $type = strip_tags($get['type']);
            $where .= " and status='" . $type . "'";
        }
        if (isset($get['priority']) && !empty($get['priority'])) {
            $priority = strip_tags($get['priority']);
            $where .= " and priority='" . $priority . "'";
        }
        if (isset($get['from']) && isset($get['to']) && !empty($get['from'])  && !empty($get['to'])) {
            $from = strip_tags($get['from']);
            $to = strip_tags($get['to']);
            $where .= " and start_date>='" . $from . "' and end_date<='" . $to . "' ";
        }

        if (isset($get['user_id']) && !empty($get['user_id'])) {
            $user_id = strip_tags($get['user_id']);
        }


        if (isset($get['client_id']) && !empty($get['client_id'])) {

            $client_id = strip_tags($get['client_id']);
            if (!is_client($user_id)) {
                $where .= " AND FIND_IN_SET($client_id,client_id)";
            }
        }

        if (isset($user_id) && !empty($user_id) && !is_client($user_id)) {
            $where .= " AND FIND_IN_SET($user_id,user_id)";
        }


        if ($this->ion_auth->is_admin($user_id)) {
            $query = $this->db->query("SELECT count(id) as total FROM projects WHERE  workspace_id=$workspace_id " . $where);
        } else if (!is_client($user_id)) {
            $query = $this->db->query("SELECT COUNT(id) as total FROM projects WHERE FIND_IN_SET($user_id,user_id) AND workspace_id=$workspace_id " . $where);
        } else {
            $query = $this->db->query("SELECT COUNT(id) as total FROM projects WHERE FIND_IN_SET($user_id,client_id) AND workspace_id=$workspace_id " . $where);
        }

        $res = $query->result_array();



        foreach ($res as $row) {
            $total = $row['total'];
        }

        if ($this->ion_auth->is_admin($user_id)) {

            $query = $this->db->query("SELECT * FROM projects WHERE  workspace_id= $workspace_id " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        } else if (!is_client($user_id)) {

            $query = $this->db->query("SELECT * FROM projects WHERE FIND_IN_SET($user_id,user_id) AND workspace_id= $workspace_id " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        } else {

            $query = $this->db->query("SELECT * FROM projects WHERE FIND_IN_SET($user_id,client_id) AND workspace_id= $workspace_id " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        }

        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        $this->config->load('taskhub');
        $progress_bar_classes = $this->config->item('progress_bar_classes');

        foreach ($res as $row) {
            $profile = '';
            $cprofile = '';
            $tempRow['id'] = $row['id'];

            $projects_user_ids = explode(',', $row['user_id']);
            $projects_userss = $this->users_model->get_user_array_responce($projects_user_ids);
            $i = 0;
            $j = count($projects_userss);
            foreach ($projects_userss as $projects_users) {
                if ($i < 2) {
                    if (isset($projects_users['profile']) && !empty($projects_users['profile'])) {
                        $profile .= '<a href="' . base_url('assets/profiles/' . $projects_users['profile']) . '" data-lightbox="images" data-title="' . $projects_users['first_name'] . '">
                        <img alt="image" class="mr-1 rounded-circle" width="30" src="' . base_url('assets/profiles/' . $projects_users['profile']) . '">
                        </a>';
                    } else {
                        $profile .= '<figure class="avatar mr-1 avatar-sm" data-toggle="tooltip" data-title="' . $projects_users['first_name'] . '" data-initial="' . mb_substr($projects_users['first_name'], 0, 1) . '' . mb_substr($projects_users['last_name'], 0, 1) . '">
                </figure>';
                    }
                    $j--;
                }
                $i++;
            }

            if ($i > 2) {
                $profile .= '<figure class="avatar mr-1 avatar-sm" data-toggle="tooltip" data-title="+' . $j . '" data-initial="+' . $j . '"> </figure>';
            }

            if (!empty($profile)) {
                $profiles = '<li class="media">
                    ' . $profile . '
                    </li>';
            } else {
                $profiles = 'Not assigned.';
            }

            $projects_client_ids = explode(',', $row['client_id']);
            $projects_clients = $this->users_model->get_user_array_responce($projects_client_ids);
            $statuses = $this->statuses_model->get_statuses($workspace_id);
            $statuses_project = $this->statuses_model->get_statuses_project($workspace_id);
            if (isset($statuses) && is_array($statuses)) {
                $project_status = '';
                foreach ($statuses as $status) {
                    if (isset($row['status']) && $row['status'] == $status['type']) {
                        $project_status = '<div class="badge badge-' . $status['text_color'] . ' projects-badge">' . $status['type'] . '</div>';
                        break;
                    }
                }
                if (!$project_status) {
                    $project_status = '<div class="badge badge-' . $row["class"] . ' projects-badge">' . $row['status'] . '</div>';
                }
            }
            $ci = 0;
            $cj = count($projects_clients);
            foreach ($projects_clients as $projects_client) {
                if ($ci < 2) {
                    if (isset($projects_client['profile']) && !empty($projects_client['profile'])) {

                        $cprofile .= '<a href="' . base_url('assets/profiles/' . $projects_client['profile']) . '" data-lightbox="images" data-title="' . $projects_client['first_name'] . '">
                        <img alt="image" class="mr-3 rounded-circle" width="50" src="' . base_url('assets/profiles/' . $projects_client['profile']) . '">
                        </a>';
                    } else {
                        $cprofile .= '<figure class="avatar mr-1 avatar-sm" data-toggle="tooltip" data-title="' . $projects_client['first_name'] . '" data-initial="' . mb_substr($projects_client['first_name'], 0, 1) . '' . mb_substr($projects_client['last_name'], 0, 1) . '">
                </figure>';
                    }
                    $cj--;
                }
                $ci++;
            }

            if ($ci > 2) {
                $cprofile .= '<figure class="avatar mr-1 avatar-sm" data-toggle="tooltip" data-title="+' . $j . '" data-initial="+' . $j . '"> </figure>';
            }

            if (!empty($cprofile)) {
                $cprofiles = '<li class="media">
                    ' . $cprofile . '
                    </li>';
            } else {
                $cprofiles = 'Not assigned';
            }
            foreach ($statuses_project as $status) {
                if (($row['status']) == $status['status']) {
                    $date1 = new DateTime("now");
                    $date2 = new DateTime($row['end_date']);
                    $interval = $date2->diff($date1);
                    $start_date = strtotime($row['start_date']);
                    $end_date = strtotime($row['end_date']);
                    $remaining_days = ($end_date - $start_date) / 60 / 60 / 24;

                    $current_date =  date('Y-m-d');
                    if ($current_date > $row['end_date']) {
                        $dead_line = !empty($this->lang->line('label_left')) ?
                            $this->lang->line('label_missed_sinces') . ' ' . '<div class="badge badge-danger text-center">' . $remaining_days . ' ' . $this->lang->line('label_left') . '</div>' :
                            '<span class="badge badge-danger">' . $remaining_days . " days left </span>";
                    } else if ($remaining_days <= 5) {
                        $dead_line = !empty($this->lang->line('label_left')) ?
                            '<div class="badge badge-warning text-center">' . $remaining_days . ' ' . $this->lang->line('label_left') . '</div>' :
                            '<span class="badge badge-warning">' . $remaining_days . " days left </span>";
                    } else if ($remaining_days <= 15) {
                        $dead_line = !empty($this->lang->line('label_left')) ?
                            '<div class="badge badge-primary text-center">' . $remaining_days . ' ' . $this->lang->line('label_left') . '</div>' :
                            '<span class="badge badge-primary">' . $remaining_days . " days left </span>";
                    } else if ($remaining_days <= 20) {
                        $dead_line = !empty($this->lang->line('label_left')) ?
                            '<div class="badge badge-info text-center">' . $remaining_days . ' ' . $this->lang->line('label_left') . '</div>' :
                            '<span class="badge badge-info">' . $remaining_days . " days left </span>";
                    } else {
                        $dead_line = !empty($this->lang->line('label_left')) ?
                            '<div class="badge badge-success text-center">' . $remaining_days . ' ' . $this->lang->line('label_left') . '</div>' :
                            '<span class="badge badge-success">' . $remaining_days . " days left </span>";
                    }
                } else {
                    $dead_line = '-';
                }
            }
            if ($row['priority'] == 'low') {
                $priority = !empty($this->lang->line('label_low')) ? '<div class="badge badge-primary text-center">' . $this->lang->line('label_low') . '</div>' : '<span class = "badge badge-primary"> Low </span>';
            } else if ($row['priority'] == 'medium') {
                $priority = !empty($this->lang->line('label_medium')) ? '<div class="badge badge-warning text-center">' . $this->lang->line('label_medium') . '</div>' : '<span class = "badge badge-warning "> Medium </span>';
            } else if ($row['priority'] == 'high') {
                $priority = !empty($this->lang->line('label_high')) ? '<div class="badge badge-danger text-center">' . $this->lang->line('label_high') . '</div>' : '<span class = "badge badge-danger"> High </span>';
            } else {
                $priority = !empty($this->lang->line('label_no_priority')) ? '<div class="badge badge-info text-center">' . $this->lang->line('label_no_priority') . '</div>' : '<span class = "badge badge-info"> No priority selected </span>';
            }

            $tempRow['priority'] = $priority;
            $tempRow['project_progress'] = $this->get_project_progress($workspace_id, $row['id']);

            $action = '<a href="' . base_url('projects/details/' . $row['id']) . '" class="btn btn-light mr-2"><i class="fas fa-eye"></i></a>';
            $edit = '';
            if (check_permissions("projects", "update")) {
                $edit = '<a class="dropdown-item has-icon modal-edit-project-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>';
            }
            $duplicate = '';
            if (check_permissions("projects", "create")) {
                $duplicate = '<a class="dropdown-item has-icon modal-duplicate-project-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-copy"></i>' . (!empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate') . '</a>';
            }
            $delete = '';
            if (check_permissions("projects", "delete")) {
                $delete = '<a class="dropdown-item has-icon delete-project-alert" href="' . base_url('projects/delete/' . $row['id']) . '" data-project_id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>';
            }

            if ($this->ion_auth->is_admin($user_id) || is_editor($user_id)) {
                $action .= '<div class="dropdown card-widgets">
                <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                ' . $edit . '' . $duplicate . '' . $delete . '
                </div>
                </div>';
            }
            if (check_permissions("projects", "update") || (check_permissions("projects", "create")) || (check_permissions("projects", "delete"))) {
                $action_btns = '<div class="btn-group no-shadow">
            ' . $action . '
            </div>';
            }
            $tempRow['dead_line'] = $dead_line;
            $tempRow['projects_userss'] = $profiles;
            $tempRow['projects_clientss'] = $cprofiles;
            $tempRow['title'] = '<a href="' . base_url('projects/details/' . $row['id']) . '">' . $row['title'] . '</a>';
            $tempRow['description'] = mb_substr($row['description'], 0, 20) . '...';
            $tempRow['status'] = $project_status;
            $tempRow['task_count'] = '<a href="' . base_url('projects/details/' . $row['id']) . '">' . $row['task_count'] . '</a>';

            $tempRow['action'] = $action_btns;

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function create_project($data)
    {
        if ($this->db->insert('projects', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function get_project_progress($workspace_id, $id = '')
    {
        $query = $this->db->query("SELECT class,status, COUNT(id) AS Total , ROUND((COUNT(id) / (SELECT COUNT(id) FROM tasks WHERE project_id=$id AND workspace_id=$workspace_id)) * 100) AS percentage FROM tasks WHERE project_id=$id AND workspace_id=$workspace_id GROUP BY status,class");

        // return $this->db->last_query();
        return $query->result_array();
    }

    function get_project($workspace_id, $user_id, $filter = '', $user_type = 'normal', $sort = '', $order = '', $limit = '', $start = '')
    {

        $sort_by = ($this->input->get('sort')) ? $this->input->get('sort', true) : '';
        $sort = 'id';
        $order = 'desc';
        if ($sort_by == "newest-desc") {
            $sort = 'id';
            $order = 'DESC';
        } elseif ($sort_by == "oldest-asc") {
            $sort = 'id';
            $order = 'ASC';
        } elseif ($sort_by == "recently-desc") {
            $sort = 'updated_at';
            $order = 'DESC';
        } elseif ($sort_by == "recently-asc") {
            $sort = 'updated_at';
            $order = 'ASC';
        }

        if (!empty($limit)) {
            $where_limit = ' LIMIT ' . $limit;
            if (!empty($start)) {
                $where_limit .= ' OFFSET ' . $start;
            }
        } else {
            $where_limit = '';
        }

        if (!empty($filter)) {
            $where = "AND status='$filter'";
        } else {
            $where = '';
        }
        if (is_admin() && $user_type == 'normal') {
            $query = $this->db->query('SELECT * FROM projects WHERE  workspace_id=' . $workspace_id . ' ' . $where . 'ORDER BY ' . $sort . ' ' . $order . ' ' . $where_limit);
        } else  if ($user_type != 'normal') {
            $query = $this->db->query('SELECT * FROM projects WHERE FIND_IN_SET(' . $user_id . ',`client_id`) AND workspace_id=' . $workspace_id . ' ' . $where . 'ORDER BY ' . $sort . ' ' . $order . ' ' . $where_limit);
        } else {
            $query = $this->db->query('SELECT * FROM projects WHERE FIND_IN_SET(' . $user_id . ',`user_id`) AND workspace_id=' . $workspace_id . ' ' . $where . 'ORDER BY ' . $sort . ' ' . $order . ' ' . $where_limit);
        }

        return $query->result_array();
    }

    function get_projects_count($workspace_id, $user_id, $filter = '', $user_type = 'normal')
    {
        if (!empty($filter)) {
            $where = "AND status='$filter'";
        } else {
            $where = '';
        }
        if (is_admin()) {
            $query = $this->db->query('SELECT count(id) as total FROM projects WHERE  workspace_id=' . $workspace_id . ' ' . $where);
        } else if ($user_type != 'normal') {
            $query = $this->db->query('SELECT count(id) as total FROM projects WHERE FIND_IN_SET(' . $user_id . ',`client_id`) AND workspace_id=' . $workspace_id . ' ' . $where);
        } else {
            $query = $this->db->query('SELECT count(id) as total FROM projects WHERE FIND_IN_SET(' . $user_id . ',`user_id`) AND workspace_id=' . $workspace_id . ' ' . $where);
        }
        return $query->result_array();
    }

    function edit_project($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('projects', $data))
            return true;
        else
            return false;
    }

    function project_comment_count_update($id)
    {
        if ($this->db->query('UPDATE projects SET comment_count = `comment_count`+1 WHERE id=' . $id . ''))
            return true;
        else
            return false;
    }

    function add_file($data)
    {
        if ($this->db->insert('project_media', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function project_task_count_update($id)
    {
        if ($this->db->query('UPDATE projects SET task_count = `task_count`+1 WHERE id=' . $id . ''))
            return true;
        else
            return false;
    }

    function project_task_count_decreas($id)
    {
        if ($this->db->query('UPDATE projects SET task_count = `task_count`-1 WHERE id=' . $id . ''))
            return true;
        else
            return false;
    }

    function delete_project($id)
    {
        $this->db->delete('comments', array('project_id' => $id));
        $this->db->delete('tasks', array('project_id' => $id));
        $this->db->delete('milestones', array('project_id' => $id));
        $query = $this->db->query("SELECT * FROM project_media WHERE type_id=$id AND type='project' ");
        $data = $query->result_array();
        $abspath = getcwd();
        foreach ($data as $row) {
            unlink('assets/project/' . $row['file_name']);
        }
        $this->db->delete('project_media', array('type_id' => $id, 'type' => 'project'));

        if ($this->db->delete('projects', array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }

    function delete_file($id)
    {
        $query = $this->db->query('SELECT * FROM project_media WHERE id=' . $id . '');
        $data = $query->result_array();
        if (!empty($data)) {
            $abspath = getcwd();
            if (unlink('assets/project/' . $data[0]['file_name'])) {
                $this->db->delete('project_media', array('id' => $id));
                return true;
            } else {
                return false;
            }
        }
    }

    function get_project_by_id($project_id)
    {
        // $query = $this->db->query('SELECT * FROM projects WHERE id=' . $project_id . ' ');
        $query = $this->db->query('SELECT projects.*, IF(favourite_projects.project_id IS NOT NULL, 1, 0) AS is_favourite
                           FROM projects
                           LEFT JOIN favourite_projects ON projects.id =  favourite_projects.project_id AND favourite_projects.user_id = ' . $this->session->userdata('user_id') . '
                           WHERE projects.id = ' . $project_id);
        return $query->result_array();
    }

    function get_files($type_id, $type)
    {
        $query = $this->db->query('SELECT * FROM project_media WHERE type="' . $type . '" AND type_id=' . $type_id . '');
        return $query->result_array();
    }
    function get_project_users($id)
    {
        $query = $this->db->query('SELECT user_id FROM projects WHERE id=' . $id);
        return $query->result_array();
    }
    function get_project_clients($id)
    {
        $query = $this->db->query('SELECT client_id FROM projects WHERE id=' . $id);
        // print_r($this->db->last_query());
        return $query->result_array();
    }
    function send_email($user_ids, $project_id, $admin_id, $subject = "")
    {

        $recepients = array();
        try {
            $project = $this->get_project_by_id($project_id);
            $admin = $this->users_model->get_user_by_id($admin_id);
            $admin_name = $admin[0]['first_name'] . ' ' . $admin[0]['last_name'];
            for ($i = 0; $i < count($user_ids); $i++) {
                $query = $this->db->query("SELECT email FROM users WHERE id=" . $user_ids[$i]);
                $data = $query->result_array();
                if (isset($data[0]) && !empty($data[0])) {
                    array_push($recepients, $data[0]['email']);
                }
            }
            $recepients = implode(",", $recepients);
            $config = $this->config->item('ion_auth')['email_config'];
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $from_email = get_admin_email();
            $this->email->from($from_email, get_compnay_title());
            $this->email->to($recepients);
            $this->email->subject($subject);
            $data['logo'] = base_url('assets/icons/') . get_full_logo();
            $data['admin_name'] = $admin_name;
            $data['type'] = 'project';
            $data['type_title'] = $project[0]['title'];
            $data['type_id'] = $project[0]['id'];
            $data['company_title'] = get_compnay_title();
            $email_templates =  fetch_details('email_templates', ['type' => "project_assigned"]);
            $email_first_name = '{first_name}';
            $email_last_name = '{last_name}';
            $email_type = '{type}';
            $email_project_title = '{project_title}';
            $email_project_id = '{project_id}';

            $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
            $hashtag = html_entity_decode($string);

            $message_data = (!empty($email_templates)) ? str_replace(array($email_first_name, $email_last_name, $email_type, $email_project_title, $email_project_id), array($admin[0]['first_name'], $admin[0]['last_name'], $data['type_id'], $data['type_title'], $data['type_id']), $hashtag)  : $admin_name . " just assigned you new " . $data['type'] . " <b>" . $data['type_title'] . "</b> ID <b>#" . $data['type_id'] . "</b></p>";

            $data['message'] = output_escaping(trim($message_data, '"'));
            $this->email->message($this->load->view('project-task-email-template.php', $data, true));
            $this->email->send();
        } catch (Exception $e) {
            $response['error'] = true;
            echo json_encode($response);
        }
    }
    function get_projects($workspace_id)
    {
        $this->db->where('workspace_id', $workspace_id);
        $this->db->from('projects');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    function projects_list($workspace_id, $user_id, $role = '')
    {
        $where = "workspace_id = " . $workspace_id;
        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where($where);
        if ($role == "members") {
            $this->db->where("FIND_IN_SET($user_id, `user_id`)");
        }
        if ($role == "clients") {
            $this->db->where("FIND_IN_SET($user_id, `client_id`)");
        }
        $this->db->order_by('updated_at', 'desc');
        $this->db->limit(5);
        $query = $this->db->get();
        $projects = $query->result_array();
        return  $projects;
    }

    function get_favorite_project($workspace_id, $user_id, $filter = '', $user_type = 'normal', $limit = '', $start = '')
    {

        if (!empty($limit)) {
            $where_limit = ' LIMIT ' . $limit;
            if (!empty($start)) {
                $where_limit .= ' OFFSET ' . $start;
            }
        } else {
            $where_limit = '';
        }

        if (!empty($filter)) {
            $where = "AND  status='$filter'";
        } else {
            $where = '';
        }
        // $query = $this->db->query('SELECT * FROM favourite_projects WHERE user_id ='. $user_id.'  AND workspace_id=' . $workspace_id . ' ' . $where . 'ORDER BY id desc ' . $where_limit);
        // if ($user_type != 'normal') {
        // } else {
        //     $query = $this->db->query('SELECT * FROM projects WHERE FIND_IN_SET(' . $user_id . ',`user_id`) AND is_favorite = 1 AND workspace_id=' . $workspace_id . ' ' . $where . 'ORDER BY id desc ' . $where_limit);
        // }
        $this->db->select('*');
        $this->db->from('favourite_projects');
        $this->db->join('projects', 'favourite_projects.project_id = projects.id', 'left');
        $this->db->where('favourite_projects.user_id', $user_id);
        $this->db->where('favourite_projects.workspace_id', $workspace_id);
        $this->db->order_by('favourite_projects.id', 'desc');
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($where_limit)) {
            $this->db->limit($where_limit);
        }

        return $this->db->get()->result_array();
        // return $query->result_array();
    }

    function fetch_projects($workspace_id, $user_id, $user_type)
    {
        if ($user_type != 'normal') {
            $query = $this->db->query('SELECT * FROM projects WHERE FIND_IN_SET(' . $user_id . ',`client_id`) AND workspace_id=' . $workspace_id);
        } else {
            $query = $this->db->query('SELECT * FROM projects WHERE FIND_IN_SET(' . $user_id . ',`user_id`) AND workspace_id=' . $workspace_id);
        }
        return $query->result_array();
    }

    function add_to_favourites($data)
    {
        if ($this->db->insert('favourite_projects', $data))
            return true;
        else
            return false;
    }
    function remove_from_favourites($data)
    {
        if ($this->db->delete('favourite_projects', $data))
            return true;
        else
            return false;
    }

    function get_activity_list($user_id, $workspace_id, $project_id)
    {

        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $where = '';
        $get = $this->input->get();
        if (isset($get['sort']))
            $sort = strip_tags($get['sort']);
        if (isset($get['offset']))
            $offset = strip_tags($get['offset']);
        if (isset($get['limit']))
            $limit = strip_tags($get['limit']);
        if (isset($get['order']))
            $order = strip_tags($get['order']);
        if (isset($get['project_id']) && !empty($get['project_id'])) {
            $project_id = strip_tags($get['project_id']);
        }

        if (isset($get['search']) &&  !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where .= " where (id like '%" . $search . "%' OR user_id LIKE '%" . $search . "%' OR user_name LIKE '%" . $search . "%' OR type LIKE '%" . $search . "%' OR activity LIKE '%" . $search . "%'  OR date_created LIKE '%" . $search . "%' OR project_title LIKE '%" . $search . "%' OR task_title LIKE '%" . $search . "%' OR comment LIKE '%" . $search . "%')";
        }
        if (isset($get['activity']) && !empty($get['activity'])) {
            $where .= empty($where) ? " WHERE activity = '" . $get['activity'] . "'" : " AND activity = '" . $get['activity'] . "'";
        }
        if (isset($get['activity_type']) && !empty($get['activity_type'])) {
            $where .= empty($where) ? " WHERE type = '" . $get['activity_type'] . "'" : " AND type = '" . $get['activity_type'] . "'";
        }
        $where .= empty($where) ? " where workspace_id=" . $workspace_id : " and workspace_id=" . $workspace_id;
        $where .= empty($where) ? " where project_id=" . $project_id : " and project_id=" . $project_id;

        $query = $this->db->query("SELECT COUNT('id') as total FROM `activity_log`" . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT * FROM activity_log" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);

        // print_r($this->db->last_query());
        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($res as $row) {
            if ($row['activity'] == 'Created') {
                $activity = "<span class='badge badge-success'>Created</span>";
            }
            if ($row['activity'] == 'Updated') {
                $activity = "<span class='badge badge-info'>Updated</span>";
            }
            if ($row['activity'] == 'Deleted') {
                $activity = "<span class='badge badge-danger'>Deleted</span>";
            }
            if ($row['activity'] == 'Uploaded') {
                $activity = "<span class='badge badge-warning'>Uploaded</span>";
            }
            $user_name =  $row['user_id'] == $user_id ? 'You' : $row['user_name'];
            $tempRow['id'] = $row['id'];
            $tempRow['workspace_id'] = $row['workspace_id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['user_name'] = $user_name;
            $tempRow['type'] = $row['type'];
            $tempRow['project_id'] = $row['project_id'];
            $tempRow['project_title'] = $row['project_title'];
            $tempRow['task_id'] = $row['task_id'];
            $tempRow['task_title'] = $row['task_title'];
            $tempRow['comment_id'] = $row['comment_id'];
            $tempRow['comment'] = $row['comment'];
            $tempRow['file_id'] = $row['file_id'];
            $tempRow['file_name'] = $row['file_name'];
            $tempRow['milestone_id'] = $row['milestone_id'];
            $tempRow['milestone'] = $row['milestone'];
            $tempRow['activity'] = $activity;
            $tempRow['message'] = $row['message'];
            $tempRow['date_created'] = date("d-M-Y H:i:s", strtotime($row['date_created']));


            $action = '
                            <a class="dropdown-item has-icon delete-activity-alert" href="' . base_url('activity-logs/delete/' . $row['id']) . '" data-activity-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i></a>
                            
                        ';

            $action_btns = '<div class="btn-group no-shadow">
                                ' . $action . '
                                </div>';
            $tempRow['action'] = is_admin() ? $action_btns : '';


            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
