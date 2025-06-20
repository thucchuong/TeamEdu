<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leads_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }
    function create_leads($data)
    {
        if ($this->db->insert('leads', $data))
            return true;
        else
            return false;
    }

    function edit_leads($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('leads', $data))
            return true;
        else
            return false;
    }
    function lead_status_update($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('leads', $data))
            return true;
        else
            return false;
    }
    function get_leads($workspace_id)
    {
        $query = $this->db->query('SELECT * FROM leads WHERE workspace_id=' . $workspace_id . ' ORDER BY id desc');
        return $query->result();
    }
    function get_leads_list($type, $workspace_id, $user_id)
    {

        $offset = 0;
        $limit = 10;
        $sort = 'position';
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
            $where = " and (l.id like '%" . $search . "%' OR l.title like '%" . $search . "%' OR l.email like '%" . $search . "%' OR l.phone like '%" . $search . "%' OR l.status like '%" . $search . "%')";
        }

        if (isset($get['workspace_id']) &&  !empty($get['workspace_id'])) {
            $workspace_id = strip_tags($get['workspace_id']);
        }

        if (isset($get['text']) &&  !empty($get['text'])) {
            $workspace_id = strip_tags($get['text']);
        }
        if (isset($get['status']) && !empty($get['status'])) {
            $status = strip_tags($get['status']);
            $where .= " and l.status='" . $status . "'";
        }

        if (isset($get['from']) && isset($get['to']) && !empty($get['from'])  && !empty($get['to'])) {
            $from = strip_tags($get['from']);
            $to = strip_tags($get['to']);
            $where .= " and l.assigned_date>='" . $from . "' and l.assigned_date<='" . $to . "' ";
        }

        if (isset($get['user_id']) && !empty($get['user_id'])) {
            $user_id = strip_tags($get['user_id']);
            $where .= " and FIND_IN_SET($user_id,l.user_id)";
        }

        $query = $this->db->query("
        SELECT COUNT(l.id) as total FROM `leads` as l JOIN users as u on u.id = l.user_id WHERE l.workspace_id = $workspace_id
        " . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        $query = $this->db->query(
            "SELECT l.* FROM `leads` as l
            JOIN users as u on u.id = l.user_id
            WHERE l.workspace_id = $workspace_id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit
        );

        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            $client_create = '';
            if (check_permissions("clients", "create")) {
                $client_create = '<a class="dropdown-item has-icon lead_status modal-edit-client-leads-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-plus"></i>' . (!empty($this->lang->line('label_convert_to_client')) ? $this->lang->line('label_convert_to_client') : 'Convert to Client') . '</a>';
            }
            $edit = '';
            if (check_permissions("leads", "update")) {
                $edit = ' <a class="dropdown-item has-icon modal-edit-leads-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>';
            }
            $duplicate = '';
            if (check_permissions("leads", "create")) {
                $duplicate = '<a class="dropdown-item has-icon modal-duplicate-lead" href="#" data-id="' . $row['id'] . '"><i class="fas fa-copy"></i>' . (!empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate') . '</a>';
            }
            $delete = '';
            if (check_permissions("leads", "delete")) {
                $delete = '<a class="dropdown-item has-icon delete-leads-type-alert" href="#" data-type-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>';
            }
            $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                    ' . $client_create . '' . $edit . '' . $duplicate . '' . $delete . '
                    </div>
                </div>';
            if ((check_permissions("clients", "create")) || check_permissions("leads", "update") || (check_permissions("leads", "create")) || (check_permissions("leads", "delete"))) {
                $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                </div>';
            }
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['email'] = $row['email'];
            $tempRow['phone'] = $row['phone'];
            $tempRow['description'] = $row['description'];
            $profile = '';
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
            if ($row['status'] == "new") {
                $status = '<div class="badge badge-info text-center">New</div>';
            } else if ($row['status'] == "qualified") {
                $status = '<div class="badge badge-secondary text-center">Qualified</div>';
            } else if ($row['status'] == "discussion") {
                $status = '<div class="badge badge-warning text-center">Discussion</div>';
            } else if ($row['status'] == "won") {
                $status = '<div class="badge badge-success text-center">Won</div>';
            } else if ($row['status'] == "lost") {
                $status = '<div class="badge badge-danger text-center">Lost</div>';
            }
            $tempRow['status'] = $status;
            $tempRow['assigned_date'] = $row['assigned_date'];
            $tempRow['user_ids'] = $profiles;
            $tempRow['action'] = $action_btns;
            $rows[] = $tempRow;
        }


        if ($type == "controller") {
            $bulkData['rows'] = $rows;
            return $bulkData;
        } else {
            $bulkData['total'] = $total;
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }
    }

    function get_leads_by_id($lead_id)
    {
        $query = $this->db->query('SELECT * FROM leads WHERE id=' . $lead_id . ' ');
        return $query->result_array();
    }

    function convert_to_client_id($lead_id)
    {
        $query = $this->db->query('SELECT * FROM leads WHERE id=' . $lead_id . ' ');
        return $query->result_array();
    }

    function delete_leads($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('leads'))
            return true;
        else
            return false;
    }

    function fetch_leads($workspace_id, $user_id, $user_type)
    {
        if ($user_type != 'normal') {
            $query = $this->db->query('SELECT * FROM leads WHERE FIND_IN_SET(' . $user_id . ',`client_id`) AND workspace_id=' . $workspace_id);
        } else {
            $query = $this->db->query('SELECT * FROM leads WHERE FIND_IN_SET(' . $user_id . ',`user_id`) AND workspace_id=' . $workspace_id);
        }
        return $query->result_array();
    }
}
