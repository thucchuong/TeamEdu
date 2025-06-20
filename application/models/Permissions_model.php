<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Permissions_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    public function add_permissions($data)
    {
        if ($this->db->insert('permissions', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function update_permissions($id, $workspace_id, $data)
    {
        $this->db->where('user_id', $id)->where('workspace_id', $workspace_id);
        if ($this->db->update('permissions', $data)) {
            return true;
        } else {
            return false;
        }

    }

    public function get_permissions($workspace_id, $user_id)
    {
        $this->db->where('workspace_id', $workspace_id)->where('user_id', $user_id);
        $this->db->from('permissions');
        $this->db->order_by("created_at", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_global_permissions($workspace_id, $user_id)
    {
        $this->db->where('workspace_id', $workspace_id)->where('user_id', $user_id);
        $this->db->from('permissions');
        $this->db->order_by("created_at", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }
}
