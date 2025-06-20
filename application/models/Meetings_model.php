<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Meetings_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function create_meeting($data)
    {
        // $data['title'] = $this->db->escape_str($data['title']);
        if ($this->db->insert('meetings', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function edit_meeting($data, $id)
    {
        // $data['title'] = $this->db->escape_str($data['title']);
        $this->db->where('id', $id);
        if ($this->db->update('meetings', $data))
            return true;
        else
            return false;
    }

    function delete_meeting($id, $user_id)
    {
        $meeting = $this->get_meeting_by_id($id);
        if (!empty($meeting) && ($meeting[0]['admin_id'] == $user_id || is_admin())) {
            if ($this->db->delete('meetings', array('id' => $id)))
                return true;
            else
                return false;
        } else {
            return false;
        }
    }

    function get_note($workspace_id, $user_id)
    {
        $query = $this->db->query('SELECT * FROM notes WHERE workspace_id=' . $workspace_id . ' AND user_id=' . $user_id . ' ORDER BY id desc');
        return $query->result();
    }

    function get_note_by_id($note_id)
    {
        $query = $this->db->query('SELECT * FROM notes WHERE id=' . $note_id . ' ');
        return $query->result_array();
    }
    function get_meetings_list($workspace_id, $user_id)
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
            $where = " and (m.id like '%" . $search . "%' OR m.title like '%" . $search . "%' OR m.start_date like '%" . $search . "%' OR m.slug like '%" . $search . "%')";
        }

        if (isset($get['from']) && isset($get['to']) && !empty($get['from'])  && !empty($get['to'])) {
            $from = strip_tags($get['from']);
            $to = strip_tags($get['to']);
            $where .= " AND m.start_date >= '" . $from . "' AND m.start_date <= '" . $to . "' ";
        }

        if (is_client()) {
            $where .= ' AND (m.admin_id=' . $user_id . " || " . "FIND_IN_SET($user_id, client_ids))";
        }
        if (is_member()) {
            $where .= ' AND (m.admin_id=' . $user_id . " || " . "FIND_IN_SET($user_id, user_ids))";
        }
        $query = $this->db->query("SELECT COUNT(m.id) as total FROM meetings m left join users u on u.id=m.admin_id WHERE m.workspace_id=$workspace_id " . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }


        $query = $this->db->query("SELECT m.*,u.first_name,u.last_name FROM meetings m left join users u on u.id=m.admin_id WHERE m.workspace_id= $workspace_id " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);

        // print_r($this->db->last_query());    
        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        $this->config->load('taskhub');


        foreach ($res as $row) {
            $profile = '';
            $cprofile = '';
            $tempRow['id'] = $row['id'];

            $meeting_user_ids = explode(',', $row['user_ids']);
            $meeting_users = $this->users_model->get_user_array_responce($meeting_user_ids);
            $i = 0;
            $j = count($meeting_users);
            $users_icon = base_url('assets/img/avatar/avatar-1.png');
            $clients_icon = base_url('assets/img/avatar/avatar-4.png');
            foreach ($meeting_users as $meeting_user) {
                if ($i < 2) {
                    if (isset($meeting_user['profile']) && !empty($meeting_user['profile'])) {
                        $profile .= '<a href="' . base_url('assets/profiles/' . $meeting_user['profile']) . '" data-lightbox="images" data-title="' . $meeting_user['first_name'] . '">
                        <img alt="image" class="mr-1 rounded-circle" width="30" src="' . base_url('assets/profiles/' . $meeting_user['profile']) . '">
                        </a>';
                    } else {
                        $profile .= '<figure class="avatar mr-1 avatar-sm" data-toggle="tooltip" data-title="' . $meeting_user['first_name'] . '" data-initial="' . mb_substr($meeting_user['first_name'], 0, 1) . '' . mb_substr($meeting_user['last_name'], 0, 1) . '">
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

            $meeting_client_ids = explode(',', $row['client_ids']);
            $meeting_clients = $this->users_model->get_user_array_responce($meeting_client_ids);

            $ci = 0;
            $cj = count($meeting_clients);
            foreach ($meeting_clients as $meeting_client) {
                if ($ci < 2) {
                    if (isset($meeting_client['profile']) && !empty($meeting_client['profile'])) {

                        $cprofile .= '<a href="' . base_url('assets/profiles/' . $meeting_client['profile']) . '" data-lightbox="images" data-title="' . $meeting_client['first_name'] . '">
                        <img alt="image" class="mr-3 rounded-circle" width="50" src="' . base_url('assets/profiles/' . $meeting_client['profile']) . '">
                        </a>';
                    } else {
                        $cprofile .= '<figure class="avatar mr-1 avatar-sm" data-toggle="tooltip" data-title="' . $meeting_client['first_name'] . '" data-initial="' . mb_substr($meeting_client['first_name'], 0, 1) . '' . mb_substr($meeting_client['last_name'], 0, 1) . '">
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
            $action = '';
            $delete = '';
            $edit = '';
            $join = '';
            $join1 = '';
            $time = time();
            $start = strtotime($row['start_date']);
            $end = strtotime($row['end_date']);
            if (is_admin() || $row['admin_id'] == $user_id) {
                $edit = '';
                if (check_permissions("meetings", "update")) {
                    $edit = '<a class="dropdown-item has-icon modal-edit-meeting-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a> ';
                }
                $delete = '';
                if (check_permissions("meetings", "delete")) {
                    $delete = '<a class="dropdown-item has-icon delete-meeting-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a> ';
                }
            }
            $join = '';
            // print_r($meeting_user_ids);
            if ((is_admin() || is_workspace_admin($user_id, $workspace_id) || in_array($user_id, $meeting_user_ids) || in_array($user_id, $meeting_client_ids) || $row['admin_id'] == $user_id) && $time > $start && $time < $end) {
                if (check_permissions("meetings", "create")) {
                    $join = '<a class="dropdown-item has-icon join-meeting-alert" href="#" data-slug="' . $row['slug'] . '"><i class="fas fa-sign-in-alt"></i>' . (!empty($this->lang->line('label_join')) ? $this->lang->line('label_join') : 'Join') . '</a>';
                    $join1 = '<br><a class="join-meeting-alert" href="#" title="Join Now" data-slug="' . $row['slug'] . '"><i class="fas fa-sign-in-alt"></i></a>';
                }
            }
            if (check_permissions("meetings", "update") || check_permissions("meetings", "delete") || check_permissions("meetings", "create")) {
                $action .= '<div class="dropdown card-widgets">
                                <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    ' . $join . $edit . $delete . '
                                    
                                </div>
                            </div>';
            }

            $action_btns = '<div class="btn-group no-shadow">
                        ' . $action . '
                        </div>';
            $start = strtotime($row['start_date']);
            $end = strtotime($row['end_date']);
            if ($start > time()) {
                $status = relativeTime($start);
            } else if ($end < time()) {
                $status = 'Ended ';
                $status .= relativeTime($end);
            } else if (time() > $start && time() < $end) {
                $status = 'On going';
            }
            $row['title'] = stripslashes($row['title']);

            $tempRow['users'] = '<a href="#" class="modal-meeting-users-ajax" data-id="' . $row['id'] . '" title="Click to see meeting users"><img alt="image" class="mr-3 rounded-circle" width="40" src=' . $users_icon . '></a>';
            $tempRow['clients'] = '<a href="#" class="modal-meeting-clients-ajax" data-id="' . $row['id'] . '" title="Click to see meeting clients"><img alt="image" class="mr-3 rounded-circle" width="40" src=' . $clients_icon . '></a>';
            $tempRow['title'] = $row['title'] . $join1;

            $tempRow['start_date'] = $row['start_date'];
            $tempRow['workspace_id'] = $row['workspace_id'];
            $tempRow['end_date'] = $row['end_date'];
            $tempRow['type'] = ucwords(str_replace('_', " ", $row['type'])) ? ucwords(str_replace('_', " ", $row['type'])) : '-';
            $tempRow['platform'] = ucwords(str_replace('_', " ", $row['platform'])) ? ucwords(str_replace('_', " ", $row['platform'])) : '-';
            $tempRow['link'] = $row['link'] ? '<a href="' . $row['link'] . '" target="_blank">Meeting link <i class="fas fa-link"></i></a>' : '-';
            $tempRow['venue'] = $row['venue'] ? $row['venue'] : '-';
            $tempRow['status'] = $status;
            $tempRow['created_on'] = $row['date_created'];
            $tempRow['created_by'] = $row['first_name'] . ' ' . $row['last_name'];
            $tempRow['action'] = !empty($edit) || !empty($delete) || !empty($join) ? $action_btns : '-';

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function get_meeting_by_id($meeting_id)
    {
        $query = $this->db->query('SELECT * FROM meetings WHERE id=' . $meeting_id . ' ');
        return $query->result_array();
    }
    function get_meeting_by_slug($slug)
    {
        $query = $this->db->query("SELECT * FROM meetings WHERE slug='" . $slug . "' ");
        return $query->result_array();
    }
    function get_meeting_by_title($title)
    {
        $query = $this->db->query("SELECT * FROM meetings WHERE title='" . $title . "' ");
        return $query->result_array();
    }

    function fetch_meeting($workspace_id, $user_id, $user_type)
    {
        if ($user_type != 'normal') {
            $query = $this->db->query('SELECT * FROM meetings WHERE FIND_IN_SET(' . $user_id . ',`client_id`) AND workspace_id=' . $workspace_id);
        } else {
            $query = $this->db->query('SELECT * FROM meetings WHERE FIND_IN_SET(' . $user_id . ',`user_ids`) AND workspace_id=' . $workspace_id);
        }
        return $query->result_array();
    }
}
