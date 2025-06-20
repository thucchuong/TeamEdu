<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_code extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['settings_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (is_admin()) {
                $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
                $this->load->view('purchase-code', $data);
            } else {
                $this->session->set_flashdata('message', 'You are not authorized to view this page!');
                $this->session->set_flashdata('message_type', 'error');
                redirect('home', 'refresh');
            }
        }
    }

    public function validator()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {


            if ((isset($_POST['purchase_code']) && !empty($_POST['purchase_code']))) {
                $purchase_code = $this->input->post("purchase_code", true);
                $app_url = "https://wrteam.in/validator/home/validator_new?purchase_code=$purchase_code&domain_url=" . base_url() . "&item_id=" . ITEM_CODE;

                $app_result = curl($app_url);



                if (isset($app_result['body']) && !empty($app_result['body'])) {
                    if (isset($app_result['body']['error']) && $app_result['body']['error'] == 0) {
                        $doctor_brown = [];
                        $doctor_brown = get_system_settings('doctor_brown');

                       
                        if (empty($doctor_brown)) {

                            $doctor_brown = [];

                            $doctor_brown['code_bravo'] = $app_result["body"]["purchase_code"];
                            $doctor_brown['time_check'] = $app_result["body"]["token"];
                            $doctor_brown['code_adam'] = $app_result["body"]["username"];
                            $doctor_brown['dr_firestone'] = $app_result["body"]["item_id"];

                           
                            $data['type'] = "doctor_brown";
                            $data['data'] = json_encode($doctor_brown);

                            $this->db->insert('settings', $data);
                            // $this->settings_model->save_settings($setting_type, $data);
                        }
                    }
                } else {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = "Somthing Went wrong. Please contact Super admin.";
                    print_r(json_encode($this->response));
                }



                if (isset($app_result['body']['error']) && $app_result['body']['error'] == 0 && !empty($_POST['purchase_code'])) {
                    $this->response['error'] = false;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = $app_result['body']['message'];
                    print_r(json_encode($this->response));
                }

                if (isset($app_result['body']['error']) && $app_result['body']['error'] != 0 && !empty($_POST['purchase_code'])) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] = $app_result['body']['message'];
                    print_r(json_encode($this->response));
                }
            }
        } else {
            redirect('home', 'refresh');
        }
    }
}
