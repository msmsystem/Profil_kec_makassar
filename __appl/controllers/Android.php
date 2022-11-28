<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Android extends CI_Controller {
	function __construct(){
		parent::__construct();
		header("Expires: Mon, 26 Jul 1999 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("If-Modified-Since: Mon, 22 Jan 2008 00:00:00 GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Cache-Control: private");
		header("Pragma: no-cache");
		header('Access-Control-Allow-Origin: *');
		
		$this->host	= $this->config->item('base_url');
		$this->smarty->assign('host',$this->host);
		//$this->smarty->assign('auth', $this->auth);
		$this->load->model(array('mandroid'));
		$this->load->library(array('lib','encrypt'));
		$this->smarty->assign("main_css", $this->lib->assetsmanager('css','main') );
		$this->smarty->assign("main_js", $this->lib->assetsmanager('js','main') );
		//$this->smarty->assign("setting", $this->setting );
		$this->smarty->assign("acak", md5(date('H:i:s')));
	  }
	
	function index(){
		echo "XXX";
	}
    public function get_konten($p1="",$p2="",$p3="",$p4="",$p5="",$p6="")
	{
		//echo "XXX";exit;
		if($p1=='chart_desa')$_POST["desa"]=$p2;
		$jumlah_penduduk = $this->db->get_where('tbl_data_penduduk', array('status_data'=>'AKTIF'))->num_rows();
		$jumlah_kk = $this->db->get('tbl_kartu_keluarga')->num_rows();
		$jumlah_surat = $this->db->get('tbl_data_surat')->num_rows();
		
		$summary_persuratan = $this->mandroid->getdata('summary_persuratan', 'result_array');
		$summary_persuratan_desa = $this->mandroid->getdata('summary_persuratan_desa', 'result_array');
		
		$jenis_kelamin = $this->mandroid->getdata_laporan('dashboard_jenis_kelamin', 'result_array');
		$agama = $this->mandroid->getdata_laporan('dashboard_agama', 'result_array');
		$range_umur = $this->mandroid->getdata_laporan('dashboard_range_umur', 'result_array');
		
		$status_data = $this->mandroid->getdata_laporan('dashboard_status_data', 'result_array');
		$status_kawin = $this->mandroid->getdata_laporan('dashboard_status_kawin', 'result_array');
		$pendidikan = $this->mandroid->getdata_laporan('dashboard_pendidikan', 'result_array');
		
		$data = array(
			'jumlah_penduduk' => $jumlah_penduduk,
			'jumlah_kk' => $jumlah_kk,
			'jumlah_surat' => $jumlah_surat,
			
			'summary_persuratan' => $summary_persuratan,
			'summary_persuratan_desa' => $summary_persuratan_desa,
			
			'jenis_kelamin' => $jenis_kelamin,
			'agama' => $agama,
			'range_umur' => $range_umur,
			
			'status_data' => $status_data,
			'status_kawin' => $status_kawin,
			'pendidikan' => $pendidikan,
		);
		
		$this->smarty->assign('data',$data);
		$desa=$this->db->get('cl_kelurahan_desa')->result_array();
		$this->smarty->assign('desa',$desa);
		switch($p1){
			case "pelayanan":
				
			break;
			case "profil":
				$profil=$this->db->get('tbl_profil')->row_array();
				$this->smarty->assign('profil',$profil);
			break;
		}
		$temp=$p1.".html";
		//echo $temp;exit;
		if(!file_exists($this->config->item('appl').APPPATH.'views/android/'.$temp)){$this->smarty->display("konstruksi.html");}
		else{
			$this->smarty->assign('temp',$temp);
			$this->smarty->display('android/main.html');
		}
		
	}
	function getdata($p1){
		echo $this->mandroid->getdata($p1,"json");
	}
	function cruddata($p1){
		$post = array();
        foreach($_POST as $k=>$v){
			if($this->input->post($k)!=""){
				$post[$k] = $this->input->post($k);
			}
			
		}
		echo $this->mandroid->simpan_data($p1, $post);
	}
	
	
	function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
		// Declare an empty array 
		$array = array(); 
		  
		// Variable that store the date interval 
		// of period 1 day 
		$interval = new DateInterval('P1D'); 
	  
		$realEnd = new DateTime($end); 
		$realEnd->add($interval); 
	  
		$period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
	  
		// Use loop to store date into array 
		foreach($period as $date) {                  
			$array[] = $date->format($format);  
		} 
	  
		// Return the array elements 
		return $array; 
	} 
	
	function reg($imei){
		
		$prov=$this->db->get('cl_provinsi')->result_array();
		$this->smarty->assign('provinsi',$prov);
		$this->smarty->assign('imei',$imei);
		$this->smarty->assign('temp',"reg.html");
		$this->smarty->display('android/main.html');
	}
	function get_combo(){
		$mod=$this->input->post('v');
		switch($mod){
			case "cl_provinsi_id":
				$sql="SELECT id,nama as txt FROM cl_provinsi ";
				$dt=$this->db->query($sql)->result_array();
				$opt="<option value=''>-- Pilih Provinsi -- </option>";
			break;
			case "cl_kab_kota_id":
				$sql="SELECT id,nama as txt FROM cl_kab_kota WHERE provinsi_id='".$this->input->post('v2')."' ";
				$dt=$this->db->query($sql)->result_array();
				$opt="<option value=''>-- Pilih Kab Kota --</option>";
			break;
			case "cl_kecamatan_id":
				$sql="SELECT id,nama as txt FROM cl_kecamatan WHERE kab_kota_id='".$this->input->post('v2')."' ";
				$dt=$this->db->query($sql)->result_array();
				$opt="<option value=''>-- Pilih Kecamatan --</option>";
			break;
			case "ip_server":
				$sql="SELECT host as id,concat(nama_server, ' - ',ip_server) as txt FROM tbl_seting_android_server 
					  WHERE cl_provinsi_id='".$this->input->post('v2')."' 
					  AND cl_kab_kota_id='".$this->input->post('v3')."' 
					  AND cl_kecamatan_id='".$this->input->post('v4')."' ";
				$dt=$this->db->query($sql)->result_array();
				$opt="<option value=''>-- Pilih Server --</option>";
			break;
		}
		
		foreach($dt as $x=>$v){
			$opt .='<option value="'.$v["id"].'">'.$v["txt"].'</option>';
		}
		
		echo $opt;
		
		
	}
	
}