<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends CI_Controller {
	function __construct(){
        parent::__construct();
		header("Expires: Mon, 26 Jul 1999 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("If-Modified-Since: Mon, 22 Jan 2008 00:00:00 GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Cache-Control: private");
		header("Pragma: no-cache");
		$this->auth = unserialize(base64_decode($this->session->userdata($this->config->item('user_data'))));
		$this->host	= $this->config->item('base_url');
		//echo $this->host;exit;
		$this->smarty->assign('host',$this->host);
		$this->smarty->assign('auth', $this->auth);
		$this->load->model('mmodul');
		$this->load->library(array('lib','encrypt','php_excel'));
		$this->smarty->assign("acak", md5(date('H:i:s')));
		date_default_timezone_set('Asia/Jakarta');
		
	}
	public function index()
	{
		if($this->auth){
			/*$data = $this->mmodul->getdata('list_dash','result_array');
			$flag=array();
			foreach ($data as $v=>$k){
				$flag[$k['flag']]=$k["jml"];
			}
			$this->smarty->assign('flag', $flag);
			$this->smarty->assign('data', $data);
			*/
			
			//$data=$this->mmodul->getdata('get_dashboard','result_array');
			//echo "<pre>";print_r($data);exit;
			//$this->smarty->assign("y", $y);
			//$this->smarty->assign("x", $x);
			//$this->smarty->assign("data", $data);
			//$this->smarty->display('home.html');
			//$this->load->view('home.html',$data);
			$this->get_grid('profil');
			
			//$this->smarty->display('main.html');
		}else{
			if($this->session->flashdata('error')){
				$this->smarty->assign("error", $this->session->flashdata('error'));
			}
			//$this->smarty->display('main-login.html');
			$this->smarty->display('main-login.html');
		}
		
		//$this->smarty->display('home.html');
	}
	function save(){
		echo "<pre>";print_r($_POST);
	}
	
	function modul($p1,$p2){
		
		if($this->auth){
			$this->smarty->assign("main", $p1);
			$this->smarty->assign("mod", $p2);
			$temp='template/'.$p2.'.html';
			if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){$this->smarty->display('konstruksi.html');}
			else{$this->smarty->display($temp);}	
		}
	}	
	function get_konten($p1=""){
		$sts="add";
		if($this->input->post('editstatus'))$sts=$this->input->post('editstatus');
		$this->smarty->assign("sts_crud", $sts);
		$this->smarty->assign("mod", $p1);
		$temp='main/'.$p1.'.html';
		if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){$temp="konstruksi.html";return $this->smarty->display($temp);}
		switch($p1){
			case "form_ganti_pwd":
				return $this->smarty->display($temp);
			break;
			case "profil":
				$server=$this->mmodul->getdata('ip2','result_array');
				$this->smarty->assign("server", $server);
				if($this->input->post('editstatus')=='edit'){
					$data=$this->mmodul->getdata('profil','row_array','edit');
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			case "kab_kota":
			case "kec":
			case "desa":
			case "prov":
				$prov=$this->mmodul->getdata('prov','result_array');
				$this->smarty->assign("prov", $prov);
				if($this->input->post('editstatus')=='edit'){
					$data=$this->mmodul->getdata($p1,'row_array','edit');
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			
			case "set_server":
				$prov=$this->mmodul->getdata('prov','result_array');
				$this->smarty->assign("prov", $prov);
				if($this->input->post('editstatus')=='edit'){
					$data=$this->mmodul->getdata('tbl_seting_android_server','row_array','edit');
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			case "pengguna":
				$prov=$this->mmodul->getdata('prov','result_array');
				$this->smarty->assign("prov", $prov);
				if($this->input->post('editstatus')=='edit'){
					$data=$this->mmodul->getdata('tbl_seting_android','row_array','edit');
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			case "dt_user":
				$id_dep=$this->input->post('id_dep');
				$this->smarty->assign("id_dep", $id_dep);
				return $this->smarty->display($temp);
			break;
			
			case "user":
				$group=$this->mmodul->getdata('user_group','result_array');
				$jabatan=$this->mmodul->getdata('jabatan','result_array');
				$departemen=$this->mmodul->getdata('departemen','result_array');
				if($this->input->post('editstatus')=='edit'){
					$data=$this->db->get_where('tbl_user',array('id'=>$this->input->post('id')))->row_array();
					$this->smarty->assign("data", $data);
				}
				$this->smarty->assign("group", $group);
				$this->smarty->assign("jabatan", $jabatan);
				$this->smarty->assign("departemen", $departemen);
				return $this->smarty->display($temp);
			break;
			case "pemasukan_anggaran":
				if($this->input->post('editstatus')=='edit'){
					$data=$this->db->get_where('tbl_pemasukan_anggaran',array('id'=>$this->input->post('id')))->row_array();
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			case "lokasi":
				if($this->input->post('editstatus')=='edit'){
					$data=$this->db->get_where('cl_lokasi',array('id'=>$this->input->post('id')))->row_array();
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			case "user_group":
				if($this->input->post('editstatus')=='edit'){
					$data=$this->db->get_where('cl_user_group',array('id'=>$this->input->post('id')))->row_array();
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			case "jabatan":
				if($this->input->post('editstatus')=='edit'){
					$data=$this->db->get_where('cl_jabatan',array('id'=>$this->input->post('id')))->row_array();
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			case "departemen":
				if($this->input->post('editstatus')=='edit'){
					$data=$this->db->get_where('cl_departemen',array('id'=>$this->input->post('id')))->row_array();
					$this->smarty->assign("data", $data);
				}
				return $this->smarty->display($temp);
			break;
			
			case "project":
				$lokasi=$this->db->get('cl_lokasi')->result_array();
				$rm=$this->konversi_romawi((int)date('m'));
				if($this->input->post('editstatus')=='edit'){
					$data=$this->db->get_where('cl_project',array('kode_project'=>$this->input->post('id')))->row_array();
					$pecah=explode('/',$data["kode_project"]);
					$kdlok=$pecah[0];
					$type=substr($pecah[0],0,1);
					$xx=explode('-',$pecah[1]);
					$nm="";
					$a=count($xx)-1;
					foreach($xx as $x=>$v){
						if($x!=0){
							if($x==$a)
								$nm .=$v;
							else
								$nm .=$v.'-';
						}
						//$a++;
					}
					
					
					/*$xx=explode('-',$pecah[1]);
					$type=$xx[0];
					$nm=$xx[1];
					*/
					$bln=$pecah[2];
					$thn=$pecah[3];
					$this->smarty->assign("kdlok", $kdlok);
					$this->smarty->assign("type", $type);
					$this->smarty->assign("nm", $nm);
					$this->smarty->assign("bln", $bln);
					$this->smarty->assign("thn", $thn);
					$this->smarty->assign("data", $data);
				}
				$this->smarty->assign("lok", $lokasi);
				$this->smarty->assign("rm", $rm);
				return $this->smarty->display($temp);
			break;
			case "pengajuan_anggaran":
				if($this->input->post('editstatus')=='edit'){
					$data=$this->mmodul->getdata('pengajuan_anggaran_edit','row_array','edit');
					//echo "<pre>";print_r($data);
					$this->smarty->assign("data", $data);
					$this->smarty->assign("jml_row",count($data["d"]));
				}
				return $this->smarty->display($temp);
			break;
			case "po":
				$size=$this->db->get('cl_size')->result_array();
				$this->smarty->assign("size", $size);
				if($this->input->post('editstatus')=='edit'){
					$data=$this->mmodul->getdata('po_edit','row_array','edit');
					//$file_po=$this->db->get_where('tbl_file_po',array('tbl_h_po_id'=>$this->input->post('id')))->result_array();
					//echo "<pre>";print_r($data);
					$this->smarty->assign("data", $data);
					//$this->smarty->assign("file_po", $file_po);
					$this->smarty->assign("jml_row",count($data["d"]));
					//$this->smarty->assign("jml_row_temin",count($data["termin"]));
				}
				return $this->smarty->display($temp);
			break;
			default:
				if($this->input->post('editstatus')=='edit'){
					$data=$this->mmodul->getdata($p1,'row_array','edit');
					$this->smarty->assign("data", $data);
				}
			break;
		}
		//$temp='main/'.$p1.'.html';
		$this->smarty->assign("temp", $temp);
		$this->smarty->display('home.html');
	}
	function get_grid($p1){
		$temp='template/main.html';
		//$sup=$this->mmodul->getdata('cl_supplier','result_array');
		//$this->smarty->assign("sup", $sup);
		$this->smarty->assign("temp", $temp);
		$this->smarty->assign("main", $p1);
		$this->smarty->assign("mod", $p1);
		$this->smarty->display('home.html');
	}
	function stok(){
		$temp='main/stok.html';
		$size=$this->db->get('cl_size')->result_array();
		$sup=$this->mmodul->getdata('cl_supplier','result_array');
		$this->smarty->assign("sup", $sup);
		$this->smarty->assign("size", $size);
		$this->smarty->assign("temp", $temp);
		$this->smarty->assign("mod", "stok");
		$this->smarty->assign("main", "stok");
		$this->smarty->display('home.html');
	}
	function getdata($p1){
		echo $this->mmodul->getdata($p1,"json");
	}	
	function cruddata($p1){
		$post = array();
        foreach($_POST as $k=>$v){
			if($this->input->post($k)!=""){
				//$post[$k] = $this->db->escape_str($this->input->post($k));
				$post[$k] = $this->input->post($k);
			}
			
		}
		echo $this->mmodul->simpan_data($p1, $post);
	}
	function tes(){
		$sql="SELECT * FROM tbl_jawaban_responden 
			  WHERE tingkat_kepentingan IS NOT NULL AND tingkat_kinerja IS NOT NULL
			  ORDER BY tbl_responden_id,tbl_pertanyaan_id ASC";
		$res=$this->db->query($sql)->result_array();
		$kat=$this->mmodul->getdata("get_kategori","result_array");
		$resp=$this->mmodul->getdata("responden","result_array");
		$rata=$this->mmodul->getdata("get_rata_rata","result_array");
		$total=$this->mmodul->getdata("get_total","result_array");
		$data=array();
		foreach($res as $v=>$x){
			if(isset($data[$x["tbl_responden_id"]]))$data[$x["tbl_responden_id"]][$x["tbl_pertanyaan_id"]]=array('tk'=>$x["tingkat_kepentingan"],'tn'=>$x["tingkat_kinerja"]);
			else{
				$data[$x["tbl_responden_id"]]=array();
				$data[$x["tbl_responden_id"]][$x["tbl_pertanyaan_id"]]=array('tk'=>$x["tingkat_kepentingan"],'tn'=>$x["tingkat_kinerja"]);
			}
				
			
		}
		$this->smarty->assign("rata", $rata);
		$this->smarty->assign("total", $total);
		$this->smarty->assign("resp", $resp);
		$this->smarty->assign("kat", $kat);
		$this->smarty->assign("data", $data);
		$this->smarty->display('bo/rekap.html');
		echo "<pre>";print_r($data);
	}
	
	
	function export_excel(){
		$mod=$this->input->post('mod');
		//print_r($_POST);exit;
		switch($mod){
			case "customer":
				$arrCol[] = array('urutan'=>2, 'nilai'=>'Nama Customer','fontsize'=> '12', 'bold'=>true,'namanya'=>'nama_customer','format'=>'string');
				$arrCol[] = array('urutan'=>3, 'nilai'=>'Telp','fontsize'=> '12', 'bold'=>true,'namanya'=>'telp_customer','format'=>'string');
				$arrCol[] = array('urutan'=>4, 'nilai'=>'Alamat','fontsize'=> '12', 'bold'=>true,'namanya'=>'alamat_customer','format'=>'string');
				$rsl = $this->mmodul->getdata('tbl_customer','result_array');
				$arrExcel = array('sNAMESS'=>'DOC', 'sFILNAM'=>'customer_'.date('YmdHis'),'col'=>$arrCol, 'rsl'=>$rsl);
			break;
			case "input":
				//$arrCol[] = array('urutan'=>1, 'nilai'=>'No.','fontsize'=> '12', 'bold'=>true, 'namanya'=>'rowID', 'format'=>'string');
				$arrCol[] = array('urutan'=>2, 'nilai'=>'No Penawaran','fontsize'=> '12', 'bold'=>true,'namanya'=>'no_penawaran','format'=>'string');
				$arrCol[] = array('urutan'=>3, 'nilai'=>'Kantor Cabang/Pusat','fontsize'=> '12', 'bold'=>true,'namanya'=>'kantor_cabang','format'=>'string');
				$arrCol[] = array('urutan'=>4, 'nilai'=>'Pemberi Tugas','fontsize'=> '12', 'bold'=>true,'namanya'=>'nama_pemberi_tugas','format'=>'string');
				$arrCol[] = array('urutan'=>5, 'nilai'=>'Alamat','fontsize'=> '12', 'bold'=>true,'namanya'=>'alamat','format'=>'string');
				$arrCol[] = array('urutan'=>6, 'nilai'=>'Telp.','fontsize'=> '12', 'bold'=>true, 'namanya'=>'tlp','format'=>'string');
				$arrCol[] = array('urutan'=>7, 'nilai'=>'Pengguna Laporan','fontsize'=> '12', 'bold'=>true, 'namanya'=>'nama_pengguna_laporan','format'=>'string');
				$arrCol[] = array('urutan'=>8, 'nilai'=>'Alamat','fontsize'=> '12', 'bold'=>true, 'namanya'=>'alamat_pengguna_laporan','format'=>'string');
				$arrCol[] = array('urutan'=>9, 'nilai'=>'Telp.','fontsize'=> '12', 'bold'=>true, 'namanya'=>'tlp_pengguna_laporan','format'=>'string');
				$arrCol[] = array('urutan'=>10, 'nilai'=>'Email','fontsize'=> '12', 'bold'=>true, 'namanya'=>'email','format'=>'string');
				$arrCol[] = array('urutan'=>11, 'nilai'=>'Objek Penilain','fontsize'=> '12', 'bold'=>true, 'namanya'=>'obj_penilaian','format'=>'string');
				$arrCol[] = array('urutan'=>12, 'nilai'=>'Luas Tanah','fontsize'=> '12', 'bold'=>true, 'namanya'=>'luas_tanah','format'=>'string');
				$arrCol[] = array('urutan'=>13, 'nilai'=>'Luas Banunan','fontsize'=> '12', 'bold'=>true, 'namanya'=>'luas_bangunan','format'=>'string');
				$arrCol[] = array('urutan'=>14, 'nilai'=>'Alamat Objek','fontsize'=> '12', 'bold'=>true, 'namanya'=>'alamat_obj','format'=>'string');
				$arrCol[] = array('urutan'=>15, 'nilai'=>'FEE Penawaran','fontsize'=> '12', 'bold'=>true, 'namanya'=>'fee_projek','format'=>'money');
				$arrCol[] = array('urutan'=>16, 'nilai'=>'PPN(10%)','fontsize'=> '12', 'bold'=>true, 'namanya'=>'ppn','format'=>'string');
				$arrCol[] = array('urutan'=>17, 'nilai'=>'PPH(1.2%)','fontsize'=> '12', 'bold'=>true, 'namanya'=>'pph','format'=>'string');
				$arrCol[] = array('urutan'=>18, 'nilai'=>'FEE Marketing(%)','fontsize'=> '12', 'bold'=>true, 'namanya'=>'fee_marketing','format'=>'string');
				$arrCol[] = array('urutan'=>19, 'nilai'=>'FEE Marketing(Rp)','fontsize'=> '12', 'bold'=>true, 'namanya'=>'fee_marketing_rp','format'=>'string');
				$arrCol[] = array('urutan'=>20, 'nilai'=>'Commitmen Fee(%)','fontsize'=> '12', 'bold'=>true, 'namanya'=>'comitmen_fee','format'=>'string');
				$arrCol[] = array('urutan'=>21, 'nilai'=>'Commitmen Fee(Rp)','fontsize'=> '12', 'bold'=>true, 'namanya'=>'commitmen_fee_rp','format'=>'string');
				//$rsl = $this->db->get('vpti2_log_upload_sln')->result_array();
				$rsl = $this->mmodul->getdata('projek','result_array');
				$arrExcel = array('sNAMESS'=>'DLS', 'sFILNAM'=>'dls_'.date('YmdHis'),'col'=>$arrCol, 'rsl'=>$rsl);
			break;
			case "report_doc":
				$arrCol[] = array('urutan'=>1, 'nilai'=>'No.','fontsize'=> '12', 'bold'=>true, 'namanya'=>'rowID', 'format'=>'string');
				$arrCol[] = array('urutan'=>2, 'nilai'=>'Vo. Number','fontsize'=> '12', 'bold'=>true,'namanya'=>'vo_number','format'=>'string');
				$arrCol[] = array('urutan'=>3, 'nilai'=>'LS. Number','fontsize'=> '12', 'bold'=>true,'namanya'=>'ls_number','format'=>'string');
				$arrCol[] = array('urutan'=>4, 'nilai'=>'Commodity','fontsize'=> '12', 'bold'=>true,'namanya'=>'description2','format'=>'string');
				$arrCol[] = array('urutan'=>5, 'nilai'=>'Partial','fontsize'=> '12', 'bold'=>true,'namanya'=>'partial','format'=>'string');
				$arrCol[] = array('urutan'=>6, 'nilai'=>'File','fontsize'=> '12', 'bold'=>true,'namanya'=>'nama_file','format'=>'string');
				$arrCol[] = array('urutan'=>7, 'nilai'=>'Upload Date','fontsize'=> '12', 'bold'=>true, 'namanya'=>'tanggal_upload','format'=>'datetime');
				//$rsl = $this->db->get('vpti2_log_upload_sln')->result_array();
				$rsl = $this->mkso->getdata('histori_doc','report');
				$arrExcel = array('sNAMESS'=>'DOC', 'sFILNAM'=>'doc_'.date('YmdHis'),'col'=>$arrCol, 'rsl'=>$rsl);
			break;
			case "report_vo_xml":
				$arrCol[] = array('urutan'=>1, 'nilai'=>'No.','fontsize'=> '12', 'bold'=>true, 'namanya'=>'rowID', 'format'=>'string');
				$arrCol[] = array('urutan'=>2, 'nilai'=>'Vo. Number','fontsize'=> '12', 'bold'=>true,'namanya'=>'vo_number','format'=>'string');
				$arrCol[] = array('urutan'=>3, 'nilai'=>'Download Date','fontsize'=> '12', 'bold'=>true, 'namanya'=>'tanggal_download_vo','format'=>'datetime');
				$arrCol[] = array('urutan'=>4, 'nilai'=>'Tot. Download','fontsize'=> '12', 'bold'=>true,'namanya'=>'jml_download_vo','format'=>'string');
				$arrCol[] = array('urutan'=>5, 'nilai'=>'Download Rev. Date','fontsize'=> '12', 'bold'=>true, 'namanya'=>'tanggal_download_vo_rev','format'=>'datetime');
				$arrCol[] = array('urutan'=>6, 'nilai'=>'Tot. Download Rev.','fontsize'=> '12', 'bold'=>true,'namanya'=>'jml_download_vo_rev','format'=>'string');
				$arrCol[] = array('urutan'=>7, 'nilai'=>'Notes','fontsize'=> '12', 'bold'=>true,'namanya'=>'remark_to_inspec_office','format'=>'string');
				//$rsl = $this->db->get('vpti2_log_upload_sln')->result_array();
				$rsl = $this->mkso->getdata('histori_vo','report');
				$arrExcel = array('sNAMESS'=>'List', 'sFILNAM'=>'download_vo_'.date('YmdHis'),'col'=>$arrCol, 'rsl'=>$rsl);
			break;
		}

		$this->lib->buat_excel($arrExcel);	
		//number_to_words();
	} 
	function get_combo(){
		$mod=$this->input->post('v');
		$val=$this->input->post('v3');
		$bind=$this->input->post('v2');
		$data=$this->mmodul->getdata($mod,'result_array','get',$bind);
		$opt="<option value=''>--Pilih--</option>";
		
		foreach($data as $v){
			if($v['id']==$val)$sel="selected"; else $sel="";
			$opt .="<option value='".$v['id']."' ".$sel.">".$v['txt']."</option>";
		}
		echo $opt;
	}
	function download_temp($p1=""){
		$this->load->helper('download');
		switch($p1){
			case "formulir":
				$data = file_get_contents('/opt/aplikasi/eproc/__repo/template/formulir_keikutsertaan.doc');
				$name = 'formulir_keikutsertaan.doc';
			break;
			case "pakta_integritas":
				$data = file_get_contents('/opt/aplikasi/eproc/__repo/template/pakta_integritas.doc');
				$name = 'pakta_integritas.doc';
			break;
			case "surat_kuasa":
				$data = file_get_contents('/opt/aplikasi/eproc/__repo/template/surat_kuasa.doc');
				$name = 'surat_kuasa.doc';
			break;
			case "minat":
				$data = file_get_contents('/opt/aplikasi/eproc/__repo/template/surat_pernyataan_minat.doc');
				$name = 'surat_pernyataan_minat.doc';
			break;
		}
		if(!file_exists('__repo/template/'.$name)){
			echo "Ooooppppsss File NOT EXIST !!!!!";
		}else{
			force_download($name, $data);
		}
	}
	function appr($p1){
		switch($p1){
			case "pengajuan_po":
				$temp='main/approve_pengajuan_po.html';
				$data=$this->mmodul->getdata('pengajuan_po_revisi','row_array','edit');
			break;
			case "pengajuan_anggaran":
				$temp='main/approve.html';
				$data=$this->mmodul->getdata('pengajuan_anggaran_revisi','row_array','edit');
			break;
			case "po":
				$temp='main/approve_po.html';
				$data=$this->mmodul->getdata('po_revisi','row_array','edit');
				$file_po=$this->db->get_where('tbl_file_po',array('tbl_h_po_id'=>$this->input->post('id')))->result_array();
					//echo "<pre>";print_r($data);
				$this->smarty->assign("file_po", $file_po);
			break;
		}
		
		//echo "<pre>";print_r($data);
		$this->smarty->assign("data", $data);
		$this->smarty->assign("jml_row",count($data["d"]));
		$this->smarty->assign("mod", "appr");
		return $this->smarty->display($temp);
	}
	function revisi(){
		$temp='main/revisi.html';
		$data=$this->mmodul->getdata('pengajuan_anggaran_revisi','row_array','edit');
		//$data=$this->mmodul->getdata('pengajuan_anggaran_edit','row_array','edit');
		//echo "<pre>";print_r($data);
		$this->smarty->assign("data", $data);
		$this->smarty->assign("jml_row",count($data["d"]));
		//$data=$this->mmodul->getdata('pengajuan_anggaran_edit','row_array','edit');
		//echo "<pre>";print_r($data);
		//$this->smarty->assign("data", $data);
		//$this->smarty->assign("jml_row",count($data["d"]));
		$this->smarty->assign("mod", "revisi");
		return $this->smarty->display($temp);
	}
	function cetak($p1,$data="",$html=""){
		$this->load->library('mlpdf');		
		$htmlheader = $this->smarty->fetch("cetak/header.html");
		//echo $htmlheader;exit;
		$this->smarty->assign('mod',$p1);
		$kertas='P';
		switch($p1){
			case "label":
				$data=$this->mmodul->getdata('penjualan_edit');
				$this->smarty->assign('data',$data);
				$htmlheader="";
				$htmlcontent = $this->smarty->fetch("cetak/label.html");
			break;
			case "pengajuan_po":
				$data=$this->mmodul->getdata('pengajuan_po_edit');
				$ttd=$this->mmodul->getdata('get_ttd_pengajuan_po');
				$this->smarty->assign('data',$data);
				$this->smarty->assign('ttd',$ttd);
				$htmlcontent = $this->smarty->fetch("cetak/pengajuan_po.html");
			break;
			case "pengajuan_anggaran":
				$data=$this->mmodul->getdata('pengajuan_anggaran_edit');
				$ttd=$this->mmodul->getdata('get_ttd');
				//echo "<pre>";print_r($ttd);exit;
				$this->smarty->assign('data',$data);
				$this->smarty->assign('ttd',$ttd);
				$htmlcontent = $this->smarty->fetch("cetak/konten.html");
			break;
			case "pengajuan":
			case "laba_rugi":
				$htmlcontent = $html;
			break;
			case "po":
				$kertas='L';
				$data=$this->mmodul->getdata('po_edit');
				$size=$this->db->get('cl_size')->result_array();
				$this->smarty->assign("size", $size);
				//$ttd=$this->mmodul->getdata('get_ttd_po');
				$this->smarty->assign('data',$data);
				//$this->smarty->assign('ttd',$ttd);
			//	$htmlheader="";
				$htmlcontent = $this->smarty->fetch("cetak/po.html");
				//echo $htmlcontent;exit;
			break;
			case "barang_masuk":
				$kertas='L';
				$data=$this->mmodul->getdata('masuk_edit');
				$size=$this->db->get('cl_size')->result_array();
				$this->smarty->assign("size", $size);
				//$ttd=$this->mmodul->getdata('get_ttd_po');
				$this->smarty->assign('data',$data);
				//$this->smarty->assign('ttd',$ttd);
				//$htmlheader="";
				$htmlcontent = $this->smarty->fetch("cetak/barang_masuk.html");
			break;
			case "barang_keluar":
				$kertas='L';
				$data=$this->mmodul->getdata('lap_barang_keluar');
				
				$this->smarty->assign('data',$data);
				
				$htmlcontent = $this->smarty->fetch("cetak/barang_keluar.html");
			break;
			case "penjualan":
				$kertas='L';
				$data=$this->mmodul->getdata('penjualan_edit');
				
				$this->smarty->assign('data',$data);
				//$this->smarty->assign('ttd',$ttd);
				//$htmlheader="";
				$htmlcontent = $this->smarty->fetch("cetak/penjualan.html");
			break;
			case "barang_masuk_sales":
				$kertas='L';
				$data=$this->mmodul->getdata('masuk_sales_edit');
				$size=$this->db->get('cl_size')->result_array();
				$this->smarty->assign("size", $size);
				//$ttd=$this->mmodul->getdata('get_ttd_po');
				$this->smarty->assign('data',$data);
				//$this->smarty->assign('ttd',$ttd);
				//$htmlheader="";
				$htmlcontent = $this->smarty->fetch("cetak/barang_masuk_sales.html");
			break;
		}
		
		//echo $htmlcontent;exit;
		$pdf = $this->mlpdf->load();
		$spdf = new mPDF('', 'A4', 0, '', 12.7, 12.7, 33, 20, 5, 16, $kertas);
		$spdf->curlAllowUnsafeSslRequests = true;
		$spdf->showImageErrors = true;
		$spdf->ignore_invalid_utf8 = true;
		// bukan sulap bukan sihir sim salabim jadi apa prok prok prok
		$spdf->allow_charset_conversion = true;     // which is already true by default
		$spdf->charset_in = 'iso-8859-1';  // set content encoding to iso
		$spdf->SetDisplayMode('fullpage');		
		$spdf->SetHTMLHeader($htmlheader);
		//$spdf->keep_table_proportions = true;
		$spdf->useSubstitutions=false;
		$spdf->simpleTables=true;
		
		$spdf->SetHTMLFooter('
			<div style="font-family:arial; font-size:8px; text-align:center; font-weight:bold;">
				<table width="100%" style="font-family:arial; font-size:8px;">
					<tr>
						<td width="30%" align="left">
							FM.OPS.01
						</td>
						<td width="40%" align="center">
							Revisi: 00
						</td>
						<td width="30%" align="right">
							Hal. {PAGENO} dari {nbpg}
						</td>
					</tr>
				</table>
			</div>
		');				
		$filename = date('Ymdhis');
		$spdf->SetProtection(array('print'));				
		$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
		//$spdf->Output('repositories/Dokumen_LS/LS_PDF/'.$filename.'.pdf', 'F'); // save to file because we can
		$spdf->Output($filename.'.pdf', 'I'); // view file	
	}
	function laporan($p1){
		switch($p1){
			case "laporan_stokopname":
				$tgl=$this->db->get('tbl_h_so')->result_array();
				$this->smarty->assign("tgl", $tgl);
			break;
			case "laporan_sales":
				$sales=$this->db->get_where('tbl_user',array('cl_user_group_id'=>4))->result_array();
				$this->smarty->assign("sales", $sales);
			break;
			case "laporan_barangmasuk":
				$supplier=$this->db->get('cl_supplier')->result_array();
				$this->smarty->assign("supplier", $supplier);
			break;
			case "laporan_barangkeluar":
				//$supplier=$this->db->get('cl_supplier')->result_array();
				//$this->smarty->assign("supplier", $supplier);
			break;
		}
		$temp='main/laporan.html';
		//$lokasi=$this->db->get('cl_lokasi')->result_array();
		if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){$temp="konstruksi.html";return $this->smarty->display($temp);}
		//$this->smarty->assign("lokasi", $lokasi);
		$this->smarty->assign("temp", $temp);
		$this->smarty->assign("mod", $p1);
		$this->smarty->display('home.html');
	}
	function konversi_romawi($angka){
		switch($angka){
			case 1:$rm='I';break;
			case 2:$rm='III';break;
			case 3:$rm='III';break;
			case 4:$rm='IV';break;
			case 5:$rm='V';break;
			case 6:$rm='VI';break;
			case 7:$rm='VII';break;
			case 8:$rm='VIII';break;
			case 9:$rm='IX';break;
			case 10:$rm='X';break;
			case 11:$rm='XI';break;
			case 12:$rm='XII';break;
		}
		return $rm;
	}
	function get_notif($usr){
		$html='';
		if($usr=='dir'){
			$dt=$this->mmodul->getdata('tbl_notif_dereksi','result_array');
			if(count($dt) > 0){
				$html .='<li class="header h_jml_notif">Anda Memiliki '.count($dt).' Notifikasi</li>';
				foreach($dt as $v){
					if($v['flag_notif']=='RU')$ico='fa-pencil-square';
					else $ico='fa-file-text-o';
					if($v['flag_mod']=='P'){//PENGAJUAN ANGGARAN
						$html .='<li>';
						$html .='<ul class="menu">';
						$html .='<li>';
						$html .="<a href='#' onclick='baca_notif(\"".$usr."\",".$v['id'].",\"P\")'>";
						$html .='<i class="fa '.$ico.' text-aqua"></i>No. : '.$v['no_pengajuan'].' Oleh : '.$v['nm_po'];
						$html .='</a>';
						$html .='</li>';
						$html .='</ul>';
						$html .='</li>';
					}else if($v['flag_mod']=='PO'){//PO
						$html .='<li>';
						$html .='<ul class="menu">';
						$html .='<li>';
						$html .="<a href='#' onclick='baca_notif(\"".$usr."\",".$v['id'].",\"PO\")'>";
						$html .='<i class="fa '.$ico.' text-aqua"></i>No. PO: '.$v['no_po'].' Oleh : '.$v['nm_po'];
						$html .='</a>';
						$html .='</li>';
						$html .='</ul>';
						$html .='</li>';
					}else if($v['flag_mod']=='PAO'){//PAO
						$html .='<li>';
						$html .='<ul class="menu">';
						$html .='<li>';
						$html .="<a href='#' onclick='baca_notif(\"".$usr."\",".$v['id'].",\"PAO\")'>";
						$html .='<i class="fa '.$ico.' text-aqua"></i>No.: '.$v['no_pengajuan_po'].' Oleh : '.$v['nm_pengajuan_po'];
						$html .='</a>';
						$html .='</li>';
						$html .='</ul>';
						$html .='</li>';
					}
				}
			}else{
				$html .='<li class="header h_jml_notif">Anda Tidak Memiliki Notifikasi</li>';
			}
		}else{
			$dt=$this->mmodul->getdata('tbl_notif','result_array');
			if(count($dt) > 0){
				$html .='<li class="header h_jml_notif">Anda Memiliki '.count($dt).' Notifikasi</li>';
				foreach($dt as $v){
					if($v['flag_notif']=='RU')$ico='fa-pencil-square';
					else $ico='fa-file-text-o';
					if($v['flag_mod']=='P'){//PENGAJUAN ANGGARAN
						$html .='<li>';
						$html .='<ul class="menu">';
						$html .='<li>';
						$html .="<a href='#' onclick='baca_notif(\"".$usr."\",".$v['id'].",\"P\")'>";
						$html .='<i class="fa '.$ico.' text-aqua"></i>No. : '.$v['no_pengajuan'].' RevisiBy : '.$v['nama_lengkap'];
						$html .='</a>';
						$html .='</li>';
						$html .='</ul>';
						$html .='</li>';
					}else if($v['flag_mod']=='PO'){//PO
						$html .='<li>';
						$html .='<ul class="menu">';
						$html .='<li>';
						$html .="<a href='#' onclick='baca_notif(\"".$usr."\",".$v['id'].",\"PO\")'>";
						$html .='<i class="fa '.$ico.' text-aqua"></i>No. PO: '.$v['no_po'].' PendingBy : '.$v['nm_po'];
						$html .='</a>';
						$html .='</li>';
						$html .='</ul>';
						$html .='</li>';

					}else if($v['flag_mod']=='PAO'){//PO
						$html .='<li>';
						$html .='<ul class="menu">';
						$html .='<li>';
						$html .="<a href='#' onclick='baca_notif(\"".$usr."\",".$v['id'].",\"PAO\")'>";
						$html .='<i class="fa '.$ico.' text-aqua"></i>No.: '.$v['no_pengajuan_po'].' PendingBy : '.$v['nama_lengkap'];
						$html .='</a>';
						$html .='</li>';
						$html .='</ul>';
						$html .='</li>';

					}
				}
			}else{
				$html .='<li class="header h_jml_notif">Anda Tidak Memiliki Notifikasi</li>';
			}
		}
		$data=array('html'=>$html,'jml'=>count($dt));
		echo json_encode($data);
	}
	function baca(){
		$usr=$this->input->post('usr');
		if($usr=='dir'){
			$sql="UPDATE tbl_notif_dereksi SET flag_read='F' WHERE id=".$this->input->post('id');
			echo $this->db->query($sql);
		}else{
			$sql="UPDATE tbl_notif SET flag_read='F' WHERE id=".$this->input->post('id');
			echo $this->db->query($sql);
		}
	}
	function cek_pwd_lama(){
		$pwd_lama=$this->input->post('pwd');
		$ex_pwd=$this->encrypt->decode($this->auth["password"]);
		if($pwd_lama==$ex_pwd){
			echo 1;exit;
		}else{
			echo 2;exit;
		}
	}
	function download_template($p1){
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		
		switch($p1){
			case "barang":
				$objPHPExcel = $objReader->load("__repo/template/barang_template.xlsx");
				$name="barang_template";
			break;
			case "price":
				if($this->input->post("cl_kode_supplier")){
					$sup=$this->db->get_where('cl_supplier',array('kode_supplier'=>$this->input->post("cl_kode_supplier")))->row_array();
					$name=date("YmdHis")."_template_".$sup["supplier"];
				}else{
					$name=date("YmdHis")."_template_allsupplier";
				}
				
				$dt=$this->mmodul->getdata('get_barang','result_array');
				//echo "<pre>";print_r($dt);
				$objPHPExcel = $objReader->load("__repo/template/price_template.xlsx");
				$write=$objPHPExcel->setActiveSheetIndex(0);
				$no=0;
				$tot=0;
				$mulai=5;
				foreach($dt as $x=>$v){
					$no++;
					$write->setCellValue('A'.$mulai, $no);
					$write->setCellValue('B'.$mulai, $v["sku"]);
					$mulai++;
				}
			break;
			case "so":
				if($this->input->post("cl_kode_supplier")){
					$sup=$this->db->get_where('cl_supplier',array('kode_supplier'=>$this->input->post("cl_kode_supplier")))->row_array();
					$name=date("YmdHis")."_template_".$sup["supplier"];
				}else{
					$name=date("YmdHis")."_template_allsupplier";
				}
				
				$dt=$this->mmodul->getdata('get_barang','result_array');
				//echo "<pre>";print_r($dt);
				$objPHPExcel = $objReader->load("__repo/template/so_template.xlsx");
				$write=$objPHPExcel->setActiveSheetIndex(0);
				$no=0;
				$tot=0;
				$mulai=5;
				foreach($dt as $x=>$v){
					$no++;
					$write->setCellValue('A'.$mulai, $no);
					$write->setCellValue('B'.$mulai, $v["sku"]);
					$mulai++;
				}
			break;
			case "stok":
				if($this->input->post("cl_kode_supplier")){
					$sup=$this->db->get_where('cl_supplier',array('kode_supplier'=>$this->input->post("cl_kode_supplier")))->row_array();
					$name=date("YmdHis")."_template_".$sup["supplier"];
				}else{
					$name=date("YmdHis")."_template_allsupplier";
				}
				
				$dt=$this->mmodul->getdata('get_barang','result_array');
				//echo "<pre>";print_r($dt);
				$objPHPExcel = $objReader->load("__repo/template/stok_template.xlsx");
				$write=$objPHPExcel->setActiveSheetIndex(0);
				$no=0;
				$tot=0;
				$mulai=5;
				foreach($dt as $x=>$v){
					$no++;
					$write->setCellValue('A'.$mulai, $no);
					$write->setCellValue('B'.$mulai, $v["sku"]);
					$mulai++;
				}
			break;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet '); //mime type
		header('Content-Disposition: attachment;filename="'.$name.'.xlsx"'); 
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');    	
	}
	function set_barcode($code="0312 CK")
	{
		// Load library
		$this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		// Generate barcode
		//$img=Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
		$file = Zend_Barcode::draw('code128', 'image', array('text' => $code), array());
		//$code = time().$code;
		$store_image = imagepng($file,"__repo/barcode/{$code}.png");
		//echo $code.'.png';
		return 1;
	}
	function cetak_barcode(){
		$this->load->library('mlpdf');		
		$sku=$this->input->post('sku');
		$size=$this->input->post('size');
		$generat=$this->set_barcode($sku);
		if($generat==1){
			$dt=$this->db->get_where('tbl_master_barang',array('sku'=>$sku))->row_array();
			$this->smarty->assign('dt',$dt);
			$this->smarty->assign('size',$size);
			$htmlcontent = $this->smarty->fetch("cetak/barcode.html");
			//echo $htmlcontent;exit;
			$pdf = $this->mlpdf->load();
			$spdf = new mPDF('', 'A4', 0, '', 12.7, 12.7, 33, 20, 5, 16, 'P');
			$spdf->ignore_invalid_utf8 = true;
			// bukan sulap bukan sihir sim salabim jadi apa prok prok prok
			$spdf->allow_charset_conversion = true;     // which is already true by default
			$spdf->charset_in = 'iso-8859-1';  // set content encoding to iso
			$spdf->SetDisplayMode('fullpage');		
			
			$spdf->useSubstitutions=false;
			$spdf->simpleTables=true;
			
			$filename = $sku;
			$spdf->SetProtection(array('print'));				
			$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
			//$spdf->Output('repositories/Dokumen_LS/LS_PDF/'.$filename.'.pdf', 'F'); // save to file because we can
			$spdf->Output($filename.'.pdf', 'I'); // view file	
		
		
		}
		
		
		
		echo $generat;
	}
	function upload_so(){
		$dt=$this->mmodul->upload_so();
		$size=$this->db->get('cl_size')->result_array();
		$this->smarty->assign("size", $size);
		$this->smarty->assign('dt',$dt);
		$this->smarty->display("main/so_excel.html");
		//echo $htmlcontent;
	}
	function upload_price(){
		$dt=$this->mmodul->upload_so();
		$size=$this->db->get('cl_size')->result_array();
		$this->smarty->assign("size", $size);
		$this->smarty->assign('dt',$dt);
		$this->smarty->display("main/price_excel.html");
		//echo $htmlcontent;
	}
	function upload_barang(){
		echo $this->mmodul->upload_barang();
	}
	function get_chart($p1){
		$data=array();
		switch($p1){
			case "sales":
				
				for($i=0;$i<=24;$i++){
					if($i<10)$data["y"][$i]="0".$i;
					else $data["y"][$i]=(string)$i;
				}
				$sales=$this->mmodul->getdata('sales_dahsboard','resul_array');
				$data["x"]=$sales;
			break;
			
		}
		echo json_encode($data);
	}
	function tes_date(){
		//date_default_timezone_set('Asia/Jakarta');
		echo date('Y-m-d H:i:s');
	}
	function cek_stok(){
		$sts=0;
		$dt=$this->db->get_where('tbl_stok',array('sku'=>$this->input->post('sku'),'cl_size_kode'=>$this->input->post('size')))->row_array();
		if(isset($dt["sku"])){
			if($dt["qty"]>0)$sts=1;
		}
		echo $sts;
	}
}
