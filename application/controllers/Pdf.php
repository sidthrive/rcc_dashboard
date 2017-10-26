<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
    }

    public function index(){
        var_dump(getcwd());
    }

    public function show($form){
        $this->load->model('Report');
        $this->Report->downloadpdf($form);
    }

    public function save($form)
    {
        $this->load->view("demo/pdf");
        //header('Refresh: 1; URL='.base_url()."pdf/show/".$form);
    }

    public function savecharts($name)
    {
        $data = str_replace(' ', '+', $_POST['bin_data']);
        $data = base64_decode($data);
        $fileName = $name.'.png';
        $im = imagecreatefromstring($data);
         
        if ($im !== false) {
            // Save image in the specified location
            imagepng($im, getcwd()."/asset/images/".$fileName);
            imagedestroy($im);
            echo "Saved successfully";
        }
        else {
            echo 'An error occurred.';
        }
    }

    public function savechartssvg($name)
    {
        $data = str_replace(' ', '+', $_POST['svg_data']);
        $data = base64_decode($data);
        $fileName = $name.'.svg';
        file_put_contents (getcwd()."/asset/images/".$fileName,$data);
    }
}