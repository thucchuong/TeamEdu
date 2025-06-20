<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Email_templates_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }
    function create_email_templates($data)
    {
        if ($this->db->insert('email_templates', $data))
            return true;
        else
            return false;
    }
    function edit_email_templates($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('email_templates', $data))
            return true;
        else
            return false;
    }

    function get_mail_list($type)
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
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
            $where = " WHERE (id like '%" . $search . "%' OR subject like '%" . $search . "%' OR message like '%" . $search . "%' OR type like '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT count(*) as total FROM email_templates" . $where);
        $total = 0;
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        $query = $this->db->query("SELECT * FROM email_templates" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        $res = $query->result_array();

        $bulkData = array();
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {

            $action = '<div class="dropdown card-widgets">
                <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item has-icon modal-edit-email-templates-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>
                    <a class="dropdown-item has-icon delete-Email-templates-type-alert" href="#" data-type-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
                </div>
            </div>';
            $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                            </div>';
            $tempRow['id'] = $row['id'];
            $tempRow['type'] = ucwords(str_replace('_', " ", $row['type']));
            $tempRow['subject'] = $row['subject'];
            $tempRow['message'] = $row['message'];
            $tempRow['date_sent'] = $row['date_sent'];
            $tempRow['action'] = is_admin() ? $action_btns : '-';
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
    function delete_email_templates($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('email_templates'))
            return true;
        else
            return false;
    }

    function get_email_templates_by_id($email_id = "", $name = "")
    {
        $query = $this->db->query('SELECT * FROM email_templates e WHERE e.id="' . $email_id . '"  OR e.type="' . $name . '" ');
        return $query->result_array();
    }
}
