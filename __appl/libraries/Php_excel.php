<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/libraries/PHPExcel/Classes/PHPExcel.php";
class Php_excel extends PHPExcel {
   public function __construct() {
      parent::__construct();
   }
}
?>