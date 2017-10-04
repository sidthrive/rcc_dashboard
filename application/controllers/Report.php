<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
    }
    public function index(){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }     
        $this->load->view("laporan");
        
    }
    
    public function download(){
        ini_set('memory_limit', '512M');
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }
        
        if(isset($_POST)){
            $this->load->model('CouchData','c');   
            $this->c->download($_POST['mode']);
        }else{
            redirect('report');
        }
        redirect('report');
    }

    public function save(){
        ini_set('memory_limit', '512M');
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }
        
        if(isset($_POST)){
            $this->load->model('CouchData','c');   
            $this->c->save();
        }else{
            redirect('report');
        }
        redirect('report');
    }
}