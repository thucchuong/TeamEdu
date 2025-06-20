<?php
defined('BASEPATH') or exit('No direct script access allowed');

function is_admin($id = FALSE)
{

    $CI = &get_instance();

    if ($CI->ion_auth->is_admin($id)) {
        return true;
    } else {
        return false;
    }
}

function is_member($id = FALSE)
{
    $CI = &get_instance();

    if ($CI->ion_auth->is_member($id)) {
        return true;
    } else {
        return false;
    }
}

function is_editor($user_id = '')
{
    $CI = &get_instance();
    if (empty($user_id)) {
        $user_id = $CI->session->userdata('user_id');
    }
    $workspace_id = $CI->session->userdata('workspace_id');
    $query = $CI->db->query("SELECT id FROM workspace WHERE id=$workspace_id AND FIND_IN_SET($user_id,admin_id)");
    $config = $query->row_array();
    if (!empty($config)) {
        return true;
    } else {
        return false;
    }
}


function is_client($id = FALSE)
{
    $CI = &get_instance();

    if ($CI->ion_auth->is_client($id)) {
        return true;
    } else {
        return false;
    }
}

function is_rtl($lang = '')
{
    $CI = &get_instance();
    if (empty($lang)) {
        $lang = $CI->session->userdata('site_lang');
    }
    $CI->db->from('languages');
    $CI->db->where(['language' => $lang, 'is_rtl' => 1]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (!empty($config)) {
        return true;
    } else {
        return false;
    }
}

function get_system_fonts()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (isset($config->system_font) && !empty($config->system_font)) {
        $my_fonst = $config->system_font;
    } else {
        $my_fonst = 'default';
    }
    $return_my_fonts = '';
    $my_fonts = json_decode(file_get_contents("assets/fonts/my-fonts.json"));
    if (!empty($my_fonts) && is_array($my_fonts)) {
        foreach ($my_fonts as $my_font) {
            if ($my_font->id == $my_fonst) {
                $return_my_fonts = $my_font;
            }
        }
    } else {
        return false;
    }
    if (!empty($return_my_fonts)) {
        return $return_my_fonts;
    } else {
        return 'default';
    }
}

// to get 'system_configurations' from settings table
function get_system_settings($setting_type)
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => $setting_type]);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = isset($config[0]['data']) ? json_decode($config[0]['data'], 1) : '';
    if (!empty($config)) {
        return $config;
    } else {
        return false;
    }
}

function hide_budget()
{
    $CI = &get_instance();

    if ($CI->ion_auth->is_admin()) {
        return false;
    }

    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (isset($config->hide_budget) && !empty($config->hide_budget) && $config->hide_budget == 1) {
        return true;
    } else {
        return false;
    }
}


function is_leaves_editor($user_id = '')
{
    $CI = &get_instance();
    if (empty($user_id)) {
        $user_id = $CI->session->userdata('user_id');
    }
    $workspace_id = $CI->session->userdata('workspace_id');
    $query = $CI->db->query("SELECT id FROM workspace WHERE id=$workspace_id AND FIND_IN_SET($user_id,leave_editors)");
    $config = $query->row_array();
    if (!empty($config)) {
        return true;
    } else {
        return false;
    }
}


function get_workspace($id = '')
{
    $CI = &get_instance();
    if (empty($id)) {
        $id = $CI->session->userdata('workspace_id');
    }
    $CI->db->from('workspace');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (!empty($config)) {
        return $config[0]['title'];
    }
}

function get_system_version()
{
    $CI = &get_instance();
    $CI->db->from('updates');
    $CI->db->order_by("id", "desc");
    $query = $CI->db->get();
    $config = $query->result_array();
    if (!empty($config)) {
        return $config[0]['version'];
    }
}

function get_languages()
{
    $CI = &get_instance();
    $CI->db->from('languages');
    // $CI->db->where(['type'=>$setting_type]);
    $query = $CI->db->get();
    $config = $query->result_array();
    // $config = json_decode($config[0]['data'],1);
    if (!empty($config)) {
        return $config;
    }
}

