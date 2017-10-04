<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supervise extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
    }
    public function index(){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }

        $this->load->model('CouchData','c');
        $data['data'] = $this->c->getSurvey($this->session->userdata('username'));
        
        $this->load->view("template",$data);
        
    }
    
    public function detail($user,$id){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }

        $this->load->model('CouchData','c');
        $data['data'] = $this->c->getSurveyDetail($id);
        $data['id'] = $id;
        $data['user'] = $user;
        $this->load->view("detail",$data);
        
    }
    
    public function edit($user,$id){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }

        $this->load->model('CouchData','c');
        $data['data'] = $this->c->getSurveyDetail($id);
        $data['id'] = $id;
        $data['user'] = $user;
        $this->load->view("edit",$data);
        
    }
    
    public function ddd(){
        $formname = array("unique_identifier","household_character","health_seeking_behaviour","knowledge_regarding_immunization","attitude_regarding_immunization","immunization_coverage","gps");
        foreach ($formname as $form){
            $tempDoc = json_decode(file_get_contents(getcwd()."/asset/json/".$form.".json"));
            $tempDoc->_id = "";
            $tempDoc->anmId = "";
            $tempDoc->instanceId = "";
            $tempDoc->entityId = "";
            $tempDoc->clientVersion = $tempDoc->serverVersion = 0;
            foreach ($tempDoc->formInstance->form->fields as $index=>$field){
                $tempDoc->formInstance->form->fields[$index]->value = "";
                $tempDoc->formInstance->form->fieldsAsMap->{$field->name} = "";
            }
            $doc = json_encode($tempDoc);
            $csv_handler = fopen (getcwd()."/asset/json/".$form.".json",'w');
            fwrite ($csv_handler,$doc);
            fclose ($csv_handler);
        }
    }
    
    public function save($user,$id){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }
        $this->load->model('CouchData','c');
        if(isset($_POST)){
            $this->c->saveSurveyDetail($user,$id,$_POST);
        }else{
            redirect('supervise/edit/'.$user.'/'.$id);
        }
        redirect('supervise/detail/'.$user.'/'.$id);
    }
    
    public function approve($user,$id){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }
        $this->load->model('CouchData','c');
        $res = $this->c->approve($user,$id);
        if(isset($res->ok)){
            if($res->ok){
                redirect('supervise');
            }
        }
        redirect('supervise/detail/'.$user.'/'.$id);
    }
    
    public function maps(){
        if(empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') == FALSE) {
            $this->session->set_flashdata('url', $this->uri->uri_string);
            redirect('supervise/login');
        }

        $this->load->model('CouchData','c');
        
        $this->load->view("maps");
    }
    
    public function login(){
        if(!empty($this->session->userdata('id_user'))&&$this->session->userdata('user_valid') != FALSE) {
            redirect('');
        }
        if($_POST) {
            $this->db->where('username', $_POST['username']);
            $this->db->where('password', $_POST['password']);
            $this->db->from('users');
            $result = $this->db->get()->row();
            if(!empty($result)) {
                $data = [
                    'id_user' => $result->id_user,
                    'username' => $result->username,
                    'level' => $result->level,
                    'user_valid' => true
                ];
 
                $this->session->set_userdata($data);
                $this->db->query("UPDATE users SET last_login=current_timestamp WHERE id_user = '".$result->id_user."'");
                if($this->input->post('url')!=""){
                    redirect($this->input->post('url'));
                }else redirect('');
            } else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert"><center>Username or password is wrong!</center></div>');
                redirect('supervise/login');
            }
        }
 
        $this->load->view("login");
    }
    
    public function logout() {
            $this->session->sess_destroy();
            redirect('');
        }
}