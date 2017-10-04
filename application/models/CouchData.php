<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CouchData extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    public function getSurvey($user){   
        $this->load->model('Username','u');
        
        $enum = $this->u->getEnum($user);
        $key = $this->u->getKey($user);
        
        //daftar nama form
        $formname = array("unique_identifier","household_character","health_seeking_behaviour","knowledge_regarding_immunization","attitude_regarding_immunization","immunization_coverage","gps","confirm_form");

        //tentukan nama form yang akan diambil
        $form = $formname[0];
        $data = [];
        $res = [];
        foreach ($formname as $x=>$form){
            //mengambil data dari couchdb berdasarkan nama form dan tanggal yang sudah ditentukan
            if($form=="unique_identifier"){
                $data[$x] = $this->couchdb->startkey([$form,$key[0]])->endkey([$form,$key[1],[]])->include_docs(true)->getView('FormSubmission','formSubmission_by_form_user_and_client_version');
            }else{
                $data[$x] = $this->couchdb->startkey([$form,$key[0]])->endkey([$form,$key[1],[]])->getView('FormSubmission','formSubmission_by_form_user_and_client_version');
            }
            
        }
        
        $temp = [];
        foreach ($formname as $form){
            $temp[$form] = [];
        }
        
        
        foreach ($data as $d){
            foreach ($d->rows as $r){
                if(array_key_exists($r->value->user, $enum)){
                    if(!array_key_exists($r->value->user, $res)){
                        $res[$r->value->user] = [];
                        $res[$r->value->user][$r->value->id] = $temp;
                        if($r->key[0]=="unique_identifier") $res[$r->value->user][$r->value->id][$r->key[0]] = $r->doc->formInstance->form->fieldsAsMap;
                            else $res[$r->value->user][$r->value->id][$r->key[0]] = $r->id;
                    }else{
                        if(!array_key_exists($r->value->id, $res[$r->value->user])){
                            $res[$r->value->user][$r->value->id] = $temp;
                            if($r->key[0]=="unique_identifier") $res[$r->value->user][$r->value->id][$r->key[0]] = $r->doc->formInstance->form->fieldsAsMap;
                            else $res[$r->value->user][$r->value->id][$r->key[0]] = $r->id;
                        }else{
                            if($r->key[0]=="unique_identifier") $res[$r->value->user][$r->value->id][$r->key[0]] = $r->doc->formInstance->form->fieldsAsMap;
                            else $res[$r->value->user][$r->value->id][$r->key[0]] = $r->id;
                        }
                    }
                }
            }
        }
        
        foreach ($res as $u=>$r){
            foreach ($r as $i=>$d){
                if(empty($d['unique_identifier'])){
                    unset($res[$u][$i]);
                }
            }
        }
        
        return $res;
    }
    
    public function getFormDetail(){
        $this->load->library('PHPExcell');
        $formname = array("unique_identifier","household_character","health_seeking_behaviour","knowledge_regarding_immunization","attitude_regarding_immunization","immunization_coverage","gps");
        $formDetail = [];
        $choiceDetail = [];
        
        foreach ($formname as $form){
            $fileObject = PHPExcel_IOFactory::load(FCPATH.'asset/excel/'.$form.'.xls');
            $fileObject->setActiveSheetIndexByName("survey");
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
            $data = [];
            foreach ($arr_data as $a){
                if((isset($a['B'])&&isset($a['D']))||(isset($a['B'])&&$a['A']=='hidden')){
                    if($a['D']==null) {
                        $data[$a['B']] = ['type'=>$a['A'],'name'=>$a['B']];
                    }else {
                        $data[$a['B']] = ['type'=>$a['A'],'name'=>$a['D']];
                    }
                }
            }
            $formDetail[$form] = $data;
            
            $fileObject->setActiveSheetIndexByName("choices");
            $arr_data = array();
            $cell_collection = $fileObject->getActiveSheet()->getCellCollection();
            foreach ($cell_collection as $cell) {
                $column = $fileObject->getActiveSheet()->getCell($cell)->getColumn();
                $row = $fileObject->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $fileObject->getActiveSheet()->getCell($cell)->getValue();
                if ($row == 0) {
                    continue;
                } else {
                    $arr_data[$row-1][$column] = $data_value;
                }
            }
            $data = [];
            foreach ($arr_data as $a){
                if(isset($a['A'])&&isset($a['B'])&&isset($a['D'])){
                    if(isset($data[$a['A']])){
                        $data[$a['A']][$a['B']] = $a['D'];
                    }else{
                        $data[$a['A']] = [];
                        $data[$a['A']][$a['B']] = $a['D'];
                    }
                    
                }
            }
            $choiceDetail[$form] = $data;
        }
        $res['survey'] = $formDetail;
        $res['choices'] = $choiceDetail;
        return $res;
    }
    
    public function getForm($form){
        $this->load->library('PHPExcell');
        $formDetail = [];
        $choiceDetail = [];
        
        $fileObject = PHPExcel_IOFactory::load(FCPATH.'asset/excel/'.$form.'.xls');
        $fileObject->setActiveSheetIndexByName("survey");
        $arr_data = array();
        $cell_collection = $fileObject->getActiveSheet()->getCellCollection();
        foreach ($cell_collection as $cell) {
            $column = $fileObject->getActiveSheet()->getCell($cell)->getColumn();
            $row = $fileObject->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $fileObject->getActiveSheet()->getCell($cell)->getValue();
            if ($row == 0) {
                continue;
            } else {
                $arr_data[$row-1][$column] = $data_value;
            }
        }
        $data = [];
        foreach ($arr_data as $a){
            if((isset($a['B'])&&isset($a['D']))||(isset($a['B'])&&$a['A']=='hidden')){
                if($a['D']==null) {
                    $data[$a['B']] = ['type'=>$a['A'],'name'=>$a['B']];
                }else {
                    $data[$a['B']] = ['type'=>$a['A'],'name'=>$a['D']];
                }
            }
        }
        $formDetail[$form] = $data;

        $fileObject->setActiveSheetIndexByName("choices");
        $arr_data = array();
        $cell_collection = $fileObject->getActiveSheet()->getCellCollection();
        foreach ($cell_collection as $cell) {
            $column = $fileObject->getActiveSheet()->getCell($cell)->getColumn();
            $row = $fileObject->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $fileObject->getActiveSheet()->getCell($cell)->getValue();
            if ($row == 0) {
                continue;
            } else {
                $arr_data[$row-1][$column] = $data_value;
            }
        }
        $data = [];
        foreach ($arr_data as $a){
            if(isset($a['A'])&&isset($a['B'])&&isset($a['D'])){
                if(isset($data[$a['A']])){
                    $data[$a['A']][$a['B']] = $a['D'];
                }else{
                    $data[$a['A']] = [];
                    $data[$a['A']][$a['B']] = $a['D'];
                }

            }
        }
        $choiceDetail[$form] = $data;
        $res['survey'] = $formDetail;
        $res['choices'] = $choiceDetail;
        return $res;
    }
    
    public function getOneSurvey($enId,$form){
        $formname = array($form);
        $temp = [];
        foreach ($formname as $form){
            $temp[$form] = [];
        }
        $res = $temp;
        $data = $this->couchdb->startkey([$enId,$form])->endkey([$enId,$form,[]])->include_docs(true)->getView('FormSubmission','by_entityId_and_form');
        foreach ($data->rows as $d){
            $res[$d->value->form] = $d->doc->formInstance->form->fields;
        }
        
        return $res;
    }
    
    public function getSurveyDetail($enId){
        $formname = array("unique_identifier","household_character","health_seeking_behaviour","knowledge_regarding_immunization","attitude_regarding_immunization","immunization_coverage","gps");
        $temp = [];
        foreach ($formname as $form){
            $temp[$form] = [];
        }
        $res = $temp;
        $data = $this->couchdb->startkey([$enId])->endkey([$enId,[]])->include_docs(true)->getView('FormSubmission','by_entityId');
        foreach ($data->rows as $d){
            $res[$d->value->form] = $d->doc->formInstance->form->fields;
        }
        
        return $res;
    }
    
    private function getSurveyDocs($enId){
        $formname = array("unique_identifier","household_character","health_seeking_behaviour","knowledge_regarding_immunization","attitude_regarding_immunization","immunization_coverage","gps");
        $temp = [];
        foreach ($formname as $form){
            $temp[$form] = [];
        }
        $res = $temp;
        $data = $this->couchdb->startkey([$enId])->endkey([$enId,[]])->include_docs(true)->getView('FormSubmission','by_entityId');
        foreach ($data->rows as $d){
            $res[$d->value->form] = $d->doc;
        }
        
        return $res;
    }
    
    public function isHasValue($data){
        foreach ($data as $d){
            if($d!="") return true;
        }
        return false;
    }
    
    public function saveSurveyDetail($user,$enId,$data){
        $this->load->model('Uuid','u');
        $initialData = $this->getSurveyDocs($enId);
        foreach ($data as $form=>$formdata){
            if(empty($initialData[$form])){
                if($this->isHasValue($data[$form])){
                    $tempDoc = json_decode(file_get_contents(getcwd()."/asset/json/".$form.".json"));
                    $tempDoc->_id = $this->couchdb->getUuids()[0];
                    $tempDoc->anmId = $user;
                    $tempDoc->instanceId = $this->u->v4();
                    $tempDoc->entityId = $data[$form]['id'] = $enId;
                    $tempDoc->clientVersion = $tempDoc->serverVersion = floor(microtime(true)*1000);
                    if(isset($data[$form]['IsDraft2'])){
                        $data[$form]['IsDraft2'] = '1';
                    }elseif(isset($data[$form]['IsDraft3'])){
                        $data[$form]['IsDraft3'] = '1';
                    }elseif(isset($data[$form]['IsDraft4'])){
                        $data[$form]['IsDraft4'] = '1';
                    }elseif(isset($data[$form]['IsDraft5'])){
                        $data[$form]['IsDraft5'] = '1';
                    }elseif(isset($data[$form]['IsDraft6'])){
                        $data[$form]['IsDraft6'] = '1';
                    }
                    if(isset($data[$form]['submissionDate'])){
                        $data[$form]['submissionDate'] = date("Y-m-d");
                    }
                    foreach ($tempDoc->formInstance->form->fields as $index=>$field){
                        if(array_key_exists($field->name, $data[$form])){
                            $tempDoc->formInstance->form->fields[$index]->value = $data[$form][$field->name];
                            $tempDoc->formInstance->form->fieldsAsMap->{$field->name} = $data[$form][$field->name];
                        }
                    }
                    $res = $this->couchdb->storeDoc($tempDoc);
                }
                
            }else{
                $changed = false;
                $changes = [];
                foreach ($data[$form] as $key=>$value){
                    if($initialData[$form]->formInstance->form->fieldsAsMap->$key!=$value){
                        $initialData[$form]->formInstance->form->fieldsAsMap->$key = $value;
                        $changes[$key] = $value;
                        $changed = true;
                    }
                }
                if($changed){
                    foreach ($initialData[$form]->formInstance->form->fields as $index=>$field){
                        if(array_key_exists($field->name, $changes)){
                            $initialData[$form]->formInstance->form->fields[$index]->value = $changes[$field->name];
                        }
                    }
                    unset($initialData[$form]->_rev);
                    $initialData[$form]->_id = $this->couchdb->getUuids()[0];
                    $initialData[$form]->clientVersion = floor(microtime(true)*1000);
                    $initialData[$form]->serverVersion = floor(microtime(true)*1000);
                    $res = $this->couchdb->storeDoc($initialData[$form]);
                }
            }
        }
    }
    
    public function getMaps($loc){
        $this->load->library('Googlemaps');
        
        $coor = explode(' ', $loc);
        $coor_string = implode(',', [$coor[0],$coor[1]]);
        
        $config['center'] = $coor_string;
        $config['zoom'] = 17;
        $config['apiKey'] = 'AIzaSyAxJiDItoG0qBOffucWuEL2lyJ2F0Vambo';
        $this->googlemaps->initialize($config);

        $marker = array();
        $marker['position'] = $coor_string;
        $this->googlemaps->add_marker($marker);
        $map = $this->googlemaps->create_map();
        
        echo $map['js'];
        echo $map['html'];
    }
    
    public function getMapsDraggable($loc){
        $this->load->library('Googlemaps');
        
        $coor = explode(' ', $loc);
        $coor_string = implode(',', [$coor[0],$coor[1]]);
        
        $config['center'] = $coor_string;
        $config['zoom'] = 17;
        $config['apiKey'] = 'AIzaSyAxJiDItoG0qBOffucWuEL2lyJ2F0Vambo';
        $this->googlemaps->initialize($config);

        $marker = array();
        $marker['position'] = $coor_string;
        $marker['draggable'] = true;
        $marker['ondragend'] = 'updatePosition(event.latLng.lat(), event.latLng.lng());';
        $this->googlemaps->add_marker($marker);
        $map = $this->googlemaps->create_map();
        
        echo $map['js'];
        echo $map['html'];
    }
    
    public function getMapsClickAndDraggable(){
        $this->load->library('Googlemaps');
        
        $config['center'] = "-6.2 106.8357";
        $config['zoom'] = 11;
        $config['apiKey'] = 'AIzaSyAxJiDItoG0qBOffucWuEL2lyJ2F0Vambo';
        $this->googlemaps->initialize($config);

        $marker = array();
        $marker['position'] = "-6.2 106.8357";
        $marker['draggable'] = true;
        $marker['ondragend'] = 'updatePosition(event.latLng.lat(), event.latLng.lng());';
        $this->googlemaps->add_marker($marker);
        $map = $this->googlemaps->create_map();
        
        echo $map['js'];
        echo $map['html'];
    }
    
    public function showAllMaps($user){
        $this->load->library('Googlemaps');
        $this->load->model('Username','u');
        
        $key = $this->u->getKey($user);
        $data= $this->couchdb->startkey(['gps',$key[0]])->endkey(['gps',$key[1],[]])->include_docs(true)->getView('FormSubmission','formSubmission_by_form_user_and_client_version');
        $resp= $this->couchdb->startkey(['unique_identifier',$key[0]])->endkey(['unique_identifier',$key[1],[]])->include_docs(true)->getView('FormSubmission','formSubmission_by_form_user_and_client_version');
        $resps = [];
        foreach ($resp->rows as $r){
            $resps[$r->value->id] = $r->doc->formInstance->form->fieldsAsMap;
        }
        $gps = [];
        
        foreach($data->rows as $d){
            if($d->doc->formInstance->form->fieldsAsMap->store_gps=='') continue;
            $gps[$d->value->id] = ["gps"=>$d->doc->formInstance->form->fieldsAsMap->store_gps,"user"=>$d->doc->anmId];
        }
        
        $config['center'] = '-6.220879, 106.88257';
        $config['zoom'] = 'auto';
        $config['map_height'] = '500px';
        $config['apiKey'] = 'AIzaSyAxJiDItoG0qBOffucWuEL2lyJ2F0Vambo';
        $this->googlemaps->initialize($config);
        
        foreach ($gps as $id=>$g){
            if(array_key_exists($id, $resps)){
                $coor = explode(' ', $g['gps']);
                $coor_string = implode(',', [$coor[0],$coor[1]]);
                $marker = array();
                $marker['position'] = $coor_string;
                $marker['infowindow_content'] = $id."<br>".$g['user']."<br>".$resps[$id]->Province."<br>".$resps[$id]->District."<br>".$resps[$id]->{'Sub-district'}."<br>".$resps[$id]->Village."<br>".$resps[$id]->{'Sub-village'};
                $this->googlemaps->add_marker($marker);
            }
            
        }
        
        $map = $this->googlemaps->create_map();
        
        echo $map['js'];
        echo $map['html'];
    }

    public function getLocation($level="",$loc=""){
        $loc = str_replace("%20", " ", $loc);
        if($loc==""){
            echo json_encode([]);
            return;
        }
        $locs = json_decode(file_get_contents(getcwd()."/asset/json/locations.json"),TRUE);
        if($level == 'Province'){
            echo json_encode(["Jakarta"]);
        }else{
            echo json_encode($locs[$level][$loc]);
        }
    }

    public function approve($user,$id){
        $doc = $this->getConfirmDoc($user,$id);
        $res = $this->couchdb->storeDoc($doc);
        return $res;
    }
    
    private function getConfirmDoc($user,$id){
        $this->load->model('Uuid','u');
        $temp = (object)[];
        $temp->_id = $this->couchdb->getUuids()[0];
        $temp->type = "FormSubmission";
        $temp->anmId = $user;
        $temp->instanceId = $this->u->v4();;
        $temp->formName = "confirm_form";
        $temp->entityId = $id;
        $temp->clientVersion = floor(microtime(true)*1000);;
        $temp->formDataDefinitionVersion = "2";
        $temp->formInstance = (object)[];
        $temp->formInstance->form_data_definition_version = "2";
        $temp->formInstance->form = (object)[];
        $temp->formInstance->form->bind_type = "kartu_ibu";
        $temp->formInstance->form->default_bind_path = "/model/instance/confirm_forms/";
        $temp->formInstance->form->fields = [];
        $temp->formInstance->form->fields[0] = [];
        $temp->formInstance->form->fields[0]['name'] = "id";
        $temp->formInstance->form->fields[0]['value'] = $id;
        $temp->formInstance->form->fields[0]['source'] = "kartu_ibu.id";
        $temp->formInstance->form->fields[1] = [];
        $temp->formInstance->form->fields[1]['name'] = "is_confirm";
        $temp->formInstance->form->fields[1]['value'] = "1";
        $temp->formInstance->form->fields[1]['source'] = "kartu_ibu.is_confirm";
        $temp->formInstance->form->fieldsAsMap = (object)[];
        $temp->formInstance->form->fieldsAsMap->id = $id;
        $temp->formInstance->form->fieldsAsMap->is_confirm = "1";
        $temp->serverVersion = floor(microtime(true)*1000);;
        
        $temp = (object)$temp;
        return $temp;
    }
    
    public function download($mode){
        $this->load->model('Username','u');
        
        $formname = array("unique_identifier","household_character","health_seeking_behaviour","knowledge_regarding_immunization","attitude_regarding_immunization","immunization_coverage","gps");
        $user = $this->session->userdata('username');
        $enum = $this->u->getEnum($user);
        $key = $this->u->getKey($user);
        
        $dataconfirmed = $this->couchdb->startkey([$key[0]])->endkey([$key[1],[]])->getView('FormSubmission','rcc_confirmed')->rows;
        $confirmed = [];
        foreach ($dataconfirmed as $d){
            $confirmed[$d->value->enId] = $d->key[1];
        }
        unset($dataconfirmed);
        
        $datasubmitted = $this->couchdb->startkey(["unique_identifier",$key[0]])->endkey(["unique_identifier",$key[1],[]])->getView('FormSubmission','formSubmission_by_form_user_and_client_version')->rows;  
        $submitted = [];
        foreach ($datasubmitted as $d){
            $submitted[$d->value->id] = $d->key[2];
        }
        unset($datasubmitted);
        
        $data = [];
        $res = [];
        foreach ($formname as $x=>$form){
            //mengambil data dari couchdb berdasarkan nama form dan tanggal yang sudah ditentukan
            $data[$x] = json_decode(file_get_contents("http://118.91.130.18:5983/opensrp-form/_design/FormSubmission/_list/unique/formSubmission_by_form_user_and_client_version?startkey=[%22$form%22,%22$key[1]%22,[]]&endkey=[%22$form%22,%22$key[0]%22]&descending=true&include_docs=true",0,null,null));
            $csv = "";
            $csv .= "docId;";
            $csv .= "Username;";
            $csv .= "formName;";
            $csv .= "Submission Time;";

            $excel = $this->getForm($form);
            $excel['survey'][$form] = ['id'=>[]]+$excel['survey'][$form];
            unset($excel['survey'][$form]['name']);
            unset($excel['survey'][$form]['is_draft']);
            
            foreach ($excel['survey'][$form] as $name => $value) {
                $csv .= $name.";";
            }
            $csv .= PHP_EOL;
            
            foreach ($data[$x]->rows as $dt){
                if($mode=='approved'){
                    if(array_key_exists($dt->doc->entityId,$confirmed)){
                        $csv .= $dt->doc->_id.";";
                        $csv .= $dt->doc->anmId.";";
                        $csv .= $dt->doc->formName.";";
                        $csv .= date("Y-m-d",  substr($dt->doc->clientVersion, 0, 10)).";";
                        $fld = [];
                        foreach ($dt->doc->formInstance->form->fields as $d){
                            if(isset($d->value)) $fld[$d->name] = $d->value;
                            else $fld[$d->name] = '';
                        }
                        foreach ($excel['survey'][$form] as $name => $value){
                            if(isset($fld[$name])) $csv .= $fld[$name].";";
                            else $csv .= "".";";
                        }
                        $csv .= PHP_EOL;
                    }
                }elseif($mode=='submitted'){
                    if(array_key_exists($dt->doc->entityId,$submitted)){
                        $csv .= $dt->doc->_id.";";
                        $csv .= $dt->doc->anmId.";";
                        $csv .= $dt->doc->formName.";";
                        $csv .= date("Y-m-d",  substr($dt->doc->clientVersion, 0, 10)).";";
                        foreach ($dt->doc->formInstance->form->fields as $d){
                            if(isset($d->value)) $fld[$d->name] = $d->value;
                            else $fld[$d->name] = '';
                        }
                        foreach ($excel['survey'][$form] as $name => $value){
                            if(isset($fld[$name])) $csv .= $fld[$name].";";
                            else $csv .= "".";";
                        }
                        $csv .= PHP_EOL;
                    }
                }elseif($mode=='raw'){
                    $csv .= $dt->doc->_id.";";
                    $csv .= $dt->doc->anmId.";";
                    $csv .= $dt->doc->formName.";";
                    $csv .= date("Y-m-d",  substr($dt->doc->clientVersion, 0, 10)).";";
                    foreach ($dt->doc->formInstance->form->fields as $d){
                        if(isset($d->value)) $fld[$d->name] = $d->value;
                        else $fld[$d->name] = '';
                    }
                    foreach ($excel['survey'][$form] as $name => $value){
                        if(isset($fld[$name])) $csv .= $fld[$name].";";
                        else $csv .= "".";";
                    }
                    $csv .= PHP_EOL;
                }
                
                
            }
            $csv_handler = fopen (getcwd().'/asset/temp/'.$form.'.csv','w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);
            unset($data[$x]);
        }
        if($mode=='approved') $filename = "rcc_data_approved.zip";
        elseif($mode=='submitted') $filename = "rcc_data_submitted.zip";
        elseif($mode=='raw') $filename = "rcc_raw_data.zip";
        
        $zip = new ZipArchive();
        if ($zip->open(getcwd()."/asset/temp/".$filename, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open");
        }
        foreach ($formname as $form){
            $zip->addFile(getcwd()."/asset/temp/".$form.".csv",ltrim($form.".csv", '/'));
        }
        $zip->close();
        ob_clean();
        header("Content-type: application/zip"); 
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-length: " . filesize(getcwd()."/asset/temp/".$filename));
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        readfile(getcwd()."/asset/temp/".$filename);
        foreach ($formname as $form){
            unlink(getcwd()."/asset/temp/".$form.".csv");
        }
        unlink(getcwd()."/asset/temp/".$filename);
    }
    
    public function save(){
        $this->load->model('Username','u');
        
        $formname = array("unique_identifier","household_character","health_seeking_behaviour","knowledge_regarding_immunization","attitude_regarding_immunization","immunization_coverage","gps");
        $user = $this->session->userdata('username');
        $enum = $this->u->getEnum($user);
        $key = $this->u->getKey($user);
        
        $dataconfirmed = $this->couchdb->startkey([$key[0]])->endkey([$key[1],[]])->getView('FormSubmission','rcc_confirmed')->rows;
        $confirmed = [];
        foreach ($dataconfirmed as $d){
            $confirmed[$d->value->enId] = $d->key[1];
        }
        unset($dataconfirmed);
        
        $datasubmitted = $this->couchdb->startkey(["unique_identifier",$key[0]])->endkey(["unique_identifier",$key[1],[]])->getView('FormSubmission','formSubmission_by_form_user_and_client_version')->rows;  
        $submitted = [];
        foreach ($datasubmitted as $d){
            $submitted[$d->value->id] = $d->key[2];
        }
        unset($datasubmitted);
        
        $data = [];
        $res = [];
        foreach ($formname as $x=>$form){
            //mengambil data dari couchdb berdasarkan nama form dan tanggal yang sudah ditentukan
            $data[$x] = json_decode(file_get_contents("http://118.91.130.18:5983/opensrp-form/_design/FormSubmission/_list/unique/formSubmission_by_form_user_and_client_version?startkey=[%22$form%22,%22$key[1]%22,[]]&endkey=[%22$form%22,%22$key[0]%22]&descending=true&include_docs=true",0,null,null));
            $csv = "";
            $csv .= "docId;";
            $csv .= "Username;";
            $csv .= "formName;";
            $csv .= "Submission Time;";

            $excel = $this->getForm($form);
            $excel['survey'][$form] = ['id'=>[]]+$excel['survey'][$form];
            unset($excel['survey'][$form]['name']);
            unset($excel['survey'][$form]['is_draft']);
            
            foreach ($excel['survey'][$form] as $name => $value) {
                $csv .= $name.";";
            }
            $csv .= PHP_EOL;
            
            foreach ($data[$x]->rows as $dt){
                    if(array_key_exists($dt->doc->entityId,$confirmed)){
                        $csv .= $dt->doc->_id.";";
                        $csv .= $dt->doc->anmId.";";
                        $csv .= $dt->doc->formName.";";
                        $csv .= date("Y-m-d",  substr($dt->doc->clientVersion, 0, 10)).";";
                        $fld = [];
                        foreach ($dt->doc->formInstance->form->fields as $d){
                            if(isset($d->value)) $fld[$d->name] = $d->value;
                            else $fld[$d->name] = '';
                        }
                        foreach ($excel['survey'][$form] as $name => $value){
                            if(isset($fld[$name])) $csv .= $fld[$name].";";
                            else $csv .= "".";";
                        }
                        $csv .= PHP_EOL;
                    }
            }
            $csv_handler = fopen (getcwd().'/asset/save/'.$form.'.csv','w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);
            unset($data[$x]);
        }
    }
}