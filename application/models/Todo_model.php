<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Todo_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function add($data)
    {
        // $data['title'] = $this->db->escape_str($data['title']);
        if ($this->db->insert('todos', $data))
            return $this->db->insert_id();
        else
            return false;
    }



    function update($data)
    {
        $this->db->where('position');

        // $data['title'] = $this->db->escape_str($data['title']);
        if ($this->db->update('todos', $data))
            return true;
        else
            return false;
    }

    function update_position($test, $sequence)
    {
        $this->db->where('id', $test);
        if ($this->db->update('todos', $sequence))
            return true;
        else
            return false;
    }



    function get_todo_list($workspace_id, $user_id)
    {

        // $offset = 0;
        // $limit = 10;
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


        if (isset($get['workspace_id']) &&  !empty($get['workspace_id'])) {
            $workspace_id = strip_tags($get['workspace_id']);
        }

        if (isset($get['text']) &&  !empty($get['text'])) {
            $workspace_id = strip_tags($get['text']);
        }


        $query = $this->db->query("
        SELECT COUNT(tts.id) as total FROM `todos` as tts JOIN users as u on u.id = tts.user_id WHERE tts.user_id = $user_id AND tts.workspace_id = $workspace_id
        " . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        $query = $this->db->query(
            "SELECT tts.*, u.username, u.email, u.first_name, u.last_name FROM `todos` as tts
            JOIN users as u on u.id = tts.user_id
            WHERE tts.user_id = $user_id AND tts.workspace_id = $workspace_id" . $where . " ORDER BY " . $sort . " " . $order
        );

        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $action = '';

        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $action = '';

        foreach ($res as $row) {
            $tempRow['id'] = $row['id'];
            $tempRow['position'] = $row['position'];
            $tempRow['workspace_id'] = $row['workspace_id'];
            $tempRow['description'] = $row['description'];
            $tempRow['status'] = $row['status'];
            $tempRow['created_at'] = $row['created_at'];
            $tempRow['user_id'] = $row['user_id'];

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return Json_encode($bulkData);
    }

    function todos_list($workspace_id, $user_id)
    {

        $offset = 0;
        $limit = 5;
        $sort = 'position';
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


        if (isset($get['workspace_id']) &&  !empty($get['workspace_id'])) {
            $workspace_id = strip_tags($get['workspace_id']);
        }

        $query = $this->db->query("
        SELECT COUNT(t.id) as total FROM `todos` as t JOIN users as u on u.id = t.user_id WHERE t.user_id = $user_id AND t.workspace_id = $workspace_id
        " . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        $query = $this->db->query(
            "SELECT t.*, u.username, u.email, u.first_name, u.last_name FROM `todos` as t
            JOIN users as u on u.id = t.user_id
            WHERE t.user_id = $user_id AND t.workspace_id = $workspace_id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit
        );

        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $action = '';

        foreach ($res as $row) {
            $tempRow['id'] = $row['id'];
            $tempRow['position'] = $row['position'];
            $tempRow['workspace_id'] = $row['workspace_id'];
            $tempRow['description'] = $row['description'];
            $tempRow['status'] = $row['status'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['created_at'] = $row['created_at'];

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return Json_encode($bulkData);
    }

    function edit($data, $id)
    {
       
        $this->db->where('id', $id);
        if ($this->db->update('todos', $data))
            return true;
        else
            return false;
    }
}
