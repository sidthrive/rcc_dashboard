<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
    This controller class is the main controller for generate report function
    This class contains methods to view and download report in the form of excel and pdf files
*/

class Generate_report extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
    }

    // In this method, we set up a session check that will check if the user is logged or not
    // If not the user will be redirected to the login page
    // The session check actually should be put in all of the method, but for demo purpose we just put it in the index method
    public function index(){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }  
        if(empty($this->session->userdata('user_demo'))) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('generate_report/login');
        }
        $this->load->model('Report');

        // this view will show the report
        // you can make more and different view to accomplish your need
        // check the view files for more explanation 
        $this->load->view("demo/report");
    }

    // This method will call downloadexcel from Report model
    // It require the form name 
    public function excel($form=''){
        $this->load->model('Report');
        $this->Report->downloadexcel($form);
    }

    // This method will call downloadpdf from Report model
    // It require the form name 
    public function pdf($form=''){
        $this->load->model('Report');
        $this->Report->downloadpdf($form);
    }
    
    // This method will call getData from Report model
    // It require the table name and column name of the database
    // This will return json data
    public function get($table,$col=''){
        $this->load->model('Report');
        echo $this->Report->getData($table,$col);
    }
    
    // this is a debug function
    public function view($func,$data=''){
        $this->load->model('Report');
        var_dump($this->Report->$func($data));
    }

    // This method if for the login purpose
    // This mothod will get the POST data and check it with the user database
    // If match, the user data will be saved in the session for use
    public function login(){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }
        
        if(!empty($this->session->userdata('user_demo'))) {
            redirect('generate_report');
        }
        
        if($_POST) {
            $db = $this->load->database('report', TRUE);
            $db->where('username', $_POST['username']);
            $db->where('password', $_POST['password']);
            $db->from('users');
            $result = $db->get()->row();
            if(!empty($result)) {
                $data = [
                    'user_demo' => $result->username,
                    'demo_level' => $result->level
                ];
 
                $this->session->set_userdata($data);
                if($this->input->post('url')!=""){
                    redirect($this->input->post('url'));
                }else redirect('generate_report');
            } else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert"><center>Username or password is wrong!</center></div>');
                redirect('generate_report/login');
            }
        }
 
        $this->load->view("demo/login");
    }
    public function logout() {
        $this->session->unset_userdata('user_demo');
        $this->session->unset_userdata('demo_level');
        redirect('generate_report');
    }
}