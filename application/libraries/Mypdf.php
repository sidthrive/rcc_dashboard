<?php 
require_once APPPATH."/libraries/tcpdf.php";

class Mypdf extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = 'asset/images/sid.jpg';
        $this->Image($image_file, 10, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Position at 15 mm from bottom
        $this->SetX(20);
        // Title
        $this->Cell(0, 15, 'RCC Report', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetXY(20,18);
        $this->Cell(0, 15, 'Summit Institute of Development', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetXY(20,25);
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 15, 'Jl. Bung Hatta No. 28 Mataram - Nusa Tenggara Barat', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Line(5,31,200,31);

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}