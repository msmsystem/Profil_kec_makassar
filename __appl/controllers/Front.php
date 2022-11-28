<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('encrypt');
		$this->host	= $this->config->item('base_url');
		$this->smarty->assign('host',$this->host);
		$this->smarty->assign("acak", md5(date('H:i:s')));
		$this->load->model('mfront');
	}
	
	public function index(){
		//echo "AA";
		$sql="SELECT * FROM cl_kategori WHERE pid IS NULL";
		$kat=$this->db->query($sql)->result_array();
		//echo $this->db->last_query();exit;
		foreach($kat as $x=>$v){
			$dt=$this->db->get_where('cl_kategori',array('pid'=>$v["id"]))->result_array();
			$kat[$x]["child"]=$dt;
		}
		$this->smarty->assign('kat',$kat);
		//echo "<pre>";
		//print_r($kat);exit;
		$this->smarty->display('front/home.html');
		
		
		
		
	}
	public function get_konten($p1){
		$temp='front/'.$p1.'.html';
		$this->smarty->assign('main',$temp);
		if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){
			$temp="konstruksi.html";
			return $this->smarty->display("front/home.html");
		}
		$sql="SELECT * FROM cl_kategori WHERE pid IS NULL";
		$kat=$this->db->query($sql)->result_array();
		foreach($kat as $x=>$v){
			$dt=$this->db->get_where('cl_kategori',array('pid'=>$v["id"]))->result_array();
			$kat[$x]["child"]=$dt;
		}
		$this->smarty->assign('kat',$kat);
		switch($p1){
			case "beranda":
				
			break;
		}
		
		$this->smarty->display("front/home.html");
	}
	

}