function getTimezoneOptions()
{
    $list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();

    $data = $offset = $added = array();
    foreach ($list as $abbr => $info) {
        foreach ($info as $zone) {
            if (
                !empty($zone['timezone_id'])
                and
                !in_array($zone['timezone_id'], $added)
                and
                in_array($zone['timezone_id'], $idents)
            ) {
                $z = new DateTimeZone($zone['timezone_id']);
                $c = new DateTime('', $z);
                $zone['time'] = $c->format('H:i a');
                $offset[] = $zone['offset'] = $z->getOffset($c);
                $data[] = $zone;
                $added[] = $zone['timezone_id'];
            }
        }
    }

    array_multisort($offset, SORT_ASC, $data);

    $i = 0;
    $temp = array();
    foreach ($data as $key => $row) {
        $temp[0] = $row['time'];
        $temp[1] = formatOffset($row['offset']);
        $temp[2] = $row['timezone_id'];
        $options[$i++] = $temp;
    }

    if (!empty($options)) {
        return $options;
    }
}

function formatOffset($offset)
{
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int) abs($hours);
    $minutes = (int) abs($remainder / 60);

    if ($hour == 0 and $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0');
}


function get_compnay_title()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->company_title)) {
        return $config->company_title;
    }
}

function get_compnay_logo()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->full_logo)) {
        return $config->full_logo;
    }
}

function get_currency_symbol()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->currency_symbol)) {
        return $config->currency_symbol;
    } elseif (!empty($config->currency_shortcode)) {
        return $config->currency_shortcode;
    } else {
        return '$';
    }
}

function footer_url()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->footer_url)) {
        return $config->footer_url;
    } else {
        return 'https://www.infinitietech.com/';
    }
}

function get_admin_email()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->email)) {
        return  $config->email;
    }
}
function get_mail_type()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->mail_content_type)) {
        return  $config->mail_content_type;
    }
}

function get_half_logo()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->half_logo)) {
        return  $config->half_logo;
    }
}

function get_full_logo()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    // print_r($config);
    $config = json_decode($config[0]['data']);

    if (!empty($config->full_logo)) {
        return  $config->full_logo;
    }
}

function get_base_url()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->app_url)) {
        return $config->app_url;
    } else {
        return false;
    }
}

function get_count($field, $table, $where = '')
{
    if (!empty($where))
        $where = "where " . $where;

    $CI = &get_instance();
    $query = $CI->db->query("SELECT COUNT(" . $field . ") as total FROM " . $table . " " . $where . " ");
    $res = $query->result_array();
    if (!empty($res)) {
        return $res[0]['total'];
    }
}

function get_smtp_host()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->smtp_host)) {
        return  $config->smtp_host;
    }
}
function get_smtp_pass()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->password)) {
        return  $config->password;
    }
}
function get_smtp_port()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->smtp_port)) {
        return  $config->smtp_port;
    }
}
function get_chat_count()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('user_id');
    $workspace_id = $CI->session->userdata('workspace_id');
    $query = $CI->db->query("SELECT COUNT(id) as total FROM messages WHERE workspace_id=$workspace_id AND to_id=$user_id AND is_read=1 AND type='person'");
    $res = $query->result_array();

    $query1 = $CI->db->query("SELECT COUNT(id) as total FROM chat_group_members WHERE workspace_id=$workspace_id AND user_id=$user_id AND is_read=1");
    $res1 = $query1->result_array();


    return $res[0]['total'] + $res1[0]['total'];
}

function get_chat_theme()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('user_id');
    $CI->db->from('users');
    $CI->db->where(['id' => $user_id]);
    $query = $CI->db->get();
    $config = $query->result_array();

    if (!empty($config[0]['chat_theme'])) {
        return $config[0]['chat_theme'];
    } else {
        return false;
    }
}

function get_user_name()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('user_id');
    $CI->db->from('users');
    $CI->db->where(['id' => $user_id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['first_name']) && !empty($data[0]['last_name'])) {
        return $data[0]['first_name'] . ' ' . $data[0]['last_name'];
    } else {
        return false;
    }
}

function get_project_title($id)
{
    $CI = &get_instance();
    $CI->db->from('projects');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['title'])) {
        return $data[0]['title'];
    } else {
        return false;
    }
}

function get_project_id_by_file_id($file_id)
{
    $CI = &get_instance();
    $CI->db->from('project_media');
    $CI->db->where(['id' => $file_id]);
    $CI->db->where(['type' => 'project']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['type_id'])) {
        return $data[0]['type_id'];
    } else {
        return false;
    }
}

function get_file_name($id)
{
    $CI = &get_instance();
    $CI->db->from('project_media');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['file_name'])) {
        return $data[0]['file_name'];
    } else {
        return false;
    }
}

function get_milestone_title($id)
{
    $CI = &get_instance();
    $CI->db->from('milestones');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['title'])) {
        return $data[0]['title'];
    } else {
        return false;
    }
}

function get_project_id_by_milestone_id($id)
{
    $CI = &get_instance();
    $CI->db->from('milestones');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['project_id'])) {
        return $data[0]['project_id'];
    } else {
        return false;
    }
}

function get_project_id_by_task_id($task_id)
{
    $CI = &get_instance();
    $CI->db->from('tasks');
    $CI->db->where(['id' => $task_id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['project_id'])) {
        return $data[0]['project_id'];
    } else {
        return false;
    }
}

function get_task_title($id)
{
    $CI = &get_instance();
    $CI->db->from('tasks');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['title'])) {
        return $data[0]['title'];
    } else {
        return false;
    }
}

function get_current_version()
{
    $t = &get_instance();
    $version = $t->db->select('max(version) as version')->get('updates')->result_array();
    return $version[0]['version'];
}

function escape_array($array)
{
    $t = &get_instance();
    $posts = array();
    if (!empty($array)) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $posts[$key] = $t->db->escape_str($value);
            }
        } else {
            return $t->db->escape_str($array);
        }
    }
    return $posts;
}

function is_workspace_admin($user_id, $workspace_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select id from workspace where id=" . $workspace_id . " and FIND_IN_SET(" . $user_id . ",admin_id)");
    $count = $query->num_rows();
    if ($count == 1) {
        return true;
    } else {
        return false;
    }
}


function get_admin_id_by_workspace_id($workspace_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select created_by from workspace where id=" . $workspace_id);
    $data = $query->result_array();
    if (!empty($data[0]['created_by'])) {
        return $data[0]['created_by'];
    } else {
        return false;
    }
}

function relativeTime($time)
{
    if (!ctype_digit(strval($time)))
        $time = strtotime($time);
    $d[0] = array(1, "second");
    $d[1] = array(60, "minute");
    $d[2] = array(3600, "hour");
    $d[3] = array(86400, "day");
    $d[4] = array(604800, "week");
    $d[5] = array(2592000, "month");
    $d[6] = array(31104000, "year");

    $w = array();

    $return = "";
    $now = time();
    $diff = ($now - $time);
    $secondsLeft = $diff;

    for ($i = 6; $i > -1; $i--) {
        $w[$i] = intval($secondsLeft / $d[$i][0]);
        $secondsLeft -= ($w[$i] * $d[$i][0]);
        if ($w[$i] != 0) {
            $return .= abs($w[$i]) . " " . $d[$i][1] . (($w[$i] > 1) ? 's' : '') . " ";
        }
    }

    $return .= ($diff > 0) ? "ago" : "left";
    return $return;
}

function get_admin_company_title($id)
{
    $CI = &get_instance();
    $CI->db->select('company');
    $CI->db->from('users');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (!empty($config[0]['company'])) {
        return $config[0]['company'];
    } else {
        $title = get_compnay_title();
        if (!$title) {
            return 'Taskhub';
        } else {
            return $title;
        }
    }
}

function labels($label, $alt = '')
{

    $label = trim($label);
    if (lang('Text.' . $label) != 'Text.' . $label) {
        if (lang('Text.' . $label) == '') {
            return $alt;
        }
        return trim(lang('Text.' . $label));
    } else {
        return trim($alt);
    }
}

function output_escaping($array)
{
    $t = &get_instance();

    if (!empty($array)) {
        if (is_array($array)) {
            $data = array();
            foreach ($array as $key => $value) {
                $data[$key] = stripcslashes($value);
            }
            return $data;
        } else if (is_object($array)) {
            $data = new stdClass();
            foreach ($array as $key => $value) {
                $data->$key = stripcslashes($value);
            }
            return $data;
        } else {
            return stripcslashes($array);
        }
    }
}

