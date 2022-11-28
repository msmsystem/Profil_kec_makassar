<?php
class M_api extends CI_Model {
	function __construct(){
		parent::__construct();
		$this->resp='';
	}
	function simpan_reg($imei=null,$cl_provinsi_id=null,$cl_kab_kota_id=null,$cl_kecamatan_id=null,$ip=null){
		if(empty($imei) && empty($cl_provinsi_id) && empty($cl_kab_kota_id) && empty($cl_kecamatan_id) && empty($ip)){
			$this->resp=array('msg'=>2,'data'=>array());
			return $this->resp;
		}
		$dt=array('imei'=>$imei,
				  'cl_provinsi_id'=>$cl_provinsi_id,
				  'cl_kab_kota_id'=>$cl_kab_kota_id,
				  'cl_kecamatan_id'=>$cl_kecamatan_id,
				  'ip'=>$ip,
				  'flag'=>'F',
				  'create_date'=>date('Y-m-d H:i:s'),
				  'create_by'=>'Sys-'.$imei
		);
		/*$rs=$this->db->get_where('tbl_token_firebase',array('tbl_user_id'=>$user_id))->row_array();
		if(isset($rs["tbl_user_id"])){
			$this->db->update('tbl_token_firebase',$dt,array('tbl_user_id'=>$user_id));
		}else{
			$dt["tbl_user_id"]=$user_id;
			$this->db->insert('tbl_token_firebase',$dt);
		}
		*/
		$this->db->insert('tbl_seting_android',$dt);
		return $this->resp=array('msg'=>1,'data'=>$dt);//USER TIDAK TERDAFTAR
	}
	function simpan_token($token=null,$user_id=null){
		if(empty($token) && empty($user_id)){
			$this->resp=array('msg'=>'TIDAK ADA TOKEN','data'=>array());
			return $this->resp;
		}
		$dt=array('firebase_token'=>$token,
				  'create_date'=>date('Y-m-d H:i:s'),
				  'create_by'=>'Goyz'
		);
		$rs=$this->db->get_where('tbl_token_firebase',array('tbl_user_id'=>$user_id))->row_array();
		if(isset($rs["tbl_user_id"])){
			$this->db->update('tbl_token_firebase',$dt,array('tbl_user_id'=>$user_id));
		}else{
			$dt["tbl_user_id"]=$user_id;
			$this->db->insert('tbl_token_firebase',$dt);
		}
		return $this->resp=array('msg'=>1,'data'=>$dt);//USER TIDAK TERDAFTAR
	}
	function getAbsen($user_id=null){
		if(empty($user_id)){
			$this->resp=array('msg'=>'TIDAK DAPAT MASUK HALAMAN','data'=>array());
			return $this->resp;
		}
		$where = " WHERE 1=1 ";
		if($this->input->post("param")==1){
			$where .=" AND A.tgl BETWEEN '".$this->input->post("tgl_mulai")."' AND '".$this->input->post("tgl_akhir")."' ";
		}
		$sql="SELECT B.tbl_user_id,B.nama_lengkap,B.tgl,B.jam_masuk,C.jam_keluar 
				FROM (
					SELECT A.tbl_user_id,B.nama_lengkap,A.tgl,MIN(A.jam)as jam_masuk  
					FROM tbl_log_absensi A
					LEFT JOIN tbl_user B ON A.tbl_user_id=B.id
					".$where." AND A.flag='M' AND tbl_user_id=".$user_id."
					GROUP BY A.tbl_user_id,B.nama_lengkap,A.tgl
				)B 
				LEFT JOIN(
					SELECT A.tbl_user_id,A.tgl,MAX(A.jam)as jam_keluar  
					FROM tbl_log_absensi A
					".$where." AND A.flag='P' AND A.tbl_user_id=".$user_id."
					GROUP BY A.tbl_user_id,A.tgl
				)C ON (B.tbl_user_id=C.tbl_user_id AND B.tgl=C.tgl)";
		$dt=$this->db->query($sql)->result_array();
		return $this->resp=array('msg'=>1,'data'=>$dt);//USER TIDAK TERDAFTAR
	}
	function RegUlang($imei=null,$nik=null,$pwd=null,$alesan=null){
		if(empty($imei) || empty($nik) || empty($pwd) || empty($alesan)){
			$this->resp=array('msg'=>'TIDAK DAPAT MASUK HALAMAN','data'=>array());
			return $this->resp;
		}
		$this->db->trans_begin();
		$sql="SELECT * FROM tbl_reg WHERE karpeg='".$nik."' AND flag='F' ";
		$rs=$this->db->query($sql)->row_array();
		if(isset($rs["karpeg"])){
			$sql="UPDATE tbl_reg SET flag='C' WHERE karpeg='".$nik."' AND imei='".$rs['imei']."'";
			$this->db->query($sql);
			$pwd=$this->encrypt->encode($pwd);
			$dt=array('imei'=>$imei,'karpeg'=>$nik,'pwd'=>$pwd,'alesan'=>$alesan);
			$this->db->insert('tbl_reg',$dt);
		}
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			//echo 2;exit;
			$this->resp=array('msg'=>2,'data'=>array());//USER TIDAK TERDAFTAR
		}else{
			$this->db->trans_commit();	
			//echo 1;exit;
			return $this->resp=array('msg'=>1,'data'=>array());//USER TIDAK TERDAFTAR
		}
		
