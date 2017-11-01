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
    // @param string     $form   the form that we want to generate the report, basically the array key from the $table_col
    public function downloadexcel($form=''){
        // load the library
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
        // set the Data sheet as the active sheet
        $fileObject->setActiveSheetIndexByName('Data');
        // initialize row iterator for sheet index 0 ( or the Data sheet) 
        // this will start at row 1 and colomn A
        $rowIterator = new PHPExcel_Worksheet_RowCellIterator($fileObject->getSheet(0));
        $chart_prop = [];
        // iterate the $table_col[$form] and save the values into the excel
        //and also generate the chart properties for every values
        foreach ($this->table_col[$form] as $col_name) {
            // get the data 
            $data = json_decode($this->getData($form, $col_name));
            // set thie title of data with the $col_name + " DATA"
            $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), strtoupper(str_replace('_', ' ', $col_name))." DATA");
            // proceed to the next row by initialize again the iterator with increasing row
            $rowIterator = new PHPExcel_Worksheet_RowCellIterator($fileObject->getSheet(0),++$row);
            // set the table header with $col_name
            $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), ucwords(str_replace('_', ' ', $col_name)));
            // proceed to the next colomn
            $rowIterator->next();
            // save the current colomn and row for chart label start
            $chart_prop[$col_name]['label']['start'] = $rowIterator->current()->getCoordinate();
            // iterate the data
            foreach ($data as $key => $d) {
                // set the data $d->name value to the excel current coordinate
                $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), ucwords(str_replace('_', ' ', $d->name)));
                // update the chart label end with the current coordinate
                $chart_prop[$col_name]['label']['end'] = $rowIterator->current()->getCoordinate();
                // proceed to next colomn
                $rowIterator->next();
            }

            // proceed to the next row
            $rowIterator = new PHPExcel_Worksheet_RowCellIterator($fileObject->getSheet(0),++$row);
            // set the header with "Value"
            $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), ucwords(str_replace('_', ' ', "Value")));
            // proceed to next colomn
            $rowIterator->next();
            // save the current coordinate for chart data start
            $chart_prop[$col_name]['data']['start'] = $rowIterator->current()->getCoordinate();
            // iterate again the data
            foreach ($data as $key => $d) {
                // set the data $d->y value to the excel current coordinate
                $fileObject->getActiveSheet()->setCellValue($rowIterator->current()->getCoordinate(), ucwords(str_replace('_', ' ', $d->y)));
                // update the chart data end with the current coordinate
                $chart_prop[$col_name]['data']['end'] = $rowIterator->current()->getCoordinate();
                // proceed to next colomn
                $rowIterator->next();
            }
            // increase the row
            $row++;
            // procced to next row to give space between table
            $rowIterator = new PHPExcel_Worksheet_RowCellIterator($fileObject->getSheet(0),++$row);
        }

        // create worksheet object so we can add it into excel object
        // this worksheet will contain the graph
        $myWorkSheet = new PHPExcel_Worksheet($fileObject, 'Graph');
        // add the worksheet object into the excel object
        $fileObject->addSheet($myWorkSheet);
        // set it the active sheet
        $fileObject->setActiveSheetIndexByName('Graph');
        // initialize the initial size of the graph
        // this will determine the graph size
        // example this graph size will be B2 until J17
        $col_s = 'B';
        $row_s = 2;
        $col_e = 'J';
        $row_e = 17;

        // iterate the chart properties that we set earlier
        foreach ($chart_prop as $name => $d) {
            // create few instances of chart data series values objects
            // the chart data series vales can be a string or link from data on the excel sheet

            // set the chart data series label
            $dsl = [new PHPExcel_Chart_DataSeriesValues('String', ucwords(str_replace('_', ' ', $name)) )];
            // set the range value of the label
            $xal = [new PHPExcel_Chart_DataSeriesValues('String', 'Data!'.$d['label']['start'].':'.$d['label']['end'])];
            // set the range value of the data
            $dsv = [new PHPExcel_Chart_DataSeriesValues('Number', 'Data!'.$d['data']['start'].':'.$d['data']['end'])];

            // initialize a chart data series with the chart data series values
            $ds = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_PIECHART,null,range(0, count($dsv)-1),$dsl,$xal,$dsv);
            // put the chart data series into chart plot area
            $pa = new PHPExcel_Chart_PlotArea(NULL, array($ds));
            // create chart legend
            $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
            // create chart titile
            $title = new PHPExcel_Chart_Title(ucwords(str_replace('_', ' ', $name)));
            // put all into chart object
            $chart = new PHPExcel_Chart($name,$title,$legend,$pa,true,0,NULL,NULL);

            // set the position of the chart
            $chart->setTopLeftPosition($col_s.$row_s);
            $chart->setBottomRightPosition($col_e.$row_e);
            // update position for the next chart
            $row_s = $row_s + 16;
            $row_e = $row_e + 16;
            // add the chart to excel
            $fileObject->getActiveSheet()->addChart($chart);
        }

        // clean the output buffer so the excel download will not get error
        ob_end_clean();

        // set the header content with document type and filename
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$form.'.xlsx"'); 
        header('Cache-Control: max-age=0');

        // initialize Writer object
        $saveContainer = PHPExcel_IOFactory::createWriter($fileObject,'Excel2007');
        // set the include chart to true so it will include the chart (this is a must)
        $saveContainer->setIncludeCharts(TRUE);
        // send the excel to output
        $saveContainer->save('php://output');
    }

    // This method will generate pdf file using TCPDF library
    // you can have more example here : https://tcpdf.org/examples/
    // @param string    $form   the form that we want to generate the report, basically the array key from the $table_col
    public function downloadpdf($form=''){
        // we set this to static because this is just example
        $form = 'unique_identifier';

        // get all the data from the $table_col[$form]
        $data = [];
        foreach ($this->table_col[$form] as $col_name) {
            $data[$col_name] = json_decode($this->getData($form, $col_name));
        }

        // load tcpdf library
        // I use my own extension of the library because I need to use my own header and footer function
        // Please see application/libraries/Mypdf.php to see the detail
        // To see explanation of each function, please see application/libraries/tcpdf.php
        $this->load->library('mypdf');
        // initialize the pdf object
        // the static variable like PDF_PAGE_ORIENTATION etc are defined in application/libraries/config/tcpdf_config.php
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set the creator
        $pdf->SetCreator(PDF_CREATOR);
        // set the author
        $pdf->SetAuthor('SID');
        // set the titile
        $pdf->SetTitle(strtoupper(str_replace('_', ' ', $form)));

        // set the header
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
        
        // add a A4 page with Potrait orientation
        $pdf->AddPage('P', 'A4');
        //set the page as the active page
        $pdf->setPage(1, true);
        // set the current X and Y coordinate to write the pdf
        $pdf->SetXY(15,40);

        // set the title of the table
        $pdf->Cell(0, 10, '> Child Age Distribution', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        // set a new line
        $pdf->Ln();

        // this following code basically create a table to pdf page
        $pdf->Cell(20, 6, 'Age', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 'Count', 1, 0, 'L', 0);
        $pdf->Ln();
        foreach ($data['child_age'] as $value) {
            $pdf->Cell(20, 6, $value->name, 'LR', 0, 'L', 0);
            $pdf->Cell(20, 6, $value->y, 'LR', 0, 'L', 0);
            $pdf->Ln();
        }
        $pdf->Cell(40, 0, '', 'T');

        // put image into the pdf page
        $pdf->Image('asset/images/child_age.png', 75, 50, 100, 100, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);

        // set new coordnate for the next data
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

        // add new page
        $pdf->AddPage('P', 'A4');
        // set as active
        $pdf->setPage(2, true);

        // set the coordnate
        $pdf->SetXY(15,40);
        $pdf->Cell(0, 10, '> Respondent Age Distribution', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Ln();

        // this will create a three tables
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

        //set the margin for next table, so it will sit next to the previous table
        $pdf->SetMargins(70, 5, 5);
        // set the next coordnate
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

        //set the margin for next table, so it will sit next to the previous table
        $pdf->SetMargins(120, 5, 5);
        // set the next coordnate
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
        // revert back the margin to normal
        $pdf->SetMargins(20, 5, 5);
        $pdf->Image('asset/images/respondent_age.png', 15, 170, 180, 100, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);

        // set a new page
        $pdf->AddPage('P', 'A4');
        // set as active
        $pdf->setPage(3, true);

        $pdf->SetXY(15,40);
        $pdf->Cell(0, 10, '> Respondent Education Distribution', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Ln();

        // create the table
        $pdf->Cell(50, 6, 'Education Level', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 'Count', 1, 0, 'L', 0);
        $pdf->Ln();
        foreach ($data['respondent_education'] as $value) {
            $pdf->Cell(50, 6, $value->name, 'LR', 0, 'L', 0);
            $pdf->Cell(20, 6, $value->y, 'LR', 0, 'L', 0);
            $pdf->Ln();
        }
        $pdf->Cell(70, 0, '', 'T');
        // add the image
        $pdf->Image('asset/images/respondent_education.png', 100, 50, 80, 80, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);

        $pdf->SetXY(15,150);
        $pdf->Cell(0, 10, '> Village Distribution', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Ln();
        $pdf->Image('asset/images/village.png', 60, 160, 100, 100, 'PNG', "", '', true, 150, '', false, false, 1, false, false, false);

        // dont forget to clean output buffer before send the pdf
        ob_end_clean();
        // send the pdf to output
        $pdf->Output($form);
    }
}

