<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Screenshot extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'notifications_model', 'projects_model',]);
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
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $projects = $this->projects_model->get_projects($this->session->userdata('workspace_id'));
            $data['projects'] = $projects;
            $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $this->load->view('screenshot', $data);
        }
    }


    public function get_system_settings()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $config = fetch_details("settings", ['type' => "screenshot_set_interval"], '*');
            $response = $config[0];
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        }
    }




    public function upload()
    {
        // Check if the 'Screenshot' directory exists, if not, create it
        $uploadDir = 'assets/screenshot/';
        if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_POST['image'])) {
            $base64Image = $_POST['image'];

            $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
            $base64Image = str_replace(' ', '+', $base64Image);

            $imageData = base64_decode($base64Image);

            $filename = $uploadDir . uniqid() . '.png';

            $result = @file_put_contents($filename, $imageData);

            if ($result !== false) {
                echo 'Screenshot uploaded successfully. Filename: ' . $filename;
            } else {
                echo 'Failed to upload screenshot. Check directory permissions.';
            }
        } else {
            echo 'No screenshot data received.';
        }
    }
}