		//echo json_encode(array('msg'=>"OK"));exit;
	}
	function Reg($imei=null,$nik=null,$pwd=null){
		if(empty($imei) || empty($nik) || empty($pwd)){
			$this->resp=array('msg'=>'TIDAK DAPAT MASUK HALAMAN','data'=>array());
			return $this->resp;
		}
		$sql="SELECT * FROM tbl_user WHERE karpeg='".$nik."'";
		$rs=$this->db->query($sql)->row_array();
		if(isset($rs["karpeg"])){
			$sql="SELECT * FROM tbl_reg where karpeg='".$nik."' AND flag='F'";
			$res=$this->db->query($sql)->row_array();
			if(isset($res['karpeg'])){
				$this->resp=array('msg'=>3,'data'=>array());//USER TIDAK TERDAFTAR
				return $this->resp;
			}else{
				$pwd=$this->encrypt->encode($pwd);
				$dt=array('imei'=>$imei,'karpeg'=>$nik,'pwd'=>$pwd,'alesan'=>'User Baru');
				$this->db->insert('tbl_reg',$dt);
				$this->resp=array('msg'=>4,'data'=>array());//USER BARU
				return $this->resp;
			}
			
		}else{
			$this->resp=array('msg'=>2,'data'=>array());//USER TIDAK TERDAFTAR
			return $this->resp;
		}
	}
	function getInfo($imei=null){
		if(empty($imei)){
			$this->resp=array('msg'=>'TIDAK DAPAT MASUK HALAMAN','data'=>array());
			return $this->resp;
		}
		$sql="SELECT * FROM tbl_seting_android WHERE imei='".$imei."'";
		//echo $sql;exit;
		
		/*
		$sql="SELECT A.*,B.nama_lengkap,B.nama_user,B.alamat,B.email,B.tlp,B.ttd,
				C.deskripsi,D.jabatan,E.departemen,B.id as tbl_user_id ,B.foto,
				B.cl_user_group_id,B.cl_departemen,B.cl_jabatan_id,B.cl_unit_id,B.cl_sub_unit_id,B.cl_departemen,B.sts_peg
				FROM tbl_reg A 
				LEFT JOIN tbl_user B ON (A.nik=B.nik OR A.karpeg=B.karpeg)
				LEFT JOIN cl_user_group C ON B.cl_user_group_id=C.id
				LEFT JOIN cl_jabatan D ON B.cl_jabatan_id=D.id
				LEFT JOIN cl_departemen E ON B.cl_departemen=E.id
				WHERE A.imei='".$imei."' AND (A.flag='F' OR A.flag <> 'C')";
		*/
		$data=$this->db->query($sql)->row_array();
		
		if(isset($data["imei"])){
			$this->resp=array('msg'=>1,'data'=>$data);
			return $this->resp;
		}else{
			$this->resp=array('msg'=>2,'data'=>array());//BELUM DAFTAR
			return $this->resp;
		}
	}
	function getLogin($user=null,$pwd=null){
		if(empty($user) || empty($pwd)){
			$this->resp=array('msg'=>'TIDAK DAPAT MASUK HALAMAN','data'=>array());
			return $this->resp;
		}
		$sql="SELECT A.*,B.judul_kontrak 
				FROM pcm_user_rekanan A
				LEFT JOIN pcm_kontrak B ON A.pcm_kontrak_id=B.id
				WHERE A.nama_user='".$user."'";
		$res=$this->db->query($sql)->row_array();
		if(isset($res["nama_user"])){
			if($res["status"]==1){
				//if($this->encrypt->decode($res['password'])==$pwd){
				if($res['password']==md5($pwd)){
					$res["flag_user"]='penyedia';
					$this->resp=array('msg'=>1,'data'=>$res);
					return $this->resp;
				}else{
					$this->resp=array('msg'=>3,'data'=>array());//PASSWORD SALAH
					return $this->resp;
				}
			}else{
				$this->resp=array('msg'=>4,'data'=>array());//USER SUDAH TIDAK AKTIF
				return $this->resp;
			}
		}else{
			$sql="SELECT A.pcm_user_id,B.`password`,B.email,C.description,B.nama_lengkap,B.status 
				FROM pcm_user_inspeksi A
				LEFT JOIN pcm_user B ON A.pcm_user_id=B.nama_user
				LEFT JOIN pcm_cost_center C ON B.pcm_cost_center_id=C.id
				WHERE A.pcm_user_id='".$user."'
				GROUP BY pcm_user_id";
				$res=$this->db->query($sql)->row_array();
				
				if(isset($res["pcm_user_id"])){
					if($res["status"]==1){
						$this->resp=array('msg'=>1,'data'=>$res);
						
						/*echo md5('12345');
						exit;
						echo $this->encryption->decrypt($res['password']);
						exit;
						*/
						//if($this->encryption->decrypt($res['password'])==$pwd){
						if($res['password']==md5($pwd)){
							$res["flag_user"]='management';
							$this->resp=array('msg'=>1,'data'=>$res);
							return $this->resp;
						}else{
							$this->resp=array('msg'=>3,'data'=>array());//PASSWORD SALAH
							return $this->resp;
						}
					}else{
						$this->resp=array('msg'=>4,'data'=>array());//USER SUDAH TIDAK AKTIF
						return $this->resp;
					}
				}else{
					$this->resp=array('msg'=>2,'data'=>array());// USER EWEUH
					return $this->resp;
				}
			//$this->resp=array('msg'=>2,'data'=>array());// USER EWEUH
			//return $this->resp;
		}
		
	}
	
	function crudData($data){
		$this->db->trans_begin();
		$post = array();
		$id = $this->input->post('id');
		$field_id = array("id"=>$id);
		$sts_crud = $this->input->post('sts_crud');
		$table = $this->input->post('table');
		unset($data['sts_crud']);
		unset($data['table']);
		$resp=array('msg'=>0,'t_msg'=>'Gagal');
		switch($table){
			case "pcm_realisasi_bobot":
				$id_kontrak=$data["id_kontrak"];
				unset($data["id_kontrak"]);
				$sql="SELECT * FROM pcm_realisasi_bobot WHERE minggu=".$data["minggu"]." AND pcm_rencana_kerja_id=".$data["pcm_rencana_kerja_id"];
				$ex=$this->db->query($sql)->row_array();
				if(isset($ex["minggu"])){
					$sts_crud="edit";
					$field_id=array("minggu"=>$data["minggu"],"pcm_rencana_kerja_id"=>$data["pcm_rencana_kerja_id"]);
					unset($data["minggu"]);
					unset($data["pcm_rencana_kerja_id"]);
				}
				
			break;
			case "tbl_cuti":
				if($sts_crud!='delete'){
					$pengaju_id=$this->input->post('tbl_user_id');
					$dept_appr=$this->input->post('tbl_tahapan_sop_id');
					$info_usr=$this->db->get_where('tbl_user',array('id'=>$pengaju_id))->row_array();
					$notification = array();
					$notification['title'] = "APPROVE CUTI";
					$notification['flag'] = "appr_cuti";
					//$notification['body'] = substr($isi,0,15)."....";
					$notification['body'] = "Pengajuan Cuti Oleh : ".$info_usr["nama_lengkap"];
					
					$info_appr=$this->db->get_where('tbl_user',array('cl_departemen'=>$dept_appr,'cl_jabatan_id'=>1))->result_array();
					foreach($info_appr as $a=>$b){						
						$rs=$this->db->get_where('tbl_token_firebase',array('tbl_user_id'=>$b["id"]))->row_array();
						if(isset($rs["firebase_token"])){
							$this->lib->send_firebase($notification,$rs["firebase_token"]);
						}
					}
					
				}
			break;
		}
		
		
		if($sts_crud == 'add'){
			$this->db->insert($table, $data);
			
		}elseif($sts_crud == 'edit'){
			$this->db->update($table, $data, $field_id );
			
		}elseif($sts_crud == 'delete'){
			$this->db->delete($table, array($field_id=>$id) );
		}
		
		
		
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			return $resp;
		}else{
			$this->db->trans_commit();	
			$resp=array('msg'=>"OK");
			//return $resp;
			echo json_encode(array('msg'=>"OK"));exit;
		}
	}
	function Absen($foto){
		$this->db->trans_begin();
		$kantor=$this->input->post('kantor');
		$pch=explode('_',$kantor);
		$shift_1="08:00:00";
		$shift_2="17:00:00";
		//$shift_2="17:00:00";
		$kal=strtotime($shift_1)."->".strtotime($shift_2)."->".strtotime($this->input->post('jam'));
		$shift=1;
		$tgl=date('Y-m-d');
		//return $kal;
		if($this->input->post('flag')=='M' || $this->input->post('flag')=='P'){
			
			$qr=$this->db->get_where('cl_kantor',array('qr_code'=>$kantor))->row_array();
			if(!isset($qr["id"])){
				$msg=205;
				return $msg;
			}
			if($this->input->post('shift')==2){
				if($this->input->post('flag')=='P'){
					$date = new DateTime(date('Y-m-d'));
					$date->modify('-1 day');
					$tgl_lalu=$date->format('Y-m-d');
					$get_m=$this->db->get_where('tbl_log_absensi',array('tbl_user_id'=>$this->input->post('tbl_user_id'),'tgl'=>$tgl,'shift'=>2,'flag'=>'M'))->row_array();
						if(isset($get_m["tbl_user_id"])){
							//$tgl=$tgl_lalu;
							$shift=2;
						}else{
							$get_m=$this->db->get_where('tbl_log_absensi',array('tbl_user_id'=>$this->input->post('tbl_user_id'),'tgl'=>$tgl_lalu,'shift'=>2,'flag'=>'M'))->row_array();
							if(isset($get_m["tbl_user_id"])){
								$tgl=$tgl_lalu;
								$shift=2;
							}
						}
						
				}
			}
			/*
			if(strtotime($this->input->post('jam')) > strtotime($shift_2)){
				$shift=2;
			}
			*/
		}
		/*
		if($this->input->post('flag')=='P'){
			$date = new DateTime(date('Y-m-d'));
			$date->modify('-1 day');
			$tgl_lalu=$date->format('Y-m-d');
			$get_m=$this->db->get_where('tbl_log_absensi',array('tbl_user_id'=>$this->input->post('tbl_user_id'),'tgl'=>$tgl,'shift'=>2,'flag'=>'M'))->row_array();
				if(isset($get_m["tbl_user_id"])){
					//$tgl=$tgl_lalu;
					$shift=2;
				}else{
					$get_m=$this->db->get_where('tbl_log_absensi',array('tbl_user_id'=>$this->input->post('tbl_user_id'),'tgl'=>$tgl_lalu,'shift'=>2,'flag'=>'M'))->row_array();
					if(isset($get_m["tbl_user_id"])){
						$tgl=$tgl_lalu;
						$shift=2;
					}
				}
				
		}*/
		$dt=array('tbl_user_id'=>$this->input->post('tbl_user_id'),
				  'cl_kantor_id'=>$qr["id"],
				  'tgl'=>$tgl,
				  'jam'=>date('H:i:s'),//$this->input->post('jam'),
				  'lat'=>$this->input->post('lat'),
				  'longi'=>$this->input->post('longi'),
				  'foto'=>$foto,
				  'shift'=>$this->input->post('shift'),
				  'flag'=>$this->input->post('flag'),
		);
		$this->db->insert('tbl_log_absensi',$dt);
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			$msg=201;
			return $msg;
		}else{
			$this->db->trans_commit();	
			$msg=200;
			return $msg;
		}
	}
	function getFoto(){
		
		$sql="SELECT * FROM tbl_foto_android where flag='F' ";
		$rs=$this->db->query($sql)->result_array();
		if(count($rs)>0){
			$foto=array();
			$foto["foto"]=array();
			foreach($rs as $x=>$v){
				//$foto[]="http://netglosindo.co.id/esdm/__assets/foto_android/".$v["foto"];
				$foto["foto"][]["foto"]="http://192.168.0.120:8080/esdm2/__assets/foto_android/".$v["foto"];
			}
			$this->resp=array('msg'=>1,'data'=>$foto);//USER TIDAK TERDAFTAR
			return $this->resp;
			
		}else{
			$this->resp=array('msg'=>2,'data'=>array());//USER TIDAK TERDAFTAR
			return $this->resp;
		}
	}
}
?>