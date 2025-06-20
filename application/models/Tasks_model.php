<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tasks_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function get_tasks_list($workspace_id, $user_id, $role)
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
            $search =  $this->db->escape_like_str($get['search']);
            $where .= " and (t.id like '%" . $search . "%' OR t.title like '%" . $search . "%' OR t.description like '%" . $search . "%' OR t.priority like '%" . $search . "%' OR t.status like '%" . $search . "%' OR p.title like '%" . $search . "%' OR t.start_date like '%" . $search . "%' OR t.due_date like '%" . $search . "%')";
        }

        if (isset($get['role']) && !empty($get['role'])) {
            $role = $get['role'];
        }

        if (isset($get['project']) && !empty($get['project'])) {
            $search =  $this->db->escape_like_str($get['project']);
            $where .= " and (p.title like '%" . $search . "%')";
        }
        if (isset($get['status']) && !empty($get['status'])) {
            $status =  $this->db->escape_like_str($get['status']);
            $where .= " and t.status='" . $status . "'";
        }


        if (isset($get['from']) && isset($get['to']) && !empty($get['from'])  && !empty($get['to'])) {
            $from = strip_tags($get['from']);
            $to = strip_tags($get['to']);
            $where .= " and t.start_date>='" . $from . "' and t.due_date<='" . $to . "' ";
        }

        if (isset($get['user_id']) && !empty($get['user_id'])) {
            $user_id = strip_tags($get['user_id']);
        }


        if (isset($get['client_id']) && !empty($get['client_id'])) {

            $client_id = strip_tags($get['user_id']);
            if (is_client($user_id)) {
                $where .= " AND FIND_IN_SET($client_id,t.user_id)";
            }
        }

        if ($role == 'admin') {
            $where .= " ";
        } elseif ($role == 'clients') {
            $current_user_id = $this->session->userdata('user_id');
            $where .= " and FIND_IN_SET($user_id,t.user_id)";
            if (isset($get['user_id']) && !empty($get['user_id'])) {
                
                $where .= " and FIND_IN_SET($current_user_id,p.client_id)";
            }
        } else {
            $where .= " and FIND_IN_SET($user_id,t.user_id)";
        }

        $query = $this->db->query("SELECT COUNT('t.id') as total FROM `tasks` t LEFT JOIN
        projects p ON t.project_id=p.id WHERE t.workspace_id=$workspace_id" . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT t.*,p.title as project_title,p.client_id as client_id FROM tasks t LEFT JOIN
        projects p ON t.project_id=p.id WHERE t.workspace_id=$workspace_id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);

        // print_r($this->db->last_query());    
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
                $profile .= '<figure class="avatar mr-1 avatar-sm" data-toggle="tooltip" data-title="+' . $j . '" data-initial="+' . $j . '">
        </figure>';
            }

            if (!empty($profile)) {
                $profiles = '<li class="media">
                    ' . $profile . '
                    </li>';
            } else {
                $profiles = 'Not Found!';
            }

            $projects_client_ids = explode(',', (string)$row['client_id']);
            $projects_clients = $this->users_model->get_user_array_responce($projects_client_ids);

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
                $cprofile .= '<figure class="avatar mr-1 avatar-sm" data-toggle="tooltip" data-title="+' . $j . '" data-initial="+' . $j . '">
        </figure>';
            }

            if (!empty($cprofile)) {
                $cprofiles = '<li class="media">
                    ' . $cprofile . '
                    </li>';
            } else {
                $cprofiles = 'Not Found!';
            }


            $tempRow['id'] = $row['id'];
            $tempRow['title'] = '<a href="' . base_url('projects/tasks/' . $row['project_id']) . '">' . $row['title'] . '</a>';
            $tempRow['description'] = $row['description'];
            $priority = !empty($this->lang->line('label_' . $row['priority'])) ? $this->lang->line('label_' . $row['priority']) : $row['priority'];
            $tempRow['priority'] = '<div class="badge badge-' . $row["class"] . ' projects-badge">' . ucwords($priority) . '</div>';
            $statuses = $this->statuses_model->get_statuses($workspace_id);
            if (isset($statuses) && is_array($statuses)) {
                $task_status = '';
                foreach ($statuses as $status) {
                    if (isset($row['status']) && $row['status'] == $status['type']) {
                        $task_status = '<div class="badge badge-' . $status['text_color'] . ' projects-badge">' . $status['type'] . '</div>';
                        break;
                    }
                }
                if (!$task_status) {
                    $task_status = '<div class="badge badge-' . $row["class"] . ' projects-badge">' . $row['status'] . '</div>';
                }
            }
            $tempRow['status'] = $task_status;
            $tempRow['start_date'] = $row['start_date'];
            $tempRow['due_date'] = $row['due_date'];
            $tempRow['project_id'] = $row['project_id'];
            $tempRow['project_title'] = '<a href="' . base_url('projects/details/' . $row['project_id']) . '">' . ucwords((string)$row['project_title']) . '</a>';
            $tempRow['projects_userss'] = $profiles;
            $tempRow['projects_clientss'] = $cprofiles;
            if (check_permissions("tasks", "read")) {
                $view = '<a href="' . base_url('projects/tasks/' . $row['project_id']) . '" class="btn btn-light"><i class="fas fa-eye"></i></a>';
            }
            $tempRow['action'] = $view;

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_tasks_count($workspace_id, $user_id)
    {
        if (is_admin()) {
            $query = $this->db->query('SELECT count(id) as total FROM tasks WHERE  workspace_id=' . $workspace_id);
        } else {
            $query = $this->db->query('SELECT count(id) as total FROM tasks WHERE FIND_IN_SET(' . $user_id . ',`user_id`) AND workspace_id=' . $workspace_id);
        }
        return $query->result_array();
    }
    function create_task($data)
    {
        if ($this->db->insert('tasks', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function get_project($workspace_id, $user_id)
    {
        $query = $this->db->query('SELECT * FROM projects WHERE FIND_IN_SET(' . $user_id . ',`user_id`) AND workspace_id=' . $workspace_id . '');
        return $query->result_array();
    }

    function edit_task($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('tasks', $data))
            return true;
        else
            return false;
    }

    function delete_task($id)
    {
        $this->db->delete('comments', array('task_id' => $id));
        $query = $this->db->query("SELECT * FROM project_media WHERE type_id=$id AND type='task' ");
        $data = $query->result_array();
        if (!empty($data)) {
            $abspath = getcwd();
            foreach ($data as $row) {
                unlink($abspath . '\assets\project\\' . $row['file_name']);
            }
            $this->db->delete('project_media', array('type_id' => $id, 'type' => 'task'));
        }
        $query = $this->db->query("SELECT * FROM tasks WHERE id=$id");
        $data = $query->result_array();
        $comment_count = $data[0]['comment_count'];
        $project_id = $data[0]['project_id'];

        $this->db->query("UPDATE projects SET comment_count = `comment_count`-$comment_count WHERE id=$project_id ");

        if ($this->db->delete('tasks', array('id' => $id)))
            return true;
        else
            return false;
    }

    function get_task_by_id($task_id)
    {
        $query = $this->db->query('SELECT t.*,u.first_name as user_name,u.profile as profile, m.title as milestone_name FROM tasks t 
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN milestones m ON t.milestone_id = m.id
        WHERE t.id=' . $task_id . ' ');
        $tasks = $query->result_array();

        $product = array();
        $i = 0;

        foreach ($tasks as $task) {
            $product[$i] = $task;
            $query = $this->db->query('SELECT c.*,u.first_name as commenter_name FROM comments c LEFT JOIN users u ON c.user_id = u.id WHERE task_id=' . $task['id'] . ' ');
            $product[$i]['comments'] = $query->result_array();
            $query = $this->db->query('SELECT * FROM project_media WHERE type="task" AND type_id=' . $task['id'] . ' ');
            $product[$i]['project_media'] = $query->result_array();
            $query = $this->db->query('SELECT * FROM users WHERE FIND_IN_SET(id,"' . $task['user_id'] . '")');
            $product[$i]['task_users'] = $query->result_array();
        }
        return $product;
    }

    function get_task_by_project_id($project_id)
    {
        $query = $this->db->query('SELECT t.*,u.first_name,u.last_name,u.profile FROM tasks t 
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN milestones m ON t.milestone_id = m.id
        WHERE t.project_id=' . $project_id . ' ');
        $tasks = $query->result_array();

        $product = array();
        $i = 0;

        foreach ($tasks as $task) {
            $product[$i] = $task;
            $query = $this->db->query('SELECT c.*,u.first_name as commenter_name FROM comments c LEFT JOIN users u ON c.user_id = u.id WHERE task_id=' . $task['id'] . ' ');
            $product[$i]['comments'] = $query->result_array();
            $query = $this->db->query('SELECT * FROM project_media WHERE type="task" AND type_id=' . $task['id'] . ' ');
            $product[$i]['project_media'] = $query->result_array();
            $query = $this->db->query('SELECT * FROM users WHERE FIND_IN_SET(id,"' . $task['user_id'] . '")');
            $product[$i]['task_users'] = $query->result_array();
            $this->db->query('SELECT * FROM activity_log WHERE task_id=' . $task['id'] . ' ');
            $query = $product[$i]['activity'] = $query->result_array();
            $i++;
        }
        return $product;
    }

    function task_status_update($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('tasks', $data))
            return true;
        else
            return false;
    }

    function add_task_comment($data)
    {
        if ($this->db->insert('comments', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function task_comment_count_update($id)
    {
        if ($this->db->query('UPDATE tasks SET comment_count = `comment_count`+1 WHERE id=' . $id . ''))
            return true;
        else
            return false;
    }

    function get_task_users($id)
    {
        $query = $this->db->query('SELECT user_id FROM tasks WHERE id=' . $id);
        return $query->result_array();
    }

    function get_task($id)
    {
        $query = $this->db->query('SELECT * FROM tasks WHERE id=' . $id);
        return $query->result_array();
    }

    function get_task_users_by_project_id($id, $user_id)
    {
        $query = $this->db->query('SELECT id FROM tasks WHERE project_id=' . $id . ' AND ' . $user_id . ' IN (user_id)');
        // print_r($this->db->last_query());
        return $query->result_array();
    }
    function send_email($user_ids, $task_id, $admin_id, $subject = "")
    {
        try {
            $recepients = array();
            $task = $this->get_task_by_id($task_id);
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
            $data['type'] = 'task';
            $data['type_title'] = $task[0]['title'];
            $data['type_id'] = $task[0]['id'];
            $data['company_title'] = get_compnay_title();
            $email_templates =  fetch_details('email_templates', ['type' => "task_assigned"]);
            $email_first_name = '{first_name}';
            $email_last_name = '{last_name}';
            $email_type = '{type}';
            $email_task_title = '{task_title}';
            $email_task_id = '{task_id}';

            $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
            $hashtag = html_entity_decode($string);

            $message_data = (!empty($email_templates)) ? str_replace(array($email_first_name, $email_last_name, $email_type, $email_task_title, $email_task_id), array($admin[0]['first_name'], $admin[0]['last_name'], $data['type_id'], $data['type_title'], $data['type_id']), $hashtag)  : $admin_name . " just assigned you new " . $data['type'] . " <b>" . $data['type_title'] . "</b> ID <b>#" . $data['type_id'] . "</b></p>";
            $data['message'] = output_escaping(trim($message_data, '"'));
            $this->email->message($this->load->view('project-task-email-template.php', $data, true));
            $this->email->send();
        } catch (Exception $e) {
            $response['error'] = true;
            echo json_encode($response);
        }
    }

    function tasks_list($workspace_id, $user_id = '', $role = '')
    {
        $this->db->select('t.*,u.first_name,u.last_name,u.profile,p.title as project_title');
        $this->db->from('tasks t');
        $this->db->join('users u', 't.user_id = u.id', 'left');
        $this->db->join('projects p', 't.project_id = p.id', 'left');
        $this->db->join('milestones m', 't.milestone_id = m.id', 'left');
        if ($role == "members") {
            $this->db->where('FIND_IN_SET(' . $user_id . ', t.user_id)');
        }
        if ($role == "clients") {
            $this->db->where('FIND_IN_SET(' . $user_id . ', t.user_id)');
        }
        $this->db->where("t.workspace_id = " . $workspace_id);
        $this->db->order_by('t.updated_at', 'desc');
        $this->db->limit(5);
        $query = $this->db->get();
        $tasks = $query->result_array();
        return  $tasks;
    }

    function fetch_tasks($workspace_id, $user_id, $user_type)
    {
        if ($user_type != 'normal') {
            $query = $this->db->query('SELECT * FROM tasks WHERE FIND_IN_SET(' . $user_id . ',`client_id`) AND workspace_id=' . $workspace_id);
        } else {
            $query = $this->db->query('SELECT * FROM tasks WHERE FIND_IN_SET(' . $user_id . ',`user_id`) AND workspace_id=' . $workspace_id);
        }
        return $query->result_array();
    }

    function get_activity_list($user_id, $workspace_id, $task_id)
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
        if (isset($get['id']) && !empty($get['id'])) {
            $task_id = strip_tags($get['id']);
        }
        if (isset($get['search']) &&  !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where .= " where (id like '%" . $search . "%' OR user_id LIKE '%" . $search . "%' OR user_name LIKE '%" . $search . "%' OR type LIKE '%" . $search . "%' OR activity LIKE '%" . $search . "%'  OR date_created LIKE '%" . $search . "%' OR project_title LIKE '%" . $search . "%' OR task_title LIKE '%" . $search . "%' OR comment LIKE '%" . $search . "%')";
        }
        if (isset($get['activity']) && !empty($get['activity'])) {
            $where .= empty($where) ? " AND activity = '" . $get['activity'] . "'" : " AND activity = '" . $get['activity'] . "'";
        }

        if (isset($get['activity_type']) && !empty($get['activity_type'])) {
            $where .= empty($where) ? " AND type = '" . $get['activity_type'] . "'" : " AND type = '" . $get['activity_type'] . "'";
        }
        $query = $this->db->query("SELECT COUNT('id') as total FROM `activity_log` where workspace_id=" . $workspace_id . " " . "AND task_id=" . $task_id . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT * FROM activity_log where workspace_id=" . $workspace_id . " " . " AND task_id=" . $task_id . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);

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

    function get_tasks($workspace_id)
    {
        $this->db->where('workspace_id', $workspace_id);
        $this->db->from('tasks');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
}
