<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Mfront extends CI_Model{
	function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	
	function getdata($type="", $balikan="", $p1="", $p2=""){
		$where = " WHERE 1=1 ";
		$tgl_akhir=$this->input->post('tgl_akhir');
		$tgl_mulai=$this->input->post('tgl_mulai');
		switch($type){
			case "tbl_customer":
				if($this->input->post('key')){
					$where .=" AND (nama_customer like '%".$this->input->post('key')."%' OR 
							  telp_customer like '%".$this->input->post('key')."%' OR 
							  alamat_customer like '%".$this->input->post('key')."%')";
				}
				$sql="SELECT nama_customer,telp_customer,alamat_customer 
						FROM tbl_h_penjualan 
						".$where."
						GROUP BY nama_customer,telp_customer ";
			break;
		}
	}
}