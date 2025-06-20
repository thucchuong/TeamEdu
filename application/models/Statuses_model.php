<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Statuses_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }
    function create_statuses($data)
    {
        if ($this->db->insert('statuses', $data))
            return true;
        else
            return false;
    }
    function get_statuses($workspace_id)
    {
        $query = $this->db->select('id,type,text_color');
        $this->db->from('statuses');
        $this->db->where('workspace_id', $workspace_id);
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_statuses_project($workspace_id)
    {
        $query = $this->db->query("
        SELECT `status`
        FROM (
            SELECT `status` FROM `projects` WHERE `workspace_id` = " . $workspace_id . "  GROUP BY `status`
            UNION
            SELECT `type` as `status` FROM `statuses` GROUP BY `type`
        ) AS combined_statuses
        GROUP BY `status`
        ORDER BY status DESC
    ");
        return $query->result_array();
    }
    function get_statuses_task($workspace_id)
    {
        $query = $this->db->query("
        SELECT `status`
        FROM (
            SELECT `status` FROM `tasks` WHERE `workspace_id` = " . $workspace_id . "  GROUP BY `status`
            UNION
            SELECT `type` as `status` FROM `statuses` GROUP BY `type`
        ) AS combined_statuses
        GROUP BY `status`
        ORDER BY status DESC
    ");
        return $query->result_array();
    }
    function get_statuses_task_project($project_id)
    {
        $query = $this->db->query("
        SELECT `status`
        FROM (
            SELECT `status` FROM `tasks` WHERE `project_id` = " . $project_id . "  GROUP BY `status`
            UNION
            SELECT `type` as `status` FROM `statuses` GROUP BY `type`
        ) AS combined_statuses
        GROUP BY `status`
        ORDER BY status DESC
    ");
        return $query->result_array();
    }


    function edit_statuses($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('statuses', $data))
            return true;
        else
            return false;
    }

    function get_statuses_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'desc';
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
            $where = " WHERE (s.id like '%" . $search . "%' OR s.type like '%" . $search . "%')";
        }
        $query = $this->db->query("SELECT count(s.id) as total,s.* FROM statuses s" . $where);
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }


        $query = $this->db->query("SELECT s.* FROM statuses s" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            if ($row['text_color'] == 'info') {
                $text_color = '<div class="badge badge-info">Info</div>';
            } elseif ($row['text_color'] == 'secondary') {
                $text_color = '<div class="badge badge-secondary">Secondary</div>';
            } elseif ($row['text_color'] == 'success') {
                $text_color = '<div class="badge badge-success">Success</div>';
            } elseif ($row['text_color'] == 'danger') {
                $text_color = '<div class="badge badge-danger">Danger</div>';
            } elseif ($row['text_color'] == 'warning') {
                $text_color = '<div class="badge badge-warning">Warning</div>';
            }
            $edit = '';
            if (check_permissions("statuses", "update")) {
                $edit = '<a class="dropdown-item has-icon modal-edit-statuses-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>';
            }
            $delete = '';
            if (check_permissions("statuses", "delete")) {
                $delete = '<a class="dropdown-item has-icon delete-statuses-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>';
            }
            if (check_permissions("statuses", "update") || (check_permissions("statuses", "delete"))) {
                $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        ' . $edit . '' . $delete . '
                        
                    </div>
                </div>';
            }
            $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                </div>';
            $tempRow['id'] = $row['id'];
            $tempRow['type'] = $row['type'];
            $tempRow['text_color'] = $text_color;
            $tempRow['action'] = $action_btns;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_statuses_by_id($statuses_id)
    {
        $query = $this->db->query('SELECT * FROM statuses WHERE id=' . $statuses_id . ' ');
        return $query->result_array();
    }

    function delete_statuses($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('statuses'))
            return true;
        else
            return false;
    }
}
