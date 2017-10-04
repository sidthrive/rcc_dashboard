<?php

/*
 * copied from arjunphp.com
 * created by wind_raider_zero
 * 
 * license = GPL, and will always be free :D
 */

if(!defined('BASEPATH')) exit('no direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";

class PHPExcell extends PHPExcel{
    public function __construct() {
        parent::__construct();
    }
}