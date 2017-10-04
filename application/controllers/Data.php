<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
    }
    public function index(){
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        $this->load->view('errors/html/error_general',["heading"=>"No Access","message"=>""]);
    }
    
    public function get_username(){
        $this->load->model('Username','u');
        $user = $this->session->userdata('username');
        $enum = $this->u->getEnum($user);
        $res = [];
        foreach ($enum as $e=>$x){
            array_push($res, $e);
        }
        echo json_encode($res);
    }
    
    public function getLocation($level,$loc=""){
        $this->load->model('CouchData','c');
        return json_encode($this->c->getLocation($level,$loc));
    }
}