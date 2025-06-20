<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Search_data extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }
    public function index()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $selectedTable = array(
                'projects', 'tasks', 'meetings', 'leads', 'contracts', 'notes', 'articles',  'events', 'items',
                'milestones', 'taxes', 'unit', 'expenses'
            );

            $searchTerm = $this->input->get('search');
            $searchResults = array();

            foreach ($selectedTable as $table) {
                $result = search_data($table, $searchTerm);
                $searchResults[$table] = $result['searches'];
            }
            $response['searchResults'] = $searchResults;
            $this->output->set_header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}
