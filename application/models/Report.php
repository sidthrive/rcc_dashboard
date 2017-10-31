<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
    This is model class for generate report
    This model contains method that used to get the data from database and compile it into excel or pdf files
    some of the method is only for debug purpose
*/

class Report extends CI_Model{

    // This is example structure of the report
    // you can make any structure that fit your purpose
    protected $table_col = [
        "unique_identifier"=> ['child_age','respondent_age','respondent_education','relation_to_child','village'],
        "household_character"=> ['household_size','dairy_product','number_of_rooms','informal_income','spent_on_food'],
        "health_seeking_behaviour"=> ['anc_visit_num','place_of_birth','attendance_at_posyandu','attendance_at_puskesmas','action_taken'],
        "attitude_regarding_immunization"=> ['attitude_1','attitude_2','attitude_3','attitude_4'],
        "immunization_coverage"=> ['have_mch_book','hepb_0','hepb_1','hepb_2','hepb_3','hepb_4'],
    ];
    
    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('report', TRUE);
    }
    
    public function getHousehold(){
        $ret = [];
        $hh = $this->db->query("SELECT * FROM unique_identifier")->result();
        foreach ($hh as $h){
            
        }
    }
    
    public function getLocationList(){
        $res = [];
        $hh = $this->db->query("SELECT Province,District,`Sub-district`,Village,`Sub-village` FROM unique_identifier")->result();
        foreach ($hh as $h){
            if($h->Province!=''&&!array_key_exists($h->Province, $res)){
                $res[$h->Province] = [];
            }
            if($h->District!=''&&!array_key_exists($h->District, $res[$h->Province])){
                $res[$h->Province][$h->District] = [];
            }
            if($h->{'Sub-district'}!=''&&!array_key_exists($h->{'Sub-district'}, $res[$h->Province][$h->District])){
                $res[$h->Province][$h->District][$h->{'Sub-district'}] = [];
            }
            if($h->Village!=''&&!array_key_exists($h->Village, $res[$h->Province][$h->District][$h->{'Sub-district'}])){
                $res[$h->Province][$h->District][$h->{'Sub-district'}][$h->Village] = [];
            }
            if($h->{'Sub-village'}!=''&&!array_key_exists($h->{'Sub-village'}, $res[$h->Province][$h->District][$h->{'Sub-district'}][$h->Village])){
                array_push($res[$h->Province][$h->District][$h->{'Sub-district'}][$h->Village], $h->{'Sub-village'});
            }
        }
        var_dump(json_encode($res));exit;
    }
    
    // this method will fetch the particular column form database table
    // and associate it with string name from xlsForm 
    public function getData($table,$col){
        $ret = [];
        $res = [];
        $hh = $this->db->query("SELECT $col FROM $table")->result();
        foreach ($hh as $h){
            if($h->$col!==''){
                if(!array_key_exists($h->$col, $ret)){
                    $ret[$h->$col] = 1;
                }else{
                    $ret[$h->$col]++;
                }
            }
        }
        ksort($ret);

        //this will get the data form xlsForm file that contain the survey detail
        $form = $this->getFormDetail($table);
        if(explode(' ',$form['survey'][$col]['type'])[0]=='select_one'){
            $xcol = explode(' ',$form['survey'][$col]['type'])[1];
        }else{
            $xcol = $col;
        }
        foreach ($ret as $x=>$r){
            if(array_key_exists($x, $form['choices'][$xcol])){
                array_push($res, ['name'=>$form['choices'][$xcol][$x],'y'=>$r]);
            }else{
                array_push($res, ['name'=>$x,'y'=>$r]);
            }
            
        }
        // return it with json form
        return json_encode($res);
    }
    
    // this method will get the data from xlsForm files by using PHPExcel library
    // and return it with array data
    public function getFormDetail($form){
        $this->load->library('PHPExcell');
        $formname = array("unique_identifier","household_character","health_seeking_behaviour","knowledge_regarding_immunization","attitude_regarding_immunization","immunization_coverage","gps");
        
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
                    $data[strtolower($a['B'])] = ['type'=>$a['A'],'name'=>$a['B']];
                }else {
                    $data[strtolower($a['B'])] = ['type'=>$a['A'],'name'=>$a['D']];
                }
            }
        }
        $formDetail = $data;
        
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
        $choiceDetail = $data;
        $res['survey'] = $formDetail;
        $res['choices'] = $choiceDetail;
        return $res;
    }

    // This method is example for download report in the form of excel files
    // Using PHPExcel library, you can edit most of the excel file,
    // you can read the documentation for better explanation
    // https://github.com/PHPOffice/PHPExcel/wiki/User-Documentation-Overview-and-Quickstart-Guide
    public function downloadexcel($form=''){
        $this->load->library('PHPExcell');
        // this will create a PHPExcel object reader that can read existing excel file
        // but in this example we will not using this
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');

        // this will create a new excel object, at initialization this object will already have one worksheet 
        $fileObject = new PHPExcel('Excel2007');
        // change the initial worksheet name
        $fileObject->getSheet(0)->setTitle('Data');
        $row = 1;
        $col = 'A';
        $fileObject->setActiveSheetIndexByName('Data');
        $rowIterator = new PHPExcel_Worksheet_RowCellIterator($fileObject->getSheet(0));
        $chart_prop = [];
        foreach ($this->table_col[$form] as $col_name) {
            $data = json_decode($this->getData($form, $col_name));
            $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), strtoupper(str_replace('_', ' ', $col_name))." DATA");
            $rowIterator = new PHPExcel_Worksheet_RowCellIterator($fileObject->getSheet(0),++$row);
            $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), ucwords(str_replace('_', ' ', $col_name)));
            $rowIterator->next();
            $chart_prop[$col_name]['label']['start'] = $rowIterator->current()->getCoordinate();
            foreach ($data as $key => $d) {
                $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), ucwords(str_replace('_', ' ', $d->name)));
                $chart_prop[$col_name]['label']['end'] = $rowIterator->current()->getCoordinate();
                $rowIterator->next();
            }
            $rowIterator = new PHPExcel_Worksheet_RowCellIterator($fileObject->getSheet(0),++$row);
            $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), ucwords(str_replace('_', ' ', "Value")));
            $rowIterator->next();
            $chart_prop[$col_name]['data']['start'] = $rowIterator->current()->getCoordinate();
            foreach ($data as $key => $d) {
                $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), ucwords(str_replace('_', ' ', $d->y)));
                $chart_prop[$col_name]['data']['end'] = $rowIterator->current()->getCoordinate();
                $rowIterator->next();
            }
            $row++;
            $rowIterator = new PHPExcel_Worksheet_RowCellIterator($fileObject->getSheet(0),++$row);
        }

        // create worksheet object so we can add it into excel object
        $myWorkSheet = new PHPExcel_Worksheet($fileObject, 'Graph');
        // add the worksheet object into the excel object
        $fileObject->addSheet($myWorkSheet);
        $fileObject->setActiveSheetIndexByName('Graph');
        $col_s = 'B';
        $row_s = 2;
        $col_e = 'J';
        $row_e = 17;
        foreach ($chart_prop as $name => $d) {
            $dsl = [new PHPExcel_Chart_DataSeriesValues('String', ucwords(str_replace('_', ' ', $name)) )];
            $xal = [new PHPExcel_Chart_DataSeriesValues('String', 'Data!'.$d['label']['start'].':'.$d['label']['end'])];
            $dsv = [new PHPExcel_Chart_DataSeriesValues('Number', 'Data!'.$d['data']['start'].':'.$d['data']['end'])];
            $ds = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_PIECHART,null,range(0, count($dsv)-1),$dsl,$xal,$dsv);
            $pa = new PHPExcel_Chart_PlotArea(NULL, array($ds));
            $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
            $title = new PHPExcel_Chart_Title(ucwords(str_replace('_', ' ', $name)));
            $chart = new PHPExcel_Chart($name,$title,$legend,$pa,true,0,NULL,NULL);
            $chart->setTopLeftPosition($col_s.$row_s);
            $chart->setBottomRightPosition($col_e.$row_e);
            $row_s = $row_s + 16;
            $row_e = $row_e + 16;
            $fileObject->getActiveSheet()->addChart($chart);
        }

        // clean the output buffer so the excel download will not get error
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$form.'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $saveContainer = PHPExcel_IOFactory::createWriter($fileObject,'Excel2007');
        $saveContainer->setIncludeCharts(TRUE);
        $saveContainer->save('php://output');
    }

    // This method will generate pdf file using TCPDF library
    // you can have more example here : https://tcpdf.org/examples/
    public function downloadpdf($form=''){
        $form = 'unique_identifier';
        $data = [];
        foreach ($this->table_col[$form] as $col_name) {
            $data[$col_name] = json_decode($this->getData($form, $col_name));
        }
        $this->load->library('mypdf');
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('SID');
        $pdf->SetTitle(strtoupper(str_replace('_', ' ', $form)));

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 004', PDF_HEADER_STRING);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(20, 5, 5);

        // set auto page breaks
//        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------
        $pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

        // set font
        

        $pdf->AddPage('P', 'A4');
        $pdf->setPage(1, true);
        $pdf->SetXY(15,40);
        $pdf->Cell(0, 10, '> Child Age Distribution', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Ln();
        $pdf->Cell(20, 6, 'Age', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 'Count', 1, 0, 'L', 0);
        $pdf->Ln();
        foreach ($data['child_age'] as $value) {
            $pdf->Cell(20, 6, $value->name, 'LR', 0, 'L', 0);
            $pdf->Cell(20, 6, $value->y, 'LR', 0, 'L', 0);
            $pdf->Ln();
        }
        $pdf->Cell(40, 0, '', 'T');

        $pdf->Image('asset/images/child_age.png', 75, 50, 100, 100, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);

        $pdf->SetXY(15,180);
        $pdf->Cell(0, 10, '> Respondent Relation to Child', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Ln();
        $pdf->Cell(45, 6, 'Relation', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 'Count', 1, 0, 'L', 0);
        $pdf->Ln();
        foreach ($data['relation_to_child'] as $value) {
            $pdf->Cell(45, 6, $value->name, 'LR', 0, 'L', 0);
            $pdf->Cell(20, 6, $value->y, 'LR', 0, 'L', 0);
            $pdf->Ln();
        }
        $pdf->Cell(65, 0, '', 'T');

        $pdf->Image('asset/images/relation_to_child.png', 100, 190, 80, 80, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);


        $pdf->AddPage('P', 'A4');
        $pdf->setPage(2, true);
        $pdf->SetXY(15,40);
        $pdf->Cell(0, 10, '> Respondent Age Distribution', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Ln();
        $pdf->Cell(20, 6, 'Age', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 'Count', 1, 0, 'L', 0);
        $pdf->Ln();
        $count = 0;
        foreach ($data['respondent_age'] as $key => $value) {
            $pdf->Cell(20, 6, $value->name, 'LR', 0, 'L', 0);
            $pdf->Cell(20, 6, $value->y, 'LR', 0, 'L', 0);
            $pdf->Ln();
            unset($data['respondent_age'][$key]);
            $count++;
            if($count==17) break;
        }
        $pdf->Cell(40, 0, '', 'T');
        $pdf->SetMargins(70, 5, 5);
        $pdf->SetXY(20,44);
        $pdf->Ln();
        $pdf->Cell(20, 6, 'Age', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 'Count', 1, 0, 'L', 0);
        $pdf->Ln();
        $count = 0;
        foreach ($data['respondent_age'] as $key => $value) {
            $pdf->Cell(20, 6, $value->name, 'LR', 0, 'L', 0);
            $pdf->Cell(20, 6, $value->y, 'LR', 0, 'L', 0);
            $pdf->Ln();
            unset($data['respondent_age'][$key]);
            $count++;
            if($count==17) break;
        }
        $pdf->Cell(40, 0, '', 'T');
        $pdf->SetMargins(120, 5, 5);
        $pdf->SetXY(15,44);
        $pdf->Ln();
        $pdf->Cell(20, 6, 'Age', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 'Count', 1, 0, 'L', 0);
        $pdf->Ln();
        $count = 0;
        foreach ($data['respondent_age'] as $key => $value) {
            $pdf->Cell(20, 6, $value->name, 'LR', 0, 'L', 0);
            $pdf->Cell(20, 6, $value->y, 'LR', 0, 'L', 0);
            $pdf->Ln();
            unset($data['respondent_age'][$key]);
            $count++;
            if($count==17) break;
        }
        $pdf->Cell(40, 0, '', 'T');
        $pdf->SetMargins(20, 5, 5);
        $pdf->Image('asset/images/respondent_age.png', 15, 170, 180, 100, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);

        
        $pdf->AddPage('P', 'A4');
        $pdf->setPage(3, true);

        $pdf->SetXY(15,40);
        $pdf->Cell(0, 10, '> Respondent Education Distribution', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Ln();
        $pdf->Cell(50, 6, 'Education Level', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 'Count', 1, 0, 'L', 0);
        $pdf->Ln();
        foreach ($data['respondent_education'] as $value) {
            $pdf->Cell(50, 6, $value->name, 'LR', 0, 'L', 0);
            $pdf->Cell(20, 6, $value->y, 'LR', 0, 'L', 0);
            $pdf->Ln();
        }
        $pdf->Cell(70, 0, '', 'T');

        $pdf->Image('asset/images/respondent_education.png', 100, 50, 80, 80, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);

        $pdf->SetXY(15,150);
        $pdf->Cell(0, 10, '> Village Distribution', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Ln();
        $pdf->Image('asset/images/village.png', 60, 160, 100, 100, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);
        ob_end_clean();
        $pdf->Output($form);
    }
}

