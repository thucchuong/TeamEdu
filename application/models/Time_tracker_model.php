<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Time_tracker_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function create_record($data)
    {
        if ($this->db->insert('time_tracker_sheet', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function edit_record($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('time_tracker_sheet', $data))
            return true;
        else
            return false;
    }
    function delete_time_tracker($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('time_tracker_sheet'))
            return true;
        else
            return false;
    }
    function get_record_by_id($record_id)
    {
        $query = $this->db->query('SELECT * FROM time_tracker_sheet WHERE id=' . $record_id . ' ');
        return $query->result_array();
    }

    function get_record($workspace_id, $user_id)
    {
        $query = $this->db->query('SELECT * FROM time_tracker_sheet WHERE workspace_id=' . $workspace_id . ' AND user_id=' . $user_id . ' ORDER BY id desc');
        return $query->result();
    }

    function get_user_records($workspace_id, $user_id)
    {

        $offset = '0';
        $limit = '5';
        $sort = 'id';
        $order = 'ASC';
        $where = '';
        $search = "";
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

        if (isset($get['search']) &&  !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where .= " and (tts.start_time like '%" . $search . "%' OR tts.end_time like '%" . $search . "%' OR tts.duration like '%" . $search . "%' OR tts.message like '%" . $search . "%' OR tts.date like '%" . $search . "%' OR tts.workspace_id like '%" . $search . "%' OR u.username like '%" . $search . "%')";
        }

        if (isset($get['from']) && isset($get['to']) && !empty($get['from'])  && !empty($get['to'])) {
            $from = strip_tags($get['from']);
            $to = strip_tags($get['to']);
            $where .= " and tts.date >= '" . $from . "' and tts.date <= '" . $to . "' ";
        }

        if (isset($get['user_id']) && !empty($get['user_id'])) {
            $user_id = strip_tags($get['user_id']);
            $where .= " and tts.user_id >= '" . $user_id . "'";
        }

        $query = $this->db->query("
        SELECT COUNT(tts.id) as total FROM `time_tracker_sheet` as tts JOIN users as u on u.id = tts.user_id WHERE tts.user_id = $user_id AND tts.workspace_id = $workspace_id
        " . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query(
            "SELECT tts.*,p.title as project_title, u.username, u.email, u.first_name, u.last_name FROM `time_tracker_sheet` as tts
            JOIN users as u on u.id = tts.user_id
          LEFT JOIN projects as p on p.id = tts.project_id
            WHERE tts.user_id = $user_id AND tts.workspace_id = $workspace_id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit
        );
        // _pre1($this->db->last_query());
        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $action = '';
        foreach ($res as $row) {
            $tempRow['id'] = $row['id'];
            if (is_admin()) {
                $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item has-icon modal-edit-time-tracker-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>
                        <a class="dropdown-item has-icon delete-time-tracker-alert" href="' . base_url('time_tracker/delete/' . $row['id']) . '" data-time-tracker-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
                    </div>
                </div>';
                $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                            </div>';
            }
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['workspace_id'] = $row['workspace_id'];
            $tempRow['project_id'] = $row['project_title'] ? $row['project_title'] : '-';
            $tempRow['start_time'] = $row['start_time'];
            $tempRow['end_time'] = $row['end_time'];
            $tempRow['duration'] = $row['duration'];
            $tempRow['message'] = $row['message'];
            $tempRow['date'] = $row['date'];
            if (is_admin() || is_member() || is_client() || is_workspace_admin($user_id, $workspace_id)) {
                $profile = is_admin($row['id']) && is_member($row['id']) ? '' : ' <a href="' . base_url('users/edit-profile/' . $row['id']) . '" target="_blank"><i class="fas fa-edit"></i></a>';
                $profile = '';
            }
            if (!empty($row['profile'])) {
                $username = '<li class="media">
                        
                        <a href="' . base_url('assets/profiles/') . '' . $row['profile'] . '" data-lightbox="images" data-title="' . $row['first_name'] . ' ' . $row['last_name'] . '">
                        <img alt="image" class="mr-3 rounded-circle" width="50" src="' . base_url('assets/profiles/') . '' . $row['profile'] . '">
                        </a>
                        <div class="media-body">
                          <div class="media-title">' . $row['username'] . $profile . '</div>
                          <div class="text-job text-muted">' . $row['email'] . '</div>
                        </div>
                      </li>';
            } else {
                $username = '<li class="media">
                        <figure class="avatar mr-2 bg-info text-white" data-initial="' . mb_substr($row['first_name'], 0, 1) . '' . mb_substr($row['last_name'], 0, 1) . '"></figure>
                        <div class="media-body">
                          <div class="media-title">' . $row['username'] . '</div>
                          <div class="text-job text-muted">' . $row['email'] . '</div>
                        </div>
                      </li>';
            }

            $tempRow['username'] = $username;
            $tempRow['action'] = is_admin() ? $action_btns : '-';
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
