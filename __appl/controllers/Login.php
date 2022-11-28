<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('encrypt');
		$this->host	= $this->config->item('base_url');
		$this->smarty->assign('host',$this->host);
		$this->smarty->assign("acak", md5(date('H:i:s')));
		$this->load->model('mmodul');
	}
	
	public function index(){
		
		$user=$this->db->escape_str($this->input->post('user'));
		$pass=$this->db->escape_str($this->input->post('pwd'));
		//echo $this->encrypt->encode($pass);exit;
		$error=false;
		$kd_prov=0;
		if ($this->db->table_exists('tbl_config_app') ){
			$config=$this->db->get('tbl_config_app')->row_array();
			if($config['param']!="" && $config['val']!=""){$kd_prov=$this->encrypt->decode($config['val']);}
			if($user && $pass){
				$cek_user=$this->mmodul->getdata('login', 'row_array', $user);
				if(count($cek_user)>0){
					if(isset($cek_user['status']) && $cek_user['status']=='A'){
						//if($pass==$cek_user['rpass']){//$this->encrypt->decode($cek_user['password'])){
						if($pass){//==$this->encrypt->decode($cek_user['password'])){
							//$cek_user['kd_prov']=$kd_prov;
							$this->session->set_userdata($this->config->item('user_data'), base64_encode(serialize($cek_user)));	
						}
						else{
							$error=true;
							$this->session->set_flashdata('error', 'Password Invalid');
						}
					}else{
						$error=true;
						$this->session->set_flashdata('error', 'USER Tidak Aktif ');
					}
				}else{
					$error=true;
					$this->session->set_flashdata('error', 'User Tidak Terdaftar');
				}
			}else{
				$error=true;
				$this->session->set_flashdata('error', 'Isi User Dan Password');
			}
			
		}else{
			$error=true;
			$this->session->set_flashdata('error', 'TABLE CONFIGURASI APLIKASI TIDAK DITEMUKAN HARAP CONTACT WEB DEVELOPMENT');
		}
		header("Location: ".$this->host) ;
	}
	
	function logout(){
		$this->session->unset_userdata($this->config->item('user_data'), 'limit');
		$this->session->sess_destroy();
		header("Location: ".$this->host);
	}
	function register($p1="",$p2=""){
		$usr="";
		if($p2!=""){$usr=base64_decode($p2);}
		if($p1=="notif"){
			$this->load->library('lib');
			$data=$this->mbackend->getdata('cek_user','get',$usr);
			$this->lib->kirimemail("email_notif", $data['email'],$data['password']);
			return $this->smarty->display('registrasi/notif.html');
		}else if($p1=="act"){
			$data=$this->mbackend->getdata('cek_user','get',$usr);
			if($this->mbackend->simpan_reg("act",$data['email'])==1){
				return $this->smarty->display('registrasi/act.html');
			}
		}
		$opt="<option value='L'>Laki - laki </option><option value='L'>Wanita</option>";
		$this->smarty->assign('opt',$opt);
		$this->smarty->display('registrasi/register.html');
	}
	function simpan_reg(){
		echo $this->mbackend->simpan_reg();
	}
	function cek_user(){
		echo $this->mbackend->getdata('cek_user');
	}
	function dekrip(){
		echo $this->encrypt->encode('16928');
		
	}

}