function slugify($text, $divider = '-')
{
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, $divider);
    $text = preg_replace('~-+~', $divider, $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

function _pre1($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    return false;
    exit();
}

function fetch_details($table, $where = NULL, $fields = '*', $limit = '', $offset = '', $sort = '', $order = '', $where_in_key = '', $where_in_value = '')
{
    $t = &get_instance();
    $t->db->select($fields);
    if (!empty($where)) {
        $t->db->where($where);
    }

    if (!empty($where_in_key) && !empty($where_in_value)) {
        $t->db->where_in($where_in_key, $where_in_value);
    }

    if (!empty($limit)) {
        $t->db->limit($limit);
    }

    if (!empty($offset)) {
        $t->db->offset($offset);
    }

    if (!empty($order) && !empty($sort)) {
        $t->db->order_by($sort, $order);
    }

    $res = $t->db->get($table)->result_array();
    return $res;
}

function get_permissions($user_id)
{

    $CI = &get_instance();
    $role = $CI->session->userdata('role');

    /* find the admin id from created_by column of the workspace */
    $workspace_id = $CI->session->userdata('workspace_id');
    if (empty($user_id)) {
        $user_id = $CI->session->userdata('user_id');
    }

    $sql = ($role != "admin") ? "select * from workspace where id=" . $workspace_id . " and FIND_IN_SET(" . $user_id . ",user_id)" : "select * from workspace where `created_by` = $user_id";

    $query = $CI->db->query($sql);
    $admin = $query->result_array();
    $admin_id = (isset($admin[0]['created_by']) && !empty($admin[0]['created_by'])) ? $admin[0]['created_by'] : $user_id;

    /* select special permissions if any */
    $query = $CI->db->select('p.permissions as special_permissions')
        ->from('permissions p')
        ->where('p.user_id', $user_id)
        ->get();
    $permissions = $query->result_array();

    $query = $CI->db->select('g.name as group_name,ug.user_id,ug.member_permissions,ug.client_permissions')
        ->from('users_groups ug')
        ->join('groups g', 'g.id = ug.group_id', 'left')
        ->where('ug.user_id', $admin_id)
        ->get();
    $group = $query->result_array();

    $group[0]['permissions'] = [];

    if (!empty($group[0]['member_permissions']) && $role == 'members') {
        $group[0]['permissions'] = $group[0]['member_permissions'];
    }

    if (!empty($group[0]['client_permissions']) && $role == 'clients') {
        $group[0]['permissions'] = $group[0]['client_permissions'];
    }

    if (isset($permissions[0]['special_permissions']) && (empty($permissions[0]['special_permissions']) || $permissions[0]['special_permissions'] != null)) {
        $group[0]['permissions'] = $permissions[0]['special_permissions'];
    }

    /* setting the group name of logged in user to check the permissions */
    $group[0]['group_name'] = $_SESSION['role'];

    return $group;
}

function check_permissions($module, $permission_type, $user_id = "", $show_flash_message = false)
{
    $CI = &get_instance();
    if (empty($user_id)) {
        $user_id = (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : "";
    }

    if (!empty($user_id)) {
        $permissions = get_permissions($user_id);
        /* if he is an admin he has all permissions */
        if ($permissions[0]['group_name'] == "admin") {
            return true; /* true means user can access */
        }

        $permissions[0]['permissions'] = (!empty($permissions[0]['permissions'])) ? json_decode($permissions[0]['permissions'], 1) : "";

        if (empty($permissions[0]['permissions'])) {
            /* means he has all the permissions */
            return true;
        }

        if (isset($permissions[0]['permissions'][$module][$permission_type]) && ($permissions[0]['permissions'][$module][$permission_type] == "on" || $permissions[0]['permissions'][$module][$permission_type] == 1)) {
            return true;
        } else {
            if ($show_flash_message) {
                $CI->session->set_flashdata('message', PERMISSION_ERROR_MESSAGE);
                $CI->session->set_flashdata('message_type', 'error');
            }
            return false;
        }
    } else {
        $CI->session->set_flashdata('message', PERMISSION_ERROR_MESSAGE);
        $CI->session->set_flashdata('message_type', 'error');
        return false;
    }
}
function response($message, $error = true,  $additional_data = [], $status = 200, $show_flash_message = true)

{
    $t = &get_instance();
    $response['error'] = $error;
    $response['csrfName'] = $t->security->get_csrf_token_name();
    $response['csrfHash'] = $t->security->get_csrf_hash();
    $response['message'] = $message;
    $response['data'] = $additional_data;
    if ($show_flash_message) {
        $t->session->set_flashdata('message', $message);
        $t->session->set_flashdata('message_type', (($error == true) ? 'error' : 'success'));
    }
    return $t->output
        ->set_content_type('application/json')
        ->set_status_header($status)
        ->set_output(json_encode($response));
}

function workspace_count()
{
    $t = &get_instance();
    $user_id = $t->session->userdata('user_id');
    $workspace_count = get_count('id', 'workspace', 'FIND_IN_SET(' . $user_id . ', user_id)');
    return $workspace_count;
}

function workspace_list()
{
    $t = &get_instance();
    $t->db->select('*');
    $t->db->from('workspace');
    $t->db->limit(5);
    $query = $t->db->get();
    $workspace_list = $query->result_array();
    return  $workspace_list;
}

function duplicate_row($table, $id, $related_data = [])
{
    $t = &get_instance();
    $t->db->select('*');
    $t->db->from($table);
    $t->db->where('id', $id);
    $query = $t->db->get();
    $duplicate_data = $query->row_array();
    if ($duplicate_data) {
        unset($duplicate_data['id']);
        $t->db->insert($table, $duplicate_data);
        $insert_id = $t->db->insert_id();
        if ($insert_id) {
            // Check if related data is provided
            if (!empty($related_data)) {
                /* related table's foreign key for the main table's duplication record */
                $foreign_keys = [
                    'tasks' => 'project_id',
                    'milestones' => 'project_id',
                    'project_media' => 'type_id',
                    'estimate_items' => 'estimate_id',
                    'invoice_items' => 'invoice_id'
                ];
                foreach ($related_data as $related_table => $related_values) {
                    foreach ($related_values as $related_row) {
                        // Generate a new primary key value
                        $related_row['id'] = null;
                        $related_row[$foreign_keys[$related_table]] = $insert_id;
                        $t->db->insert($related_table, $related_row);
                    }
                }
            }
            return $insert_id;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function send_notification($fcmMsg, $registrationIDs_chunks)
{
    $fcmFields = [];
    foreach ($registrationIDs_chunks as $registrationIDs) {
        $fcmFields = array(
            'registration_ids' => $registrationIDs,  // expects an array of ids
            'priority' => 'high',
            'notification' => $fcmMsg,
            'data' => $fcmMsg,
        );
        $fcm_key = get_system_settings('web_fcm_settings');

        $fcm_key = !empty($fcm_key) ? json_decode($fcm_key[0]['data']) : '';

        $fcm_key = !empty($fcm_key->fcm_server_key) ? $fcm_key->fcm_server_key : '';
        $headers = array(
            $headers[] = "Authorization: key = " . $fcm_key,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        curl_close($ch);
    }
    print_r($fcmFields);
    return $fcmFields;
}
function ordinal($number)
{
    $suffix = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if (($number % 100) >= 11 && ($number % 100) <= 13) {
        $ordinal = $number . 'th';
    } else {
        $ordinal = $number . $suffix[$number % 10];
    }
    return $ordinal;
}

function search_data($table, $searchTerm)
{
    $t = &get_instance();
    $offset = 0;
    $limit = 5;
    $sort = 'id';
    $order = 'ASC';
    $where = '';
    $get = $t->input->get();

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
    if (isset($get['search']) && !empty($get['search'])) {
        $search = strip_tags($get['search']);
    }
    $workspace_id = $t->session->userdata('workspace_id');
    $searches = [];

    // Using an associative array
    $table_data = array(
        'articles' => 'id,title,slug',
        'contracts' => 'id,title',
        'events' => 'id,title',
        'items' => 'id,title',
        'leads' => 'id,title',
        'meetings' => 'id,title',
        'milestones' => 'id,title',
        'notes' => 'id,title',
        'projects' => 'id,title',
        'tasks' => 'id,title',
        'taxes' => 'id,title',
        'unit' => 'id,title',
        'expenses' => 'id,title'
    );

    if (array_key_exists($table, $table_data)) {
        $where = "workspace_id = " . $workspace_id . " AND (title LIKE '%" . $search . "%')";
        $searches = fetch_details($table, $where, $table_data[$table], $limit, $offset, $sort, $order);
    }

    $response['error'] = (!empty($searches)) ? false : true;
    $response['message'] = (!empty($searches)) ? "success" : "No search found";
    $response['searches'] = (!empty($searches)) ? $searches : [];

    return $response;
}


function chat_send_notification($fcmMsg, $registrationIDs_chunks)
{

    $fcmFields = [];
    foreach ($registrationIDs_chunks as $registrationIDs) {
        $fcmFields = array(
            'priority' => 'high',
            // 'notification' => $fcmMsg,
            'data' => $fcmMsg,
        );
        if (is_array($registrationIDs)) {
            $fcmFields['registration_ids'] = $registrationIDs;  // expects an array of ids
        } else {
            $fcmFields['to'] = $registrationIDs;  // expects an array of ids
        }
        $ch = curl_init();
        $fcm_key = get_system_settings('web_fcm_settings');
        $fcm_key = !empty($fcm_key['fcm_server_key']) ? $fcm_key['fcm_server_key'] : '';
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = array();
        $headers[] = "Authorization: key = " . $fcm_key;
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        curl_close($ch);
    }
    return $result;
}

// function chat_list($workspace_id, $user_id)
// {
//     $CI = &get_instance();
//     $query = $CI->db->query('
//         SELECT
//             CONCAT_WS(",", p.user_id, p.client_id, t.user_id) AS all_common_ids
//         FROM
//             users u
//         LEFT JOIN projects p ON u.id = p.user_id OR u.id = p.client_id
//         LEFT JOIN tasks t ON u.id = t.user_id
//         WHERE
//             u.workspace_id = ' . $workspace_id . ' AND FIND_IN_SET(' . $user_id . ', u.id) > 0
//         GROUP BY
//         p.user_id, p.client_id, t.user_id
//     ');
//     $chats_list = $query->result_array();
//     return $chats_list;
// }
function chat_list($workspace_id, $user_id)
{
    $CI = &get_instance();
    $query = $CI->db->query('
        SELECT
            CONCAT_WS(",", m1.id, 1.from_id, m1.to_id,m1.is_read, m1.message,m1.type,m1.media,u.id,u.usernameu.email, u.mobile, u.image, u.active, u.last_online) AS all_common_ids
        FROM
        messages m1
        LEFT JOIN users u ON (m1.from_id= p.user_id OR u.id = p.client_id
        LEFT JOIN tasks t ON u.id = t.user_id
        WHERE
            u.workspace_id = ' . $workspace_id . ' AND FIND_IN_SET(' . $user_id . ', u.id) > 0
        GROUP BY
        p.user_id, p.client_id, t.user_id
    ');
    $chats_list = $query->result_array();
    return $chats_list;
}
function get_chat_history($from_id, $limit = 5, $offset = 0, $from_user = false)
{
    $CI = &get_instance();
    $members = $CI->db->select('CONCAT_WS(",", m1.id, m1.from_id, m1.to_id, m1.is_read, m1.message, m1.type, m1.media, u.id, u.username, u.email, u.phone, u.profile, u.active, u.last_online) AS all_common_ids')
        ->from('messages m1')
        ->join('users u', '(m1.from_id = ' . $from_id . ' AND u.id = m1.to_id) OR (m1.to_id = ' . $from_id . ' AND u.id = m1.from_id)', 'inner')
        ->where('m1.id = (SELECT MAX(m2.id) FROM messages m2 WHERE (m2.from_id = ' . $from_id . ' OR m2.to_id = ' . $from_id . ') AND ((m2.from_id = m1.from_id AND m2.to_id = m1.to_id) OR (m2.from_id = m1.to_id AND m2.to_id = m1.from_id)))')
        ->order_by('m1.id', 'desc')
        ->limit($limit, $offset)
        ->get()
        ->result_array();

    return $members;
}


function add_time_sheet_project()
{
    $CI = &get_instance();
    $query = $CI->db->query('
        SELECT
            CONCAT_WS(",", p.user_id, p.client_id, t.user_id) AS all_common_ids
        FROM
            users u
        LEFT JOIN projects p ON u.id = p.user_id OR u.id = p.client_id
        LEFT JOIN tasks t ON u.id = t.user_id
        WHERE
            u.workspace_id = ' . $workspace_id . ' AND FIND_IN_SET(' . $user_id . ', u.id) > 0
        GROUP BY
        p.user_id, p.client_id, t.user_id
    ');
    $chats_list = $query->result_array();
    return $chats_list;
}

function curl($url, $method = 'GET', $data = [], $authorization = "")
{
    $ch = curl_init();
    $curl_options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
        )
    );

    if (!empty($authorization)) {
        $curl_options['CURLOPT_HTTPHEADER'][] = $authorization;
    }

    if (strtolower($method) == 'post') {
        $curl_options[CURLOPT_POST] = 1;
        $curl_options[CURLOPT_POSTFIELDS] = http_build_query($data);
    } else {
        $curl_options[CURLOPT_CUSTOMREQUEST] = 'GET';
    }
    curl_setopt_array($ch, $curl_options);

    $result = array(
        'body' => json_decode(curl_exec($ch), true),
        'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
    );
    return $result;
}
