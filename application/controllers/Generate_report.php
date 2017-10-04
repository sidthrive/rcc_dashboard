<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate_report extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
    }
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
//        $this->Report->getLocationList();
        $this->load->view("demo/report");
    }

    public function excel($form=''){
        $this->load->model('Report');
        $this->Report->downloadexcel($form);
    }

    public function pdf($form=''){
        $this->load->model('Report');
        $this->Report->downloadpdf($form);
    }
    
    public function get($table,$col=''){
        $this->load->model('Report');
        echo $this->Report->getData($table,$col);
    }
    
    public function view($func,$data=''){
        $this->load->model('Report');
        var_dump($this->Report->$func($data));
    }


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