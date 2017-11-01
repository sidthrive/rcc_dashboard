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

    // this method will show the generated pdf to the browser or download it
    public function show($form){
        $this->load->model('Report');
        $this->Report->downloadpdf($form);
    }

    // this method is called from download pdf button from generate report url
    // this will load demo/pdf view file that contain javascript that will save graph to disk
    // after 1 second, this will redirected to show function to show the pdf
    public function save($form)
    {
        // check this view file to better explanation to save graph to disk
        $this->load->view("demo/pdf");
        // after 1 second, this will redirected to show function to show the pdf
        header('Refresh: 1; URL='.base_url()."pdf/show/".$form);
    }


    // this method is called from javascript funcion in demo/pdf file
    // this will save the image data that sent by POST to png image type
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

    // this method is called from javascript funcion in demo/pdf file
    // this will save the image data that sent by POST to svg image type
    public function savechartssvg($name)
    {
        $data = str_replace(' ', '+', $_POST['svg_data']);
        $data = base64_decode($data);
        $fileName = $name.'.svg';
        file_put_contents (getcwd()."/asset/images/".$fileName,$data);
    }
}