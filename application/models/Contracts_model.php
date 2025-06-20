<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contracts_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }
    function create_contracts($data)
    {
        if ($this->db->insert('contracts', $data))
            return true;
        else
            return false;
    }
    function create_contracts_type($data)
    {
        if ($this->db->insert('contracts_type', $data))
            return true;
        else
            return false;
    }
    function get_contracts_type()
    {
        $this->db->from('contracts_type');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    function edit_contracts($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('contracts', $data))
            return true;
        else
            return false;
    }
    function edit_contracts_type($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('contracts_type', $data))
            return true;
        else
            return false;
    }
    function get_contracts($workspace_id, $user_id)
    {
        $query = $this->db->query('SELECT * FROM contracts WHERE workspace_id=' . $workspace_id . ' AND user_ids=' . $user_id . ' ORDER BY id desc');
        return $query->result();
    }

    function get_contracts_list($role, $users_id = '')
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
            $where = " WHERE (c.id like '%" . $search . "%' OR ct.type like '%" . $search . "%' OR p.title like '%" . $search . "%' OR c.title like '%" . $search . "%' OR c.description like '%" . $search . "%' OR c.value like '%" . $search . "%')";
        }
        if (is_client()) {
            $where .= " WHERE c.users_id =" . $users_id;
        }

        if (isset($get['user_id']) && !empty($get['user_id'])) {
            $user_id = strip_tags($get['user_id']);
            $where .= empty($where) ? " where c.users_id='" . $user_id . "'" : "and c.users_id='" . $user_id . "'";
        }
        if (isset($get['project_id']) && !empty($get['project_id'])) {
            $project_id = strip_tags($get['project_id']);
            $where .= empty($where) ? " where c.project_id='" . $project_id . "'" : "and c.project_id='" . $project_id . "'";
        }

        $where .=  " where c.workspace_id='" . $this->session->userdata('workspace_id') . "'";

        $query = $this->db->query("SELECT count(c.id) as total,c.*, p.title AS project_title, ct.type AS contracts_type, u.first_name AS first_name, u.last_name AS last_name FROM contracts c LEFT JOIN projects p ON c.project_id = p.id LEFT JOIN users u ON c.users_id = u.id LEFT JOIN contracts_type ct ON c.contract_type_id = ct.id" . $where);
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }


        $query = $this->db->query("SELECT c.*, p.title AS project_title, ct.type AS contracts_type, u.first_name AS first_name, u.last_name AS last_name FROM contracts c LEFT JOIN projects p ON c.project_id = p.id LEFT JOIN users u ON c.users_id = u.id LEFT JOIN contracts_type ct ON c.contract_type_id = ct.id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            if (empty($row['provider_sign']) && empty($row['client_sign'])) {
                $row['signed_status'] = '<div class="badge badge-primary">Pending</div>';
            } elseif ($row['provider_sign'] && $row['client_sign']) {
                $row['signed_status'] = '<div class="badge badge-success">Active</div>';
            } elseif ($row['provider_sign'] || $row['client_sign']) {
                $row['signed_status'] = '<div class="badge badge-warning">Awaiting</div>';
            }
            if (check_permissions("contracts", "update")) {
                $edit = '<a class="dropdown-item has-icon modal-edit-contracts-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>';
            }
            if (check_permissions("contracts", "create")) {
                $duplicate = ' <a class="dropdown-item has-icon modal-duplicate-contracts" href="#" data-id="' . $row['id'] . '"><i class="fas fa-copy"></i>' . (!empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate') . '</a> ';
            }
            if (check_permissions("contracts", "delete")) {
                $delete = '<a class="dropdown-item has-icon delete-contracts-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>';
            }
            if (check_permissions("contracts", "update") || check_permissions("contracts", "delete") || check_permissions("contracts", "create")) {
                $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                    ' .  $edit . '' . $duplicate . '' . $delete . '
                    </div>
                </div>';
            }
            $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                </div>';
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = '<a href="' . base_url('contracts/contracts_sign/' . $row['id']) . '" target="_blank">' . $row['title'] . '</a></div>';
            $tempRow['users_id'] = $row['first_name'] . " " . $row['last_name'];
            $tempRow['workspace_id'] = $row['workspace_id'];
            $tempRow['project_id'] = $row['project_title'];
            $tempRow['description'] = $row['description'];
            $tempRow['value'] = $row['value'];
            $tempRow['signed_status'] = $row['signed_status'];
            $tempRow['start_date'] = $row['start_date'];
            $tempRow['contract_type_id'] = $row['contracts_type'];
            $tempRow['end_date'] = $row['end_date'];
            $tempRow['action'] = $action_btns;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_contracts_type_list()
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
            $where = " WHERE (ct.id like '%" . $search . "%' OR ct.type like '%" . $search . "%')";
        }
        $query = $this->db->query("SELECT count(ct.id) as total,ct.* FROM contracts_type ct" . $where);
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }


        $query = $this->db->query("SELECT ct.* FROM contracts_type ct" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            $edit = '';
            if (check_permissions("contracts", "update")) {
                $edit = ' <a class="dropdown-item has-icon modal-edit-contracts-type-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>';
            }
            $delete = '';
            if (check_permissions("contracts", "delete")) {
                $delete = '<a class="dropdown-item has-icon delete-contracts-type-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>';
            }
            if (check_permissions("contracts", "update") || check_permissions("contracts", "delete")) {
                $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                    ' .  $edit . '' . $delete . '
                    </div>
                </div>';
            }
            $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                </div>';
            $tempRow['id'] = $row['id'];
            $tempRow['type'] = $row['type'];
            $tempRow['action'] = $action_btns;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_contracts_by_id($contracts_id)
    {
        $query = $this->db->query('SELECT * FROM contracts WHERE id=' . $contracts_id . ' ');
        return $query->result_array();
    }

    function get_contracts_sign_by_id($contracts_id)
    {
        $query = $this->db->query('SELECT * FROM contracts WHERE id=' . $contracts_id . ' ');
        return $query->result_array();
    }
    function get_contracts_type_by_id($contracts_id)
    {
        $query = $this->db->query('SELECT * FROM contracts_type WHERE id=' . $contracts_id . ' ');
        return $query->result_array();
    }

    function delete_contracts($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('contracts'))
            return true;
        else
            return false;
    }


    function delete_contracts_type($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('contracts_type'))
            return true;
        else
            return false;
    }
    function delete_provider_sign_contracts($id)
    {
        $query = $this->db->query("SELECT * FROM contracts WHERE id=$id ");
        $data = $query->result_array();

        if (!empty($data)) {
            foreach ($data as $row) {
                unlink('assets/sign/' . $row['provider_sign']);
            }

            $this->db->update('contracts', array('provider_sign' => ''), array('id' => $id));
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function delete_client_sign_contracts($id)
    {
        $query = $this->db->query("SELECT * FROM contracts WHERE id=$id ");
        $data = $query->result_array();

        if (!empty($data)) {
            foreach ($data as $row) {
                unlink('assets/sign/' . $row['client_sign']);
            }

            $this->db->update('contracts', array('client_sign' => ''), array('id' => $id));
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function contracts_sign($contracts_id)
    {
        $query = $this->db->query("SELECT c.*, p.title AS project_title, ct.type AS contracts_type, u.first_name AS user_first_name, u.last_name AS user_last_name FROM contracts c LEFT JOIN projects p ON c.project_id = p.id LEFT JOIN users u ON c.users_id = u.id LEFT JOIN contracts_type ct ON c.contract_type_id = ct.id where c.id=" . $contracts_id . ' ');
        return $query->result();
    }
}
