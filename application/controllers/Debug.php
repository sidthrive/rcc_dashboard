<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
    }
    public function index(){
        
    }
    
    public function update(){
        $this->load->model('Debugs','d');
        $this->d->update();
    }
}