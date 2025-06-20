<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['projects_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function get_role_by_user_id($id)
    {
        $query = $this->db->query("SELECT g.`name` as role FROM `groups` g LEFT JOIN users_groups ug ON ug.`group_id`=g.`id` WHERE ug.`user_id`=" . $id);
        $result = $query->result_array();
        return $result[0]['role'];
    }

    function get_users_list($workspace_id, $user_id = '')
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
            $where = " and (u.first_name like '%" . $search . "%' OR u.last_name like '%" . $search . "%' OR u.email like '%" . $search . "%' OR u.workspace_id like '%" . $search . "%')";
        }
        if (isset($get['workspace_id']) &&  !empty($get['workspace_id'])) {
            $workspace_id = strip_tags($get['workspace_id']);
        }
        if (isset($get['user_type']) && !empty($get['user_type'])) {
            $where .= " AND ug.group_id =" . $get['user_type'];
        } else {
            $where .= " AND ug.group_id !=3";
        }
        $left_join = 'LEFT JOIN users u ON ug.user_id = u.id';

        $query = $this->db->query("SELECT COUNT(ug.id) as total FROM users_groups ug $left_join WHERE FIND_IN_SET($workspace_id,u.workspace_id) " . $where);
        // print_r($this->db->last_query());
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT u.* FROM users_groups ug $left_join WHERE FIND_IN_SET($workspace_id,u.workspace_id) " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);

        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $action = '';

        foreach ($res as $row) {
            $tempRow['id'] = $row['id'];
            if (is_admin()) {
                $edit = '';
                if (is_client($row['id'])) {
                    if ((check_permissions("clients", "update"))) {
                        $edit = is_admin($row['id']) ? '' : ' <a href="' . base_url('clients/edit-profile/' . $row['id']) . '" target="_blank"><i class="fas fa-edit"></i></a>';
                    }
                } else {
                    if ((check_permissions("users", "update"))) {
                        $edit = is_admin($row['id']) ? '' : ' <a href="' . base_url('users/edit-profile/' . $row['id']) . '" target="_blank"><i class="fas fa-edit"></i></a>';
                    }
                }
            } else {
                $profile = '';
                $edit = '';
            }

            if (!empty($row['profile'])) {
                $first_name = '<li class="media">
                        
                        <a href="' . base_url('assets/profiles/') . '' . $row['profile'] . '" data-lightbox="images" data-title="' . $row['first_name'] . ' ' . $row['last_name'] . '">
                        <img alt="image" class="mr-3 rounded-circle" width="50" src="' . base_url('assets/profiles/') . '' . $row['profile'] . '">
                        </a>
                        <div class="media-body">
                          <div class="media-title">' . $row['first_name'] . ' ' . $row['last_name'] . ' <a href="' . base_url('users/detail/' . $row['id']) . '" target="_blank"><i class="fas fa-eye"></i></a> ' . $edit . '</div>
                          <div class="text-job text-muted">' . $row['email'] . '</div>
                        </div>
                      </li>';
            } else {
                $first_name = '<li class="media">
                        <figure class="avatar mr-2 bg-info text-white" data-initial="' . mb_substr($row['first_name'], 0, 1) . '' . mb_substr($row['last_name'], 0, 1) . '"></figure>
                        <div class="media-body">
                          <div class="media-title">' . $row['first_name'] . ' ' . $row['last_name'] . ' <a href="' . base_url('users/detail/' . $row['id']) . '" target="_blank"><i class="fas fa-eye"></i></a>' . $edit . '</div>
                          <div class="text-job text-muted">' . $row['email'] . '</div>
                        </div>
                      </li>';
            }

            $tempRow['first_name'] = $first_name;

            $super_action_btn = '<a class="dropdown-item has-icon make-user-super-admin-alert" data-user_id="' . $row['id'] . '" href="' . base_url('users/make_user_super_admin/' . $row['id']) . '"><i class="fas fa-plus"></i> ' . (!empty($this->lang->line('label_make_super_admin')) ? $this->lang->line('label_make_super_admin') : 'Make Super Admin') . '</a>';
            $admin_action_btn = '<a class="dropdown-item has-icon make-user-admin-alert" data-user_id="' . $row['id'] . '" href="' . base_url('users/make-user-admin/' . $row['id']) . '"><i class="fas fa-plus"></i>' . (!empty($this->lang->line('label_make_admin')) ? $this->lang->line('label_make_admin') : 'Make Admin') . '</a>';
            $admin_super_action_btn = '<a class="dropdown-item has-icon make-user-admin-alert" data-user_id="' . $row['id'] . '" href="' . base_url('users/make-user-admin/' . $row['id']) . '"><i class="fas fa-plus"></i>' . (!empty($this->lang->line('label_make_admin')) ? $this->lang->line('label_make_admin') : 'Make Admin') . '</a> <a class="dropdown-item has-icon make-user-super-admin-alert" data-user_id="' . $row['id'] . '" href="' . base_url('users/make_user_super_admin/' . $row['id']) . '"><i class="fas fa-plus"></i> ' . (!empty($this->lang->line('label_make_super_admin')) ? $this->lang->line('label_make_super_admin') : 'Make Super Admin') . '</a>';

            if ($this->ion_auth->is_admin($row['id'])) {
                $tempRow['role'] = '<div class="badge badge-secondary">Super Admin</div>';
            } elseif (is_editor($row['id'])) {
                $tempRow['role'] = '<div class="badge badge-secondary">Admin</div>';
            } else {
                if (!is_client($row['id'])) {
                    $tempRow['role'] = '<div class="badge badge-secondary">Team Member</div>';
                } else {
                    $tempRow['role'] = '<div class="badge badge-secondary">Client</div>';
                }
            }

            if ($row['active'] == 1) {
                $active_btn = '<div class="dropdown-divider"></div> <a class="dropdown-item has-icon deactive-user-alert" data-user_id="' . $row['id'] . '" href="' . base_url('users/deactive/' . $row['id']) . '"><i class="fas fa-user-alt-slash"></i>' . !empty($this->lang->line('label_deactive')) ? $this->lang->line('label_deactive') : 'Deactive' . '</a>';
            } else {
                $active_btn = '<div class="dropdown-divider"></div> <a class="dropdown-item has-icon active-user-alert" data-user_id="' . $row['id'] . '" href="' . base_url('users/activate/' . $row['id']) . '"><i class="fas fa-user-check"></i>' . !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active' . '</a>';
            }

            $permission_btn = '<a  class="dropdown-item has-icon" data-user_id="' . $row['id'] . '"data-toggle="modal" data-target="#exampleModal" onclick="test_net(this)"> <i class="fas fa-key"></i> Permission </a>';
            $delete_from_workspace = '';
            if ((check_permissions("clients", "delete")) || (check_permissions("users", "delete"))) {
                $delete_from_workspace = ' <a class="dropdown-item has-icon delete-user-alert" data-user_id="' . $row['id'] . '" href="' . base_url('users/remove-user-from-workspace/' . $row['id']) . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete_from_workspace')) ? $this->lang->line('label_delete_from_workspace') : 'Delete From Workspace') . '</a>';
            }
            if (is_admin($user_id)) {

                if (is_admin($row['id'])) {
                    $action = "-";
                } elseif (is_editor($row['id'])) {
                    $action = '<div class="dropdown card-widgets">
                                  <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                  ' . $super_action_btn . '
                                  <div class="dropdown-divider"></div>
                                    ' . $delete_from_workspace . ' ' . $active_btn . '
                                  </div>
                              </div>';
                } elseif (is_client($row['id'])) {
                    $action = '<div class="dropdown card-widgets">
                                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    ' . $permission_btn . '
                                    <div class="dropdown-divider"></div>

                                    ' . $delete_from_workspace . ' ' . $active_btn . '
                                    </div>
                                </div>';
                } else {
                    $action = '<div class="dropdown card-widgets">
                                  <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                  ' . $admin_super_action_btn . '
                                  <div class="dropdown-divider"></div>
                                  ' . $permission_btn . '
                                  <div class="dropdown-divider"></div>
                                    
                                  ' . $delete_from_workspace . ' ' . $active_btn . '
                                  </div>
                              </div>';
                }
            } else {
                if (is_editor($user_id)) {

                    if (is_admin($row['id'])) {
                        $action = '-';
                    } elseif (is_editor($row['id'])) {
                        $action = '-';
                    } elseif (is_client($row['id'])) {
                        $action = '<div class="dropdown card-widgets">
                                        <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                        ' . $permission_btn . '
                                        <div class="dropdown-divider"></div>
                                        
                                        ' . $delete_from_workspace . ' ' . $active_btn . '
                                        </div>
                                    </div>';
                    } else {
                        $action = '<div class="dropdown card-widgets">
                                      <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                      <div class="dropdown-menu dropdown-menu-right">
                                      ' . $admin_action_btn . '
                                      <div class="dropdown-divider"></div>
                                      ' . $permission_btn . '
                                      <div class="dropdown-divider"></div>
                                        
                                      ' . $delete_from_workspace . '  ' . $active_btn . '
                                      </div>
                                  </div>';
                    }
                }
            }

            if ($row['id'] == $user_id) {

                $action = 'You';
            }

            if (is_client($row['id'])) {
                $projects_count = get_count('id', 'projects', 'FIND_IN_SET(' . $row['id'] . ', client_id)');
                $all_projects = $this->projects_model->get_project($workspace_id, $row['id'], '', $user_type = 'client');
                // $tasks_count = 0;
                $old_proj_id = '';
                $leads_count = get_count('id', 'leads', 'FIND_IN_SET(' . $row['id'] . ', user_id)');
                $tasks_count = get_count('id', 'tasks', 'FIND_IN_SET(' . $row['id'] . ', user_id)');
                // foreach ($all_projects as $all_project) {
                //     // if($old_proj_id != $all_project['id']){
                //     // $old_proj_id = $all_project['id'];/
                //     $tasks_count = $tasks_count + get_count('id', 'tasks', 'user_id=' . $all_project['id']);
                //     // }
                // }
            } else {
                $projects_count = get_count('id', 'projects', 'FIND_IN_SET(' . $row['id'] . ', user_id)');
                $tasks_count = get_count('id', 'tasks', 'FIND_IN_SET(' . $row['id'] . ', user_id)');
                $leads_count = get_count('id', 'leads', 'FIND_IN_SET(' . $row['id'] . ', user_id)');
            }
            $tempRow['assigned'] = '<li class="media">
                        
                        <div class="media-items">
                          
                          <div class="media-item">
                            <div class="media-value badge badge-info">' . $projects_count . '</div>
                            <div class="media-label">' . (!empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects') . '</div>
                          </div>
                          <div class="media-item">
                            <div class="media-value badge badge-info">' . $tasks_count . '</div>
                            <div class="media-label">' . (!empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks') . '</div>
                          </div>
                          <div class="media-item">
                            <div class="media-value badge badge-info">' . $leads_count . '</div>
                            <div class="media-label">' . (!empty($this->lang->line('label_leads')) ? $this->lang->line('label_leads') : 'Leads') . '</div>
                          </div>
                        </div>
                      </li>';

            $tempRow['projects'] = $projects_count;
            $tempRow['tasks'] = $tasks_count;
            $tempRow['company'] = $row['company'];
            $tempRow['phone'] = $row['phone'];
            $tempRow['active'] = ($row['active'] == 1) ? '<div class="badge badge-success">Active</div>' : '<div class="badge badge-danger">Deactive</div>';
            $tempRow['action'] = $action;

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function update_user_lang($workspace_id, $user_id, $lang)
    {

        if ($this->db->query('UPDATE users SET lang="' . $lang . '" WHERE FIND_IN_SET(' . $workspace_id . ',workspace_id) AND id=' . $user_id . ' '))
            return true;
        else
            return false;
    }

    function add_users_ids_to_workspace($workspace_id, $user_id)
    {

        // in this func we are adding users id in the workspace - data format 1,2,3 

        $query = $this->db->query('SELECT user_id FROM workspace WHERE id=' . $workspace_id . ' ');

        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $product_ids = $row['user_id'];
            }
            $user_id = $product_ids . ',' . $user_id;
        }

        if ($this->db->query('UPDATE workspace SET user_id="' . $user_id . '" WHERE id=' . $workspace_id . ' '))
            return true;
        else
            return false;
    }

    function make_user_admin($workspace_id, $user_id)
    {

        // in this func we are adding users id in the workspace - data format 1,2,3 

        $query = $this->db->query('SELECT admin_id FROM workspace WHERE id=' . $workspace_id . ' ');

        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
                $product_ids = $row['admin_id'];
            }
            $admin_id = $product_ids . ',' . $user_id;
        }

        if ($this->db->query('UPDATE workspace SET admin_id="' . $admin_id . '" WHERE id=' . $workspace_id . ' '))
            return true;
        else
            return false;
    }

    function make_user_super_admin($user_id)
    {

        if ($this->db->query('UPDATE users_groups SET group_id=1 WHERE user_id=' . $user_id . ' '))
            return true;
        else
            return false;
    }

    function get_all_super_admins_id($group_id)
    {

        $query = $this->db->query('SELECT user_id FROM users_groups WHERE group_id=' . $group_id . ' ');

        return $query->result_array();
    }

    function remove_user_from_admin($workspace_id, $user_id, $superadmin = '')
    {

        if (!empty($superadmin)) {
            $this->db->query('UPDATE users_groups SET group_id=2 WHERE user_id=' . $user_id . ' ');
        }

        $query = $this->db->query('SELECT admin_id FROM workspace WHERE FIND_IN_SET(' . $user_id . ',`admin_id`) and id =' . $workspace_id . ' ');
        $result = $query->result_array();
        if (!empty($result)) {
            $admin_id = $result[0]['admin_id'];
            $admin_id = preg_replace('\s+/', '', $admin_id);
            $admin_ids = explode(",", $admin_id);
            if (($key = array_search($user_id, $admin_ids)) !== false) {
                unset($admin_ids[$key]);
            }
            $admin_id = implode(",", $admin_ids);
            if ($this->db->query('UPDATE workspace SET admin_id="' . $admin_id . '" WHERE id=' . $workspace_id . ' '))
                return true;
            else
                return false;
        } else {
            return false;
        }
    }

    function remove_user_from_workspace($workspace_id, $user_id)
    {
        $this->remove_user_from_admin($workspace_id, $user_id);
        $query = $this->db->query('SELECT user_id FROM workspace WHERE FIND_IN_SET(' . $user_id . ',`user_id`) and id =' . $workspace_id . ' ');
        // print_r($this->db->last_query());
        // return false;
        $result = $query->result_array();
        if (!empty($result)) {
            $admin_id = $result[0]['user_id'];
            // $admin_id = preg_replace('\s+/', '', $admin_id);
            $admin_ids = explode(",", $admin_id);
            if (($key = array_search($user_id, $admin_ids)) !== false) {
                unset($admin_ids[$key]);
            }
            $admin_id = implode(",", $admin_ids);
            if ($this->db->query('UPDATE workspace SET user_id="' . $admin_id . '" WHERE id=' . $workspace_id . ' ')) {

                $query = $this->db->query('SELECT workspace_id FROM users WHERE FIND_IN_SET(' . $workspace_id . ',`workspace_id`) and id =' . $user_id . ' ');
                $result = $query->result_array();
                if (!empty($result)) {
                    $admin_id = $result[0]['workspace_id'];
                    // $admin_id = preg_replace('\s+/', '', $admin_id);
                    $admin_ids = explode(",", $admin_id);
                    if (($key = array_search($workspace_id, $admin_ids)) !== false) {
                        unset($admin_ids[$key]);
                    }
                    $admin_id = implode(",", $admin_ids);
                    $this->db->query('UPDATE users SET workspace_id="' . $admin_id . '" WHERE id=' . $user_id . ' ');

                    if ($this->db->query('UPDATE users SET workspace_id="' . $admin_id . '" WHERE id=' . $user_id . ' ')) {

                        $query = $this->db->query('SELECT id,user_id FROM projects WHERE FIND_IN_SET(' . $user_id . ',`user_id`) and workspace_id =' . $workspace_id . ' ');
                        $results = $query->result_array();
                        if (!empty($results)) {
                            foreach ($results as $result) {
                                $admin_id = $result['user_id'];
                                $id = $result['id'];
                                $admin_id = preg_replace('\s+/', '', $admin_id);
                                $admin_ids = explode(",", $admin_id);
                                if (($key = array_search($user_id, $admin_ids)) !== false) {
                                    unset($admin_ids[$key]);
                                }
                                $admin_id = implode(",", $admin_ids);
                                $this->db->query('UPDATE projects SET user_id="' . $admin_id . '" WHERE id=' . $id . ' ');
                            }
                        }
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_user($user_id, $groups = [])
    {

        // $user_id is array of users ids 
        $this->db->select('u.*,ug.group_id');
        $this->db->from('users u');
        $this->db->where_in('u.id', $user_id);
        $this->db->join('users_groups ug', 'ug.user_id = u.id', 'left');
        if (!empty($groups)) {
            $this->db->group_start();
            foreach ($groups as $group) {
                $this->db->or_where('ug.group_id', $group);
            }
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_user_array_responce($user_id)
    {

        // $user_id is array of users ids 

        $this->db->from('users');
        $this->db->where_in('id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_user_not_in_workspace($user_id)
    {

        // $user_id is array of users ids 

        $this->db->from('users');
        $this->db->where_not_in('id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }

    function get_user_in_workspace($user_id, $workspace_id)
    {
        $sql = "SELECT `id` FROM `users` where id != $user_id AND FIND_IN_SET($workspace_id, workspace_id)";
        $query = $this->db->query($sql);
        $array1 = $query->result_array();
        $arr = array_map(function ($value) {
            return $value['id'];
        }, $array1);
        return $arr;
    }

    function get_users_by_email($email)
    {

        $this->db->from('users');
        $this->db->where('`email` like "%' . $email . '%" or `first_name` like "%' . $email . '%" or `last_name` like "%' . $email . '%" ');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_users_by_email_for_add($email)
    {

        $this->db->from('users');
        $this->db->where('email', $email);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_user_by_id($user_id, $row = false)
    {

        $this->db->from('users');
        $this->db->where('id', $user_id);
        $query = $this->db->get();
        if ($row) {
            return $query->row();
        } else {
            return $query->result_array();
        }
    }
    function get_user_by_email($email)
    {

        $this->db->from('users');
        $this->db->where('email', $email);
        // $this->db->where('email', $email);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_user_ids($user_id)
    {

        $sql = "SELECT `user_id` FROM `users_groups` where user_id != $user_id";
        $query = $this->db->query($sql);
        $array1 = $query->result_array();
        $arr = array_map(function ($value) {
            return $value['user_id'];
        }, $array1);
        return $arr;
    }

    function get_all_client_ids($group_id)
    {
        $sql = "SELECT user_id FROM users_groups WHERE group_id=" . $group_id;

        $query = $this->db->query($sql);
        $array1 = $query->result_array();
        $arr = array_map(function ($value) {
            return $value['user_id'];
        }, $array1);
        return $arr;
    }

    function get_user_emails($workspace_id)
    {
        $query = $this->db->query("SELECT email FROM users WHERE workspace_id=" . $workspace_id);
        $result = $query->result_array();
        return $result;
    }

    function get_workspcae_admin_ids($workspace_id)
    {
        $query = $this->db->query("SELECT admin_id FROM workspace WHERE id=" . $workspace_id);
        $result = $query->result_array();
        return $result[0]['admin_id'];
    }

    function get_participants_list($table = 'meetings')
    {
        $get = $this->input->get();
        if (isset($get['type']) && !empty($get['type'])) {
            $type = $get['type'];
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
                $where .= " and (u.first_name like '%" . $search . "%' OR u.last_name like '%" . $search . "%' OR u.email like '%" . $search . "%' OR u.workspace_id like '%" . $search . "%')";
            }

            if (isset($get['meeting_id']) &&  !empty($get['meeting_id']) && $table == 'meetings') {
                $table_id = strip_tags($get['meeting_id']);
            }
            if (isset($get['ticket_id']) &&  !empty($get['ticket_id']) && $table == 'tickets') {
                $table_id = strip_tags($get['ticket_id']);
            }
            if (isset($get['type']) &&  !empty($get['type'])) {
                $type = strip_tags($get['type']);
            }
            $field = $type == 'users' ? 'user_ids' : 'client_ids';
            $field1 = $table == 'tickets' ? 'user_id' : 'admin_id';
            $query = $this->db->query("SELECT " . $field . "," . $field1 . " FROM " . $table . " where id =" . $table_id);
            $res = $query->result_array();
            $user_ids = isset($res[0][$field]) && !empty($res[0][$field]) ? $res[0][$field] . "," . $res[0][$field1] : $res[0][$field1];

            $user_ids = explode(',', $user_ids);
            if (is_client($res[0][$field1]) && $type != 'clients') {
                $to_remove = array($res[0][$field1]);
                $user_ids = array_diff($user_ids, $to_remove);
            }
            if (!is_client($res[0][$field1]) && $type == 'clients') {
                $to_remove = array($res[0][$field1]);
                $user_ids = array_diff($user_ids, $to_remove);
            }
            $user_ids = array_unique($user_ids);
            $user_ids = array_values($user_ids);

            $bulkData = array();
            $bulkData['total'] = count($user_ids);
            $rows = array();
            $tempRow = array();
            $action = '';

            for ($i = 0; $i < count($user_ids); $i++) {
                $you = $this->session->userdata('user_id') == $user_ids[$i] ? ' (You) ' : '';
                $query = $this->db->query("SELECT * FROM users  WHERE id =" . $user_ids[$i] . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
                $res = $query->result_array();
                $tempRow['id'] = $res[0]['id'];
                if (is_admin() || is_editor()) {
                    $profile = is_admin($res[0]['id']) ? '' : ' <a href="' . base_url('users/edit-profile/' . $res[0]['id']) . '" target="_blank"><i class="fas fa-edit"></i></a>';
                } else {
                    $profile = '';
                }

                if (!empty($row['profile'])) {
                    $first_name = '<li class="media">
                                
                                <a href="' . base_url('assets/profiles/') . '' . $res[0]['profile'] . '" data-lightbox="images" data-title="' . $res[0]['first_name'] . ' ' . $res[0]['last_name'] . '">
                                <img alt="image" class="mr-3 rounded-circle" width="50" src="' . base_url('assets/profiles/') . '' . $row['profile'] . '">
                                </a>
                                <div class="media-body">
                                  <div class="media-title">' . $res[0]['first_name'] . ' ' . $res[0]['last_name'] . ' ' . $you . ' <a href="' . base_url('users/detail/' . $res[0]['id']) . '" target="_blank"><i class="fas fa-eye"></i></a> ' . $profile . '</div>
                                  <div class="text-job text-muted">' . $res[0]['email'] . '</div>
                                </div>
                              </li>';
                } else {
                    $first_name = '<li class="media">
                                <figure class="avatar mr-2 bg-info text-white" data-initial="' . mb_substr($res[0]['first_name'], 0, 1) . '' . mb_substr($res[0]['last_name'], 0, 1) . '"></figure>
                                <div class="media-body">
                                  <div class="media-title">' . $res[0]['first_name'] . ' ' . $res[0]['last_name'] . ' ' . $you . ' <a href="' . base_url('users/detail/' . $res[0]['id']) . '" target="_blank"><i class="fas fa-eye"></i></a>' . $profile . '</div>
                                  <div class="text-job text-muted">' . $res[0]['email'] . '</div>
                                </div>
                              </li>';
                }

                $tempRow['first_name'] = $first_name;


                if ($this->ion_auth->is_admin($res[0]['id'])) {
                    $tempRow['role'] = '<div class="badge badge-secondary">Admin</div>';
                    $action_btn = '<a class="dropdown-item has-icon make-team-member-alert" data-user_id="' . $res[0]['id'] . '" href="' . base_url('admin/users/remove-user-from-admin/' . $res[0]['id']) . '"><i class="fas fa-plus"></i>' . (!empty($this->lang->line('label_make_team_member')) ? $this->lang->line('label_make_team_member') : 'Make Team Member') . '</a>';
                } elseif (is_editor($res[0]['id'])) {
                    $tempRow['role'] = '<div class="badge badge-secondary">Admin</div>';
                    $action_btn = '<a class="dropdown-item has-icon make-team-member-alert" data-user_id="' . $res[0]['id'] . '" href="' . base_url('admin/users/remove-user-from-admin/' . $res[0]['id']) . '"><i class="fas fa-plus"></i>' . (!empty($this->lang->line('label_make_team_member')) ? $this->lang->line('label_make_team_member') : 'Make Team Member') . '</a>';
                } else {
                    if (!is_client($res[0]['id'])) {
                        $tempRow['role'] = '<div class="badge badge-secondary">Team Member</div>';
                        $action_btn = '<a class="dropdown-item has-icon make-user-admin-alert" data-user_id="' . $res[0]['id'] . '" href="' . base_url('admin/users/make-user-admin/' . $res[0]['id']) . '"><i class="fas fa-plus"></i>' . (!empty($this->lang->line('label_make_admin')) ? $this->lang->line('label_make_admin') : 'Make Admin') . '</a>';
                    } else {
                        $tempRow['role'] = '<div class="badge badge-secondary">Client</div>';
                        $action_btn = '<a class="dropdown-item has-icon make-client-to-team-member-alert" data-user_id="' . $res[0]['id'] . '" href="' . base_url('admin/users/remove-user-from-admin/' . $res[0]['id']) . '"><i class="fas fa-plus"></i>' . (!empty($this->lang->line('label_make_team_member')) ? $this->lang->line('label_make_team_member') : 'Make Team Member') . '</a>';
                    }
                }

                if ($res[0]['active'] == 1) {
                    $active_btn = '<div class="dropdown-divider"></div>
                                              <a class="dropdown-item has-icon deactive-user-alert" data-user_id="' . $res[0]['id'] . '" href="' . base_url('admin/users/deactive/' . $res[0]['id']) . '"><i class="fas fa-user-alt-slash"></i>Deactive</a>';
                } else {
                    $active_btn = '<div class="dropdown-divider"></div>
                                              <a class="dropdown-item has-icon active-user-alert" data-user_id="' . $res[0]['id'] . '" href="' . base_url('admin/users/activate/' . $res[0]['id']) . '"><i class="fas fa-user-check"></i>Active</a>';
                }

                if (is_admin() || is_workspace_admin($this->session->userdata('user_id'), $this->session->userdata('workspace_id'))) {
                    $action = '<div class="dropdown card-widgets">
                                          <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                          <div class="dropdown-menu dropdown-menu-right">
                                          ' . $action_btn . '
                                          <div class="dropdown-divider"></div>
                                              <a class="dropdown-item has-icon delete-user-alert" data-user_id="' . $res[0]['id'] . '" href="' . base_url('admin/users/remove-user-from-workspace/' . $res[0]['id']) . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete_from_workspace')) ? $this->lang->line('label_delete_from_workspace') : 'Delete From Workspace') . '</a>
                                              ' . $active_btn . '
                                          </div>
                                      </div>';
                }

                if ($res[0]['id'] == $this->session->userdata('user_id')) {
                    $action = 'You';
                }
                if (is_client($res[0]['id'])) {
                    $projects_count = get_count('id', 'projects', 'FIND_IN_SET(' . $res[0]['id'] . ', client_id)');
                    $all_projects = $this->projects_model->get_project($this->session->userdata('workspace_id'), $res[0]['id'], '', $user_type = 'client');
                    $tasks_count = 0;
                    $old_proj_id = '';
                    foreach ($all_projects as $all_project) {
                        $tasks_count = $tasks_count + get_count('id', 'tasks', 'project_id=' . $all_project['id']);
                    }
                } else {
                    $projects_count = get_count('id', 'projects', 'FIND_IN_SET(' . $res[0]['id'] . ', user_id)');
                    $tasks_count = get_count('id', 'tasks', 'FIND_IN_SET(' . $res[0]['id'] . ', user_id)');
                }
                $tempRow['assigned'] = '<li class="media">
                                
                                <div class="media-items">
                                  
                                  <div class="media-item">
                                    <div class="media-value badge badge-info">' . $projects_count . '</div>
                                    <div class="media-label">Projects</div>
                                  </div>
                                  <div class="media-item">
                                    <div class="media-value badge badge-info">' . $tasks_count . '</div>
                                    <div class="media-label">Tasks</div>
                                  </div>
                                </div>
                              </li>';

                $tempRow['projects'] = $projects_count;
                $tempRow['tasks'] = $tasks_count;
                $tempRow['company'] = $res[0]['company'];
                $tempRow['phone'] = $res[0]['phone'];
                $tempRow['active'] = ($res[0]['active'] == 1) ? '<div class="badge badge-success">Active</div>' : '<div class="badge badge-danger">Deactive</div>';
                $tempRow['action'] = $action;

                $rows[] = $tempRow;
            }


            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }
    }

    public function modules($user_id = '')
    {
        $this->db->select('ug.*');
        $this->db->from('users_groups ug');
        if (!empty($user_id)) {
            $this->db->where('ug.user_id', $user_id);
        }

        $query = $this->db->get();
        $module = $query->result_array();
        return $module;
    }

    public function validate_forgot_password_link($code)
    {
        $this->db->select('id');
        $this->db->where('forgotten_password_code', $code);
        $query = $this->db->get('users');
        $num = $query->num_rows();
        if ($num === 0) {
            return 0;
        } else {
            return 1;
        }
    }
    function get_user_by_forgot_password_code($code)
    {
        $this->db->select('id,email,first_name,last_name');
        $this->db->where('forgotten_password_code', $code);
        $query = $this->db->get('users');
        return $query->result_array();
    }
    public function update_password($id, $password)
    {
        $ar = array(
            'password' => $password,
            'forgotten_password_code' => '',
        );
        $this->db->where('id', $id);
        return $this->db->update('users', $ar);
    }
    public function get_user_names()
    {
        $this->db->select('first_name');
        $this->db->select('last_name');
        $query = $this->db->get('users');
        return $query->result_array();
    }
}
