<?php
error_reporting(0);
ini_set('display_errors', 0);
defined('BASEPATH') or exit('No direct script access allowed');

class Forgot_password extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'form']);
        $this->load->library('session');
        $this->load->model('users_model');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }
    public function index()
    {
    }
    public function send_mail()
    {
     //   print_r($_POST);

        $this->form_validation->set_rules('identity', "Email", 'required|xss_clean');
        if ($this->form_validation->run() === FALSE) {
            if (validation_errors()) {
                $response['error'] = true;
                $response['message'] = validation_errors();
                echo json_encode($response);
                return false;
                exit();
            }
        }
        $email = $this->input->post('identity', true);
        $this->db->select('id');
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        $num = $query->num_rows();
        if ($num <= 0) {
            $response['error'] = true;
            $response['message'] = 'Email Does Not Exists';
            echo json_encode($response);
            return false;
            exit();
        } else {
            $this->db->select('first_name,last_name');
            $this->db->where('email', $email);
            $query = $this->db->get('users');
            $result = $query->result_array();
            $temp = mt_rand(100000, 999999);
            $link = md5($temp);

            $full_link = base_url('forgot_password/reset_password/') . $link;
            $this->email->clear(TRUE);
            $this->load->library('email');

            $config = $this->config->item('ion_auth')['email_config'];
            try {
                if (isset($config['smtp_user']) && !empty($config['smtp_user'])) {
                    $this->email->initialize($config);
                    $this->email->set_newline("\r\n");
                    $from_email = get_admin_email();
                    $this->email->from($from_email, get_compnay_title());
                    $this->email->to($email);
                    $data['logo'] = base_url('assets/icons/') . get_compnay_logo();
                    $data['user_name'] = $result[0]['first_name'] . " " . $result[0]['last_name'];
                    $data['full_link'] = $full_link;
                    $data['company_title'] = get_compnay_title();
                    $data['type'] = 'forgot_password';
                    $email_templates = fetch_details('email_templates',['type' => "forgot_password"]);
                    $email_user_name = '{user_name}';
                    $email_full_link = '{full_link}';
                    $email_company_title = '{company_title}';

                    $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);

                    $message = (!empty($email_templates)) ? str_replace(array($email_user_name, $email_full_link, $email_company_title), array($data['user_name'], $data['full_link'], $data['company_title']), $hashtag)  : "Dear, <b>" . $data['user_name'] . "</b> here is your password reset link <a href='" . $data['full_link'] . "' target='_blank'>" . $data['full_link'] . "</a> click it to proceed further.<br>Thanks & regards <b>" . $data['company_title'] . "</b>";

                    $data['message'] = output_escaping(trim($message, '"'));
                    $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject')); 
                    $this->email->message($this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_forgot_password', 'ion_auth'), $data, true));
                    if (!$this->email->send()) {
                        $response['error'] = true;
                        $response['message'] = "Opps!!Something Went Wrong PleaseTry Agian Later Sorry For Inconvenience";
                        echo json_encode($response);
                        return false;
                        exit();
                    } else {
                        $ar = array(
                            'forgotten_password_code' => $link,

                        );
                        $this->db->where('email', $email);
                        $this->db->update('users', $ar);

                        $response['error'] = false;
                        $response['message'] = "Password Reset Link Sent Successfully Check Your Email";
                        echo json_encode($response);

                        return false;
                        exit();
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Please configure email settings first";
                    echo json_encode($response);

                    return false;
                    exit();
                }
            } catch (Exception $e) {
                $response['error'] = true;
                echo json_encode($response);
            }
        }
    }
    public function reset_password()
    {
        $data['code'] = !empty($this->uri->segment(3)) ? $this->uri->segment(3) : '';
        $this->load->view('reset-password', $data);
    }
    public function recover_password()
    {

        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
        $this->form_validation->set_rules('confirm_password', 'Confirm password', 'required|matches[password]|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            if (validation_errors()) {
                $response['error'] = true;
                $response['message'] = validation_errors();
                echo json_encode($response);
                return false;
                exit();
            }
        }
        if (empty($_POST['code'])) {
            $response['error'] = true;
            $response['message'] = 'Either link is invalid or expired';
            echo json_encode($response);
            return false;
            exit();
        }
        if (!$this->users_model->validate_forgot_password_link($_POST['code'])) {
            $response['error'] = true;
            $response['message'] = 'Either link is invalid or expired';
            echo json_encode($response);
            return false;
            exit();
        } else {
            $params = [
                'cost' => 12
            ];
            $password = $_POST['password'];
            $password_hash = password_hash($password, PASSWORD_BCRYPT, $params);

            $this->email->clear(TRUE);
            $this->load->library('email');
            $config = $this->config->item('ion_auth')['email_config'];
            try {
                if (isset($config['smtp_user']) && !empty($config['smtp_user'])) {
                    $this->email->initialize($config);
                    $this->email->set_newline("\r\n");
                    $from_email = get_admin_email();
                    $this->email->from($from_email, get_compnay_title());


                    $user_details = $this->users_model->get_user_by_forgot_password_code($_POST['code']);
                    $full_name = $user_details[0]['first_name'] . ' ' . $user_details[0]['last_name'];
                    $email = $user_details[0]['email'];
                    // return false;
                    $this->email->to($email); // change it to yours
                    $this->email->subject(get_compnay_title() . " Recover Password");
                    $data['type'] = 'reset_password_ack';
                    $data['logo'] = base_url('assets/icons/') . get_compnay_logo();
                    $data['user_name'] = $full_name;
                    $data['password'] = $password;
                    $data['company_title'] = get_compnay_title();
                    $email_templates = fetch_details('email_templates',['type' => "reset_password"]);
                    $email_user_name = '{user_name}';
                    $email_password = '{password}';
                    $email_company_title = '{company_title}';

                    $string = json_encode($email_templates[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);

                    $message = (!empty($email_templates)) ? str_replace(array($email_user_name, $email_password, $email_company_title), array($data['user_name'], $data['password'], $data['company_title']), $hashtag)  : "Dear, <b>" . $data['user_name'] . "</b> your account password changes successfully. Here is new password <b>" . $data['password'] . "</b> please take note of it.<br>Thanks & regards <b>" . $data['company_title'] . "</b>";

                    $data['message'] = output_escaping(trim($message, '"'));
                    $this->email->message($this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_forgot_password', 'ion_auth'), $data, true));
                    if (!$this->email->send()) {
                        $response['error'] = true;
                        $response['message'] = "Opps!!Something Went Wrong PleaseTry Agian Later Sorry For Inconvenience";
                        echo json_encode($response);
                        return false;
                        exit();
                    } else {
                        if ($this->users_model->update_password($user_details[0]['id'], $password_hash)) {
                            $this->session->set_flashdata('message', 'Password Changed Successfully');
                            $this->session->set_flashdata('message_type', 'success');
                            $response['error'] = false;
                            $response['message'] = "Password Changed Successfully";
                            echo json_encode($response);
                            return false;
                            exit();
                        } else {
                            $response['error'] = true;
                            $response['message'] = "Some Thing Went Wrong Please Try Again!";
                            echo json_encode($response);

                            return false;
                            exit();
                        }
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Please configure email settings first!";
                    echo json_encode($response);

                    return false;
                    exit();
                }
            } catch (Exception $e) {
                $response['error'] = true;
                echo json_encode($response);
            }
        }
    }
    public function mail_config()
    {
        $mail_config['smtp_host'] = get_smtp_host();
        $mail_config['smtp_port'] = get_smtp_port();
        $mail_config['smtp_user'] = get_admin_email();
        $mail_config['smtp_auth'] = TRUE;
        $mail_config['smtp_pass'] = get_smtp_pass();
        $mail_config['smtp_crypto'] = 'tls';
        $mail_config['protocol'] = 'smtp';
        $mail_config['mailtype'] = get_mail_type();
        $mail_config['send_multipart'] = FALSE;
        $mail_config['charset'] = 'utf-8';
        $mail_config['wordwrap'] = TRUE;
        return $mail_config;
    }
}
