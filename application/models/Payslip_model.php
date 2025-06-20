<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payslip_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }
    function get_payslip_list($workspace_id)
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
            $where = " and (p.basic_salary like '%" . $search . "%' OR u.first_name like '%" . $search . "%' OR u.last_name like '%" . $search . "%' OR p.total_earnings like '%" . $search . "%' OR p.total_deductions like '%" . $search . "%' OR p.net_pay like '%" . $search . "%' OR p.payment_date like '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT count(p.id) as total FROM payslips p  LEFT JOIN users u on p.user_id=u.id WHERE p.workspace_id = $workspace_id" . $where);
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT p.* , pm.title as payment_method,u.first_name,u.last_name FROM payslips p LEFT JOIN users u on p.user_id=u.id LEFT JOIN payment_mode pm ON p.payment_method = pm.id WHERE p.workspace_id = $workspace_id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            $payslip_month = strtotime($row['payslip_month']);
            $payment_date = strtotime($row['payment_date']);
            if ($row['status'] == 1) {
                $status = '<div class="badge badge-primary">Paid</div>';
            } else {
                $status = '<div class="badge badge-danger">Unpaid</div>';
            }
            $edit = '';
            if (check_permissions("payslips", "update")) {
                $edit = ' <div class="bullet"></div>
        <a href="' . base_url('payslip/edit-payslip/' . $row['id']) . '" data-id="' . $row['id'] . '">Edit</a>';
            }
            $duplicate = '';
            if (check_permissions("payslips", "create")) {
                $duplicate = '<div class="bullet"></div>
        <a href="#" data-id="' . $row['id'] . '" class="modal-duplicate-payslip">' . (!empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate') . '</a>';
            }
            $delete = '';
            if (check_permissions("payslips", "delete")) {
                $delete = ' <div class="bullet"></div>
                <a href="#" data-payslip-id="' . $row['id'] . '" class="text-danger delete-payslip-alert">Trash</a>';
            }
            if (check_permissions("payslips", "update") || check_permissions("payslips", "read") || (check_permissions("payslips", "create")) || check_permissions("payslips", "delete")) {

                $tempRow['id'] = '<a href="' . base_url('payslip/view-payslip/' . $row['id']) . '" target="_blank">Payslip-' . $row['id'] . '</a><div class="table-links">
            ' . $edit . '' . $duplicate . '' . $delete . '
            </div>';
            }
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['workspace_id'] = $workspace_id;
            $tempRow['username'] = $row['first_name'] . ' ' . $row['last_name'];
            $tempRow['payslip_month'] = date('F Y', $payslip_month);
            $tempRow['basic_salary'] = round($row['basic_salary'], 2);
            $tempRow['total_earnings'] = round($row['total_earnings'], 2);
            $tempRow['total_deductions'] = round($row['total_deductions'], 2);
            $tempRow['net_pay'] = round($row['net_pay'], 2);
            $tempRow['payment_date'] =  date('d F Y', $payment_date);
            $tempRow['payment_method'] = $row['payment_method'];
            $tempRow['status'] = $status;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function create_payslip($data)
    {
        if ($this->db->insert('payslips', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function add_allowance($data)
    {
        if ($this->db->insert('allowance', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function edit_payslips($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('payslips', $data))
            return true;
        else
            return false;
    }
    function delete_sign_payslips($id)
    {
        $query = $this->db->query("SELECT * FROM payslips WHERE id=$id ");
        $data = $query->result_array();

        if (!empty($data)) {
            foreach ($data as $row) {
                unlink('assets/sign/' . $row['signature']);
            }

            $this->db->update('payslips', array('signature' => ''), array('id' => $id));
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function edit_allowance($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('allowance', $data))
            return true;
        else
            return false;
    }
    function edit_deduction($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('deductions', $data))
            return true;
        else
            return false;
    }
    function add_payslip_allowance($data)
    {
        if ($this->db->insert('payslip_allowance', $data))
            return $this->db->insert_id();
        else
            return false;
    }
    function get_allowances_list()
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
            $where = " WHERE (a.id like '%" . $search . "%' OR a.name like '%" . $search . "%' OR a.amount like '%" . $search . "%')";
        }
        $query = $this->db->query("SELECT count(a.id) as total,a.* FROM allowance a" . $where);
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }


        $query = $this->db->query("SELECT a.* FROM allowance a" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item has-icon modal-edit-allowance-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>
                        <a class="dropdown-item has-icon delete-allowance-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
                    </div>
                </div>';

            $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                </div>';
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            $tempRow['amount'] = round($row['amount'], 2);
            $tempRow['action'] = $action_btns;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function delete_allowance($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('allowance'))
            return true;
        else
            return false;
    }

    function get_deductions_list()
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
            $where = " WHERE (d.id like '%" . $search . "%' OR d.name like '%" . $search . "%' OR d.amount like '%" . $search . "%' OR d.percentage like '%" . $search . "%')";
        }
        $query = $this->db->query("SELECT count(d.id) as total,d.* FROM deductions d" . $where);
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT d.* FROM deductions d" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item has-icon modal-edit-deduction-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>
                        <a class="dropdown-item has-icon delete-deduction-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
                    </div>
                </div>';

            $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                </div>';
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            $tempRow['amount'] = $row['amount'] ? round($row['amount'], 2) : "-";
            $tempRow['percentage'] = $row['percentage'] ? $row['percentage'] : "-";
            $tempRow['action'] = $action_btns;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function delete_deductions($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('deductions'))
            return true;
        else
            return false;
    }
    function get_allowance_by_id($allowance_id)
    {
        $this->db->from('allowance');
        $this->db->where('id', $allowance_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_allowance($workspace_id)
    {
        $this->db->where('workspace_id', $workspace_id);
        $this->db->from('allowance');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_allowance_items($payslip_id)
    {
        $this->db->select('pa.*, a.name as name');
        $this->db->from('payslip_allowance pa');
        $this->db->join('allowance a', 'pa.allowance_id = a.id', 'left');
        $this->db->where('pa.payslip_id', $payslip_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_deductions_items($payslip_id)
    {
        $this->db->select('pd.*, d.name as name');
        $this->db->from('payslip_deductions pd');
        $this->db->join('deductions d', 'pd.deduction_id = d.id', 'left');
        $this->db->where('pd.payslip_id', $payslip_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function add_deduction($data)
    {
        if ($this->db->insert('deductions', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function add_payslip_deduction($data)
    {
        if ($this->db->insert('payslip_deductions', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function get_deduction_by_id($deduction_id)
    {
        $this->db->from('deductions');
        $this->db->where('id', $deduction_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_deduction($workspace_id)
    {
        $this->db->where('workspace_id', $workspace_id);
        $this->db->from('deductions');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function update_payslip($data, $payslip_id)
    {
        $this->db->where('id', $payslip_id);
        if ($this->db->update('payslips', $data))
            return true;
        else
            return false;
    }

    public function get_payslip_by_id($payslip_id)
    {
        $this->db->select('p.*, u.first_name,u.email,u.last_name, pa.allowance_id,pa.amount as allowamount,pa.payslip_id as allowpayslipid, pd.deduction_id,pd.amount as deductionamount,pd.payslip_id as deductionpayslipid,pd.deduction_type,pd.percentage');
        $this->db->from('payslips p');
        $this->db->join('users u', 'p.user_id = u.id', 'LEFT');
        $this->db->join('payslip_allowance pa', 'p.id = pa.payslip_id', 'LEFT');
        $this->db->join('payslip_deductions pd', 'p.id = pd.payslip_id', 'LEFT');
        $this->db->where('p.id', $payslip_id);
        $query = $this->db->get();

        return $query->result_array();
    }
    function delete_payslip($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('payslips'))
            return true;
        else
            return false;
    }

    function update_payslip_allowance_item($data, $payslip_allowance_ids)
    {
        $this->db->where('allowance_id', $payslip_allowance_ids);
        return $this->db->update('payslip_allowance', $data);
    }

    function update_payslip_deduction_item($data, $payslip_deduction_ids)
    {
        $this->db->where('deduction_id', $payslip_deduction_ids);
        return $this->db->update('payslip_deductions', $data);
    }


    function delete_payslip_deductions_item($deleted_deductions_item_ids)
    {
        $this->db->where('id', $deleted_deductions_item_ids);
        return $this->db->delete('payslip_deductions', array('id' => $deleted_deductions_item_ids));
    }
    function delete_payslip_allowance_item($deleted_allowance_item_ids)
    {
        $this->db->where('id', $deleted_allowance_item_ids);
        return $this->db->delete('payslip_allowance', array('id' => $deleted_allowance_item_ids));
    }
}
