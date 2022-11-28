<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library(array('egen','encrypt','encryption','lib'));
		$this->load->model('m_api');
	}
	public function index(){
		if('options' === $this->input->method()){
			$controllername = "Api";
			$aMethods = get_class_methods($controllername);
			$aUserMethods = array();
			if(is_array($aMethods)){
				foreach($aMethods as $method) {
					if($method != '__construct' && $method != 'get_instance' && $method != 'index' && $method != 'response' && $method != $controllername) {
						$aUserMethods[] = $method;
					}
				}
			}
			$this->response(200,$aUserMethods);
		}else{
			$response = array('code' => 501,'status' => 'Not Implemented !!!');
			$this->response(501,$response);
		}
	}
	public function simpan_reg(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'imei' => 'string',
					
				),
				'Extra Information' => 'GET INFROMASI DEVICE DAN USER',
				'Output' => 'json',
				'format' => json_decode('[{ "imei": "2017" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$resp = $this->m_api->simpan_reg($this->input->post('imei', TRUE),$this->input->post('cl_provinsi_id', TRUE),$this->input->post('cl_kab_kota_id', TRUE),$this->input->post('cl_kecamatan_id', TRUE),$this->input->post('ip', TRUE));
			//$resp=array('msg'=>1,'data'=>array('flag'=>'Lembur','id'=>1,'Nama'=>'Goyz'));//USER TIDAK TERDAFTAR
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	public function getAbsen(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'imei' => 'string',
					
				),
				'Extra Information' => 'GET INFROMASI DEVICE DAN USER',
				'Output' => 'json',
				'format' => json_decode('[{ "imei": "2017" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$resp = $this->m_api->getAbsen($this->input->post('user_id', TRUE));
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	public function getInfo(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'imei' => 'string',
					
				),
				'Extra Information' => 'GET INFROMASI DEVICE DAN USER',
				'Output' => 'json',
				'format' => json_decode('[{ "imei": "2017" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$resp = $this->m_api->getInfo($this->input->post('imei', TRUE));
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	public function getReg(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'imei' => 'string',
					
				),
				'Extra Information' => 'GET INFROMASI DEVICE DAN USER',
				'Output' => 'json',
				'format' => json_decode('[{ "imei": "2017" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$resp = $this->m_api->Reg($this->input->post('imei', TRUE),$this->input->post('karpeg', TRUE),$this->input->post('pwd', TRUE));
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	public function getRegUlang(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'imei' => 'string',
					
				),
				'Extra Information' => 'GET INFROMASI DEVICE DAN USER',
				'Output' => 'json',
				'format' => json_decode('[{ "imei": "2017" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$resp = $this->m_api->RegUlang($this->input->post('imei', TRUE),$this->input->post('karpeg', TRUE),$this->input->post('pwd', TRUE),$this->input->post('alesan', TRUE));
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	public function getLogin(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'nip' => 'string',
					'bulan' => 'integer',
					'tahun' => 'integer'
				),
				'Extra Information' => 'Ambil data pegawai per bulan dan tahun.',
				'Output' => 'json',
				'format' => json_decode('[{ "tahun": "2017", "bulan": "6", "nip_lama": "199006042014031002", "nip": "199006042014031002", "nrp": "", "nama": "XXXXX.", "jabat_jns_jabatan": "6", "ref_jabatan_kd": "205", "kode_unit": "1.12.2.1", "pns_jns_fungsi": "1", "kode_jabatan": "6", "ref_jabatan_akronim": "Operator Komputer DATA STATISTIK KRIMINAL DAN TEKNOLOGI INFORMASI", "deskripsi_kd_jabatan": "Operator Komputer", "create_date": null, "create_by": null, "flag_udt_unit": null, "unit_kerja": "SUB BIDANG PENGELOLAAN BASIS DATA" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$resp = $this->m_api->getLogin($this->input->post('user', TRUE),$this->input->post('pwd', TRUE));
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	function tes_api(){
		
		$ImageData = $this->input->post('image_path');
		$Imagename = $this->input->post('image_name');
		$file_name=  null;
		
		if($ImageData!="" || $ImageData!=null){
			
			$file_name=  $this->input->post('tbl_user_id')."_".date('YmdHis').".png";
			$ImagePath = "__repo/".$file_name;
			file_put_contents($ImagePath,base64_decode($ImageData));
		}
		$resp = $this->m_api->Absen($file_name);
		//if($resp==2)
		$code=$resp;
		$resp=array('code' => 200,'data' => array('sts'=>'ok'));
		
		$this->output
			->set_status_header($code)
			->set_output('OK');
		
		//echo $resp = $this->m_api->Absen($file_name);
		//echo "OKLAH";
	}
	public function crudData(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'nip' => 'string',
					'bulan' => 'integer',
					'tahun' => 'integer'
				),
				'Extra Information' => 'Ambil data pegawai per bulan dan tahun.',
				'Output' => 'json',
				'format' => json_decode('[{ "tahun": "2017", "bulan": "6"}]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$post = array();
			foreach($_POST as $k=>$v){
				if($this->input->post($k)!=""){
					$post[$k] = $this->input->post($k);
				}
				
			}
			$resp = $this->m_api->crudData($post);
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	public function getTes(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'imei' => 'string',
					
				),
				'Extra Information' => 'GET INFROMASI DEVICE DAN USER',
				'Output' => 'json',
				'format' => json_decode('[{ "imei": "2017" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			//$resp = $this->m_api->getInfo($this->input->post('imei', TRUE));
			$resp=array('msg'=>1,'data'=>array('flag'=>'Lembur','id'=>1,'Nama'=>'Goyz'));//USER TIDAK TERDAFTAR
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	public function simpan_token(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'imei' => 'string',
					
				),
				'Extra Information' => 'GET INFROMASI DEVICE DAN USER',
				'Output' => 'json',
				'format' => json_decode('[{ "imei": "2017" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$resp = $this->m_api->simpan_token($this->input->post('token', TRUE),$this->input->post('user_id', TRUE));
			//$resp=array('msg'=>1,'data'=>array('flag'=>'Lembur','id'=>1,'Nama'=>'Goyz'));//USER TIDAK TERDAFTAR
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	function alarm_masuk(){
		$notification = array();
		$notification['title'] = "Waktu Absen";
		$notification['flag'] = "alarm_masuk";
		//$notification['body'] = substr($isi,0,15)."....";
		$notification['body'] = "Sudah kah Anda Absen Masuk Hari ini?";
		$sts=0;
		$rs=$this->db->get('tbl_token_firebase')->result_array();
		foreach($rs as $x=>$v){
			$sts=$this->lib->send_firebase($notification,$v["firebase_token"]);
		}
		echo $sts;
		//send_firebase($notification,$token)
	}
	function alarm_pulang(){
		$notification = array();
		$notification['title'] = "Waktu Absen";
		$notification['flag'] = "alarm_pulang";
		//$notification['body'] = substr($isi,0,15)."....";
		$notification['body'] = "Sudah kah Anda Absen Pulang Hari ini?";
		$sts=0;
		$rs=$this->db->get('tbl_token_firebase')->result_array();
		foreach($rs as $x=>$v){
			$sts=$this->lib->send_firebase($notification,$v["firebase_token"]);
		}
		echo $sts;
		//send_firebase($notification,$token)
	}
	function send_firebase(){
		//define( 'API_ACCESS_KEY', 'AAAA4jhRZys:APA91bE6ZwW_bFGNy4JeAz2dgvEUKgpworlnUItbyAp-yXbKDa6I8uolDhlclaP0T6o_bKF2VZRAN1tLIdcmcV6trIzVy4DI0IynDIsKf2GRr_mSgQkd4X3TDz7WySpteram0eLsOJKJ' );
		define( 'API_ACCESS_KEY', 'AAAA1WmTjh4:APA91bG_06LNGTaOEaNgQ-1Xf-qQkG_vd6H5UtQ7NRUL5shGRw5y0n_6Cv66Q55VcE6OL00td20scpxsQ_Sv0VlRDed1EDu4K6nRwpgUENE1-CIGtBnfMPGzWW9S0Ha7MD9c_hm2RGau' );
			// Set POST variables
		$url = 'https://fcm.googleapis.com/fcm/send';
 
		$headers = array(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		$notification = array();
		$notification['title'] = "TESS";
		$notification['body'] = "COBA DARI PHP";
		/*$token=array();
		$token[]="eT1Tjd_So1M:APA91bFYwTpqwIRq1EPGI5j0GVY6bQylpd8Y1mEAYhwTKvShVG612YsWH0ULdCL2oUfNSatiT6eUtqZlKQa4gQJjLncd-wfSwuhfEra3ZPWW-g0dM6xENi1wbqaCEY5w5zijK1rPGo9p";
		$token[]="dMOX3LwaDLw:APA91bGT3SiPknWRC35-5QtdsWhdATZY0_bHaCa7q2YXoNSUUTovTW-6zb2qgvOzEN8Sp-XEkfKOqtWtz1CgTrJUNKfutysqSY0lvzKXWPNNYzzemXQacpoGqsLTq6naKHq0J75000Rz";
		*/
		$token="cgVuh3nKAWw:APA91bE2_vzX6dgxKYTxz0CWmI3SpzE_Un4gn1BdCERYcPpgpZA-Crj5knzGY733KWeCsFLyWUVnN5qZHUUO9gXTuU02A4xOWBOXUaq-WaLvrDDw_gatq68xU2H4AKf2OSRLkCNYhDho";
		//$token="dMOX3LwaDLw:APA91bGT3SiPknWRC35-5QtdsWhdATZY0_bHaCa7q2YXoNSUUTovTW-6zb2qgvOzEN8Sp-XEkfKOqtWtz1CgTrJUNKfutysqSY0lvzKXWPNNYzzemXQacpoGqsLTq6naKHq0J75000Rz";
		$fields = array(
			//'to' => "com.goyz.belajar",
			'registration_ids' => array($token),
			'data' => $notification,
		);
		//$notification['image'] = $this->image_url;
		//$notification['action'] = $this->action;
		//$notification['action_destination'] = $this->action_destination;
		// Open connection
		$ch = curl_init();
 
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
 
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
		// Disabling SSL Certificate support temporarily
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
		// Execute post
		$result = curl_exec($ch);
		if($result === FALSE){
			die('Curl failed: ' . curl_error($ch));
		}
 
		// Close connection
		curl_close($ch);
		
		echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
		echo json_encode($fields,JSON_PRETTY_PRINT);
		echo '</pre></p><h3>Response </h3><p><pre>';
		echo $result;
		echo '</pre></p>';
	}
	
	public function getFoto(){
		$code = 200;
		if('options' == $this->input->method()){
			$resp = array(
				'Method' => 'POST',
				'Parameter' => array(
					'imei' => 'string',
					
				),
				'Extra Information' => 'GET INFROMASI DEVICE DAN USER',
				'Output' => 'json',
				'format' => json_decode('[{ "imei": "2017" }]')
			);
		}else if('post' == $this->input->method()){
			//$resp = $this->m_api->getKontrak($this->input->post('nip', TRUE),$this->input->post('bulan', TRUE),$this->input->post('tahun', TRUE));
			$resp = $this->m_api->getFoto();
		}else {
			$code = 405;
			$resp = array('code' => $code,'status' => 'Method Not Allowed !!!');
		}
		
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
	
	function tes(){
		$price = 99.995511259525;
		$price1 = round((float)$price,2);
		$price2 = number_format((float)$price,3);
		$price3 = sprintf('%,5s',number_format((float)$price,3));
		echo $price1.' -> '.$price2.' -> '.$price3 ;
	}
	
}
