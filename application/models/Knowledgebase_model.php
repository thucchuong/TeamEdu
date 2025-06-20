<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Knowledgebase_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function create_article($data)
    {
        if ($this->db->insert('articles', $data))
            return true;
        else
            return false;
    }

    function edit_article($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('articles', $data))
            return true;
        else
            return false;
    }

    function get_group_by_id($group_id)
    {
        $this->db->from('article_group');
        $this->db->where('id', $group_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_article_list($type, $workspace_id, $user_id)
    {
        $offset = 0;
        $limit = 10;
        $sort = 'title';
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
            $where = " and (a.id like '%" . $search . "%' OR a.title like '%" . $search . "%' OR a.group like '%" . $search . "%' OR a.description like '%" . $search . "%')";
        }

        if (isset($get['workspace_id']) &&  !empty($get['workspace_id'])) {
            $workspace_id = strip_tags($get['workspace_id']);
        }

        if (isset($get['text']) &&  !empty($get['text'])) {
            $workspace_id = strip_tags($get['text']);
        }
        if (isset($get['group_id']) && !empty($get['group_id'])) {
            $group_id = strip_tags($get['group_id']);
            $where .= " and a.group_id='" . $group_id . "'";
        }

        if (isset($get['from']) && isset($get['to']) && !empty($get['from'])  && !empty($get['to'])) {
            $from = strip_tags($get['from']);
            $to = strip_tags($get['to']);
            $where .= " and a.date_published>='" . $from . "' and a.date_published<='" . $to . "' ";
        }

        $query = $this->db->query("
        SELECT COUNT(a.id) as total FROM `articles` as a JOIN users as u on u.id = a.user_id WHERE a.workspace_id = $workspace_id
        " . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        $query = $this->db->query(
            "SELECT a.* FROM `articles` as a
            JOIN users as u on u.id = a.user_id
            WHERE a.workspace_id = $workspace_id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit
        );

        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($res as $row) {
            $tempRow['id'] = $row['id'];
            $edit = '';
            if (check_permissions("knowledgebase", "update")) {
                $edit = ' <div class="bullet"></div>
                <a class="" href="' . base_url('knowledgebase/edit-article/' . $row['id']) . '">Edit</a> ';
            }
            $duplicate = '';
            if (check_permissions("knowledgebase", "create")) {
                $duplicate = '<div class="bullet"></div>
                <a href="#" data-id="' . $row['id'] . '" class="modal-duplicate-Knowledgebase">' . (!empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate') . '</a>';
            }
            $delete = '';
            if (check_permissions("knowledgebase", "delete")) {
                $delete = '<div class="bullet"></div>
                <a href="#" data-article-id="' . $row['id'] . '" class="delete-article-alert">Trash</a>';
            }
            if (check_permissions("knowledgebase", "update") || check_permissions("knowledgebase", "read") || (check_permissions("knowledgebase", "create")) || check_permissions("knowledgebase", "delete")) {
                $tempRow['title'] = $row['title'] .
                    '<div class="table-links">
                <a href="' . base_url('knowledgebase/view-article/' . $row['slug']) . '" target="_blank">View</a>
           ' . $edit . '' . $duplicate . '' . $delete . '</div>';
            }
            $tempRow['description'] = $row['description'];
            foreach ($this->get_group_by_id($row['group_id']) as $temp1) {
                $tempRow['group_name'] = $temp1['title'];
            }
            $tempRow['date_published'] = $row['date_published'];
            //$tempRow['action'] = $action_btns;
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

    function get_article_by_grp_id($grp_id, $slug_id)
    {
        $this->db->select('*');
        $this->db->from('articles');
        $this->db->where('group_id', $grp_id);
        $this->db->where('slug !=', $slug_id);
        $this->db->order_by('date_published', 'desc');
        $this->db->limit(5);
        $query = $this->db->get();
        return $query->result();
    }

    function get_article_by_id($id)
    {
        $this->db->from('articles');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_article_by_slug($slug_id)
    {
        $this->db->from('articles');
        $this->db->where('slug', $slug_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function add_group($data)
    {
        if ($this->db->insert('article_group', $data))
            return true;
        else
            return false;
    }

    function delete_article_group($article_grp_id)
    {
        $this->db->where('id', $article_grp_id);
        if ($this->db->delete('article_group'))
            return true;
        else
            return false;
    }

    function get_groups_list($type, $workspace_id, $user_id)
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
            $where = " and (id ag.like '%" . $search . "%' OR ag.title like '%" . $search . "%' OR ag.description like '%" . $search . "%')";
        }

        if (isset($get['title']) &&  !empty($get['title'])) {
            $workspace_id = strip_tags($get['title']);
        }

        $query = $this->db->query("SELECT COUNT(ag.id) as total FROM `article_group` ag" . $where);

        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        $query = $this->db->query(
            "SELECT ag.* FROM `article_group` ag" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit
        );

        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            if (check_permissions("knowledgebase", "update")) {
                $edit = '<a class="dropdown-item has-icon modal-edit-article-group-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>';
            }
            if (check_permissions("knowledgebase", "delete")) {
                $delete = '<a class="dropdown-item has-icon delete-article-group-alert" href="#" data-type-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>';
            }
            if (check_permissions("knowledgebase", "update") || check_permissions("knowledgebase", "delete")) {
                $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">                    
                    ' .  $edit . '' . $delete . '

                    </div>
                </div>';
            }
            $action_btns = '<div class="btn-group no-shadow"> ' . $action . ' </div>';
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['description'] = $row['description'];
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

    function get_groups()
    {
        $this->db->from('article_group');
        $this->db->order_by("id", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    function edit_article_group($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('article_group', $data))
            return true;
        else
            return false;
    }

    function delete_article($article_id)
    {
        $this->db->where('id', $article_id);
        if ($this->db->delete('articles'))
            return true;
        else
            return false;
    }
}
