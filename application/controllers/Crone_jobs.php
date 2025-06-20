<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Crone_jobs extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->model(['notifications_model']);
        $this->load->helper(['url', 'language', 'form', 'file']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function projects_deadline_reminder()
    {
        $this->db->select('id,title,user_id,client_id');
        $this->db->where('end_date', date("Y-m-d"));
        $query = $this->db->get('projects');
        $result = $query->result_array();
       
        if (isset($result[0]) && !empty($result[0])) {
            foreach ($result as $res) {
                $ids = $res['user_id'] . ',' . $res['client_id'];
                $ids = explode(",", $ids);
                $ids = array_unique($ids);
                $project_title = $res['title'];
                $project_id = $res['id'];
                foreach ($ids as $id) {
                    $this->db->select('first_name,last_name,email');
                    $this->db->where('id', $id);
                    $query = $this->db->get('users');
                    $result = $query->result_array();
                    $email_templates = fetch_details('email_templates', ['type' => "projects_deadline_reminder"]);

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));

                    $subject = isset($title) ? $title : "Project deadline reminder";

                    $email_first_name = '{first_name}';
                    $email_last_name = '{last_name}';
                    $email_project_title = '{project_title}';
                    $email_project_id = '{project_id}';
                    $email_get_compnay_title = '{get_compnay_title}';

                    $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);

                    if (!empty($result[0]['email'])) {
                        $to_email = $result[0]['email'];
                        $message = (!empty($email_templates)) ? str_replace(array($email_first_name, $email_last_name, $email_project_title, $email_project_id, $email_get_compnay_title), array($result[0]['first_name'], $result[0]['last_name'], $project_title, $project_id, get_compnay_title()), $hashtag)  : "<p>Hello Dear <b>" . $result[0]['first_name'] . " " . $result[0]['last_name'] . "</b>, today is the deadline of the project <b>" . $project_title . "</b>, ID <b>#" . $project_id . "</b> assigned to you please take note of it. <a href=" . base_url('projects/details/' . $project_id) . " target='_blank'>Click here</a> to see more</p>
                        <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";
                        $data['message'] = output_escaping(trim($message, '"'));
                        $this->send_mail($to_email, $subject, $message);
                    }
                }
            }
        }
    }
    public function tasks_deadline_reminder()
    {
        $this->db->select('id,title,user_id');
        $this->db->where('due_date', date("Y-m-d"));
        $query = $this->db->get('tasks');
        $result = $query->result_array();
        
        if (isset($result[0]) && !empty($result[0])) {
            foreach ($result as $res) {
                $ids = $res['user_id'];
                $ids = explode(",", $ids);
                $ids = array_unique($ids);
                $task_title = $res['title'];
                $task_id = $res['id'];
                foreach ($ids as $id) {
                    $this->db->select('first_name,last_name,email');
                    $this->db->where('id', $id);
                    $query = $this->db->get('users');
                    $result = $query->result_array();

                    $email_templates = fetch_details('email_templates', ['type' => "tasks_deadline_reminder"]);

                    $string1 = json_encode($email_templates[0]['subject'], JSON_UNESCAPED_UNICODE);
                    $hashtag1 = html_entity_decode($string1);
                    $title = output_escaping(trim($hashtag1, '"'));

                    $subject = isset($title) ? $title : "Task deadline reminder";
                   

                    $email_first_name = '{first_name}';
                    $email_last_name = '{last_name}';
                    $email_task_title = '{task_title}';
                    $email_task_id = '{task_id}';
                    $email_get_compnay_title = '{get_compnay_title}';

                    $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);

                    if (!empty($result[0]['email'])) {
                        $to_email = $result[0]['email'];
                        $message = (!empty($email_templates)) ? str_replace(array($email_first_name, $email_last_name, $email_task_title, $email_task_id, $email_get_compnay_title), array($result[0]['first_name'], $result[0]['last_name'], $task_title, $task_id, get_compnay_title()), $hashtag)  :  "<p>Hello Dear <b>" . $result[0]['first_name'] . " " . $result[0]['last_name'] . "</b>, today is the deadline of the task <b>" . $task_title . "</b>, ID <b>#" . $task_id . "</b> assigned to you please take note of it. <a href=" . base_url('projects/tasks/' . $task_id) . " target='_blank'>Click here</a> to see more</p>
                        <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";
                        $data['message'] = output_escaping(trim($message, '"'));
                        $this->send_mail($to_email, $subject, $message);
                    }
                }
            }
        }
    }

    public function today_birthday_reminder()
    {
        $today = date('m-d');
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("DATE_FORMAT(date_of_birth, '%m-%d') =", $today);
        $query = $this->db->get();
        $result = $query->result_array();
        $subject = 'Happy Birthday';

        if (!empty($result)) {
            // Check if there are users with today's birthday
            foreach ($result as $res) {
                $user_ids = explode(",", $res['id']);
                $user_ids = array_map('trim', $user_ids);
                $user_ids = array_unique($user_ids);

                foreach ($user_ids as $user_id) {
                    $this->db->select('first_name,last_name,email');
                    $this->db->where('id', $user_id);
                    $query = $this->db->get('users');
                    $user_data = $query->row_array();

                    if (!empty($user_data['email'])) {
                        $to_email = $user_data['email'];
                        $message = "<p> Happy Birthday, <b>" . $user_data['first_name'] . " " . $user_data['last_name'] . "</b>! May your day be filled with joy, laughter, and unforgettable moments. Here's to another year of success and happiness.</p>
                        <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";

                        $message = trim($message);
                        $this->send_mail($to_email, $subject, $message);
                        $title = "Happy Birthday";
                        $notification_data = array(
                            'user_id' => $this->session->userdata('user_id'),
                            'workspace_id' => $this->session->userdata('workspace_id'),
                            'title' => $title,
                            'type' => 'birthday',
                            'notification' =>  "Happy Birthday, <b>" . $user_data['first_name'] . " " . $user_data['last_name'] . "</b>! May your day be filled with joy, laughter, and unforgettable moments. Here's to another year of success and happiness.",
                        );
                        $this->notifications_model->send_notifications($notification_data);
                    }
                }
            }
        }
    }

    public function work_anniversary_reminder()
    {
        // Load necessary libraries and models
        $today = date('m-d');
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("DATE_FORMAT(date_of_joining, '%m-%d') =", $today);
        $query = $this->db->get();
        $result = $query->result_array();
        $subject = "Work Anniversary";
        if (!empty($result)) {
            // Check if there are users with today's Work Anniversary
            foreach ($result as $res) {
                $user_ids = explode(",", $res['id']);
                $user_ids = array_map('trim', $user_ids);
                $user_ids = array_unique($user_ids);

                foreach ($user_ids as $user_id) {
                    $this->db->select('first_name,last_name,email,date_of_joining');
                    $this->db->where('id', $user_id);
                    $query = $this->db->get('users');
                    $user_data = $query->row_array();
                    $today = date('j F');
                    $date_of_joining = strtotime($user_data['date_of_joining']);
                    $today_date = date('Y-m-d');
                    $diff_in_seconds = strtotime($today_date) - $date_of_joining;
                    $diff_in_years = floor($diff_in_seconds / (365 * 24 * 60 * 60));

                    $year = ordinal($diff_in_years);

                    if (!empty($user_data['email'])) {
                        $to_email = $user_data['email'];
                        $message = "<p>Dear" . " " . $user_data['first_name'] . " " . $user_data['last_name'] . " " . ", Time truly flies when you're doing what you love, and today marks another incredible milestone in your journey with  <b>" . get_compnay_title() . "</b>. It's with great joy and appreciation that we celebrate your " . $year . " work anniversary! </p>
                        <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";

                        $message = trim($message);
                        $this->send_mail($to_email, $subject, $message);
                        $title = "Celebrating" . " " . $user_data['first_name'] . " " . $user_data['last_name'] . " " . " Work Anniversary";
                        $notification_data = array(
                            'user_id' => $this->session->userdata('user_id'),
                            'workspace_id' => $this->session->userdata('workspace_id'),
                            'title' => $title,
                            'type' => 'birthday',
                            'notification' =>  "<p>Dear" . " " . $user_data['first_name'] . " " . $user_data['last_name'] . " " . ", Time truly flies when you're doing what you love, and today marks another incredible milestone in your journey with  <b>" . get_compnay_title() . "</b>. It's with great joy and appreciation that we celebrate your " . $year . " work anniversary! </p>",
                        );
                        $this->notifications_model->send_notifications($notification_data);
                    }
                }
            }
        }
    }

    // public function remove_images()
    // {
    //     // Convert URL to server path
    //     $server_path = realpath(FCPATH . 'assets/screenshot') . DIRECTORY_SEPARATOR;
    //     // Check if the folder exists
    //     if (is_dir($server_path)) {
    //         // Get all files in the folder
    //         $files = get_filenames($server_path);
    //         // Loop through each file and delete it
    //         foreach ($files as $file) {
    //             unlink($server_path . $file);
    //         }
    //         echo 'All images removed successfully.';
    //     } else {
    //         echo 'Image folder not found.';
    //     }
    // }
    public function send_mail($to, $subject, $message)
    {
        try {
            $this->email->clear(TRUE);
            $config = $this->config->item('ion_auth')['email_config'];
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $from_email = get_admin_email();
            $this->email->from($from_email, get_compnay_title());
            $this->email->to($to);
            $this->email->subject($subject);
            $data['logo'] = base_url('assets/icons/') . get_full_logo();
            $data['company_title'] = get_compnay_title();
            $data['heading'] = "<h1>" . $subject . "</h1>";
            $data['message'] = $message;
            $this->email->message($this->load->view('project-task-deadline-reminder-email-template.php', $data, true));
            $this->email->send();
        } catch (Exception $e) {
            $response['error'] = true;
            echo json_encode($response);
        }
    }
}
