<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Debugs extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    public function update(){
        $this->load->library('PHPExcell');
        $fileObject = PHPExcel_IOFactory::load(FCPATH.'asset/temp/jatipulo.xlsx');
        $fileObject->setActiveSheetIndex(0);
        
        $excel = [];
        $excel['RW 7 - Jati Pulo'] = [];
        $excel['RW 8 - Jati Pulo'] = [];
        $arr_data = array();
        $cell_collection = $fileObject->getActiveSheet()->getCellCollection();
        foreach ($cell_collection as $cell) {
            $column = $fileObject->getActiveSheet()->getCell($cell)->getColumn();
            $row = $fileObject->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $fileObject->getActiveSheet()->getCell($cell)->getValue();
            if ($row == 1) {
                continue;
            } else {
                $arr_data[$row-1][$column] = $data_value;
            }
        }
        foreach ($arr_data as $data){
            if(isset($data['A'])) $excel['RW 7 - Jati Pulo'][strtolower($data['A'])] = true;
            if(isset($data['B'])) $excel['RW 8 - Jati Pulo'][strtolower($data['B'])] = true;
        }
        
        
        
        $result = [];
        $result['RW 7 - Jati Pulo'] = [];
        $result['RW 8 - Jati Pulo'] = [];
        $resp = [];
        $data = $this->couchdb->getView('FormSubmission','rcc_debug');
        foreach ($data->rows as $d){
            $resp[$d->id] = $d->value->name;
            if(array_key_exists(strtolower($d->value->name), $excel['RW 7 - Jati Pulo'])){
                $result['RW 7 - Jati Pulo'][$d->id] = $d->value->name;
            }
            if(array_key_exists(strtolower($d->value->name), $excel['RW 8 - Jati Pulo'])){
                $result['RW 8 - Jati Pulo'][$d->id] = $d->value->name;
            }
        }
        
        var_dump($resp);
        var_dump($excel);
        var_dump($result);exit;
        
        foreach ($result as $rw=>$r){
            foreach ($r as $id=>$name){
                $doc = $this->couchdb->getDoc($id);
                $doc->formInstance->form->fieldsAsMap->Village = 'Jati Pulo';
                $doc->formInstance->form->fieldsAsMap->{'Sub-village'} = $rw;
                foreach ($doc->formInstance->form->fields as $x=>$f){
                    if($f->name=='Village') $doc->formInstance->form->fields[$x]->value = 'Jati Pulo';
                    if($f->name=='Sub-village') $doc->formInstance->form->fields[$x]->value = $rw;
                }
//                $response = $this->couchdb->storeDoc($doc);
//                var_dump($response);
            }
        }
    }
}