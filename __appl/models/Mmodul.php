<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Mmodul extends CI_Model{
	function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	
	function getdata($type="", $balikan="", $p1="", $p2=""){
		$where = " WHERE 1=1 ";
		$tgl_akhir=$this->input->post('tgl_akhir');
		$tgl_mulai=$this->input->post('tgl_mulai');
		switch($type){
			case "profil":
				if($this->input->post('key')){
					$where .=" AND (A.nama_kecamatan like '%".$this->input->post('key')."%' OR 
							   A.alamat like '%".$this->input->post('key')."%'
							   )";
				}
				if($p1=='edit'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,B.nama_server
						FROM tbl_profil A
						LEFT JOIN tbl_seting_android_server B ON A.tbl_seting_server_id=B.id
						".$where;
				if($p1=='edit'){
					return $this->db->query($sql)->row_array();
				}
			break;
			case "tbl_seting_android_server":
				if($this->input->post('key')){
					$where .=" AND (A.imei like '%".$this->input->post('key')."%' OR 
							   B.nama like '%".$this->input->post('key')."%' OR 
							   C.nama like '%".$this->input->post('key')."%' OR 
							   D.nama like '%".$this->input->post('key')."%'
							   )";
				}
				if($p1=='edit'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,B.nama as prov,C.nama as kab_kota,D.nama as kec 
						FROM tbl_seting_android_server A
						LEFT JOIN cl_provinsi B ON A.cl_provinsi_id=B.id
						LEFT JOIN cl_kab_kota C ON A.cl_kab_kota_id=C.id
						LEFT JOIN cl_kecamatan D On A.cl_kecamatan_id=D.id ".$where;
				if($p1=='edit'){
					return $this->db->query($sql)->row_array();
				}
			break;
			case "prov":
				if($this->input->post('key')){
					$where .=" AND (A.nama like '%".$this->input->post('key')."%')";
				}
				if($p1=='edit'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,A.nama as txt FROM cl_provinsi A ".$where;
				if($p1=='edit'){
					return $this->db->query($sql)->row_array();
				}
			break;
			case "kab_kota":
				if($this->input->post('key')){
					$where .=" AND (A.nama like '%".$this->input->post('key')."%')";
				}
				if($p1=='get'){
					$where .=" AND A.provinsi_id=".$p2;
				}
				if($p1=='edit'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,A.nama as txt,B.nama as prov 
					  FROM cl_kab_kota A 
					  LEFT JOIN cl_provinsi B ON A.provinsi_id=B.id ".$where;
				
				if($p1=='edit'){
					return $this->db->query($sql)->row_array();
				}
			break;
			case "kec":
				if($this->input->post('key')){
					$where .=" AND (A.nama like '%".$this->input->post('key')."%')";
				}
				if($p1=='get'){
					$where .=" AND A.kab_kota_id=".$p2;
				}
				if($p1=='edit'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,A.nama as txt,B.nama as kab_kota,C.nama as prov,C.id as provinsi_id 
					  FROM cl_kecamatan A 
					  LEFT JOIN cl_kab_kota B ON A.kab_kota_id=B.id 
					  LEFT JOIN cl_provinsi C ON B.provinsi_id = C.id ".$where;
				if($p1=='edit'){
					return $this->db->query($sql)->row_array();
				}
			break;
			case "ip":
				if($this->input->post('key')){
					$where .=" AND (A.nama like '%".$this->input->post('key')."%')";
				}
				if($p1=='get'){
					$where .=" AND A.cl_kecamatan_id=".$p2;
				}
				$sql="SELECT A.host as txt,A.host as id FROM tbl_seting_android_server A ".$where;
			break;
			case "ip2":
				if($this->input->post('key')){
					$where .=" AND (A.nama like '%".$this->input->post('key')."%')";
				}
				if($p1=='get'){
					$where .=" AND A.cl_kecamatan_id=".$p2;
				}
				$sql="SELECT A.*,A.nama_server as txt FROM tbl_seting_android_server A ".$where;
			break;
			case "desa":
				if($this->input->post('key')){
					$where .=" AND (A.nama like '%".$this->input->post('key')."%')";
				}
				if($p1=='edit'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,A.nama as txt,B.nama as kab_kota,C.nama as prov ,E.nama as kec,C.id as provinsi_id,B.id as kab_kota_id 
					  FROM cl_kelurahan_desa A 
					  LEFT JOIN cl_kecamatan E ON A.kecamatan_id=E.id 
					  LEFT JOIN cl_kab_kota B ON E.kab_kota_id=B.id 
					  LEFT JOIN cl_provinsi C ON B.provinsi_id = C.id ".$where;
				if($p1=='edit'){
					return $this->db->query($sql)->row_array();
				}
			break;
			case "tbl_seting_android":
				if($this->input->post('key')){
					$where .=" AND (A.imei like '%".$this->input->post('key')."%' OR 
							   B.nama like '%".$this->input->post('key')."%' OR 
							   C.nama like '%".$this->input->post('key')."%' OR 
							   D.nama like '%".$this->input->post('key')."%'
							   )";
				}
				if($p1=='edit'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,B.nama as prov,C.nama as kab_kota,D.nama as kec 
						FROM tbl_seting_android A
						LEFT JOIN cl_provinsi B ON A.cl_provinsi_id=B.id
						LEFT JOIN cl_kab_kota C ON A.cl_kab_kota_id=C.id
						LEFT JOIN cl_kecamatan D On A.cl_kecamatan_id=D.id ".$where;
				if($p1=='edit'){
					return $this->db->query($sql)->row_array();
				}
			break;
			case "user":
				if($this->input->post('key')){
					$where .=" AND A.nama_lengkap like '%".$this->input->post('key')."%'";
				}
				$sql="SELECT A.*,B.user_group
					FROM tbl_user A
					LEFT JOIN cl_user_group B ON A.cl_user_group_id=B.id ".$where;
			break;
			case "jabatan":
				if($this->input->post('key')){
					$where .=" AND (A.jabatan like '%".$this->input->post('key')."%')";
				}
				$sql="SELECT A.*,A.jabatan as txt FROM cl_jabatan A ".$where;
			break;
			case "user_group":
				if($this->input->post('key')){
					$where .=" AND (A.user_group like '%".$this->input->post('key')."%')";
				}
				$sql="SELECT A.*,A.user_group as txt FROM cl_user_group A ".$where;
			break;
			case "departemen":
				if($this->input->post('key')){
					$where .=" AND (A.departemen like '%".$this->input->post('key')."%' OR A.deskripsi like '%".$this->input->post('key')."%')";
				}
				$sql="SELECT A.*,A.departemen as txt FROM cl_departemen A ".$where;
			break;
			case "pengajuan_anggaran":
				//$where .=" AND (A.flag='P' OR A.flag='R') ";
				if($this->input->post('key')){
					$where .=" AND (E.kode_project like '%".$this->input->post('key')."%' 
							  OR E.nama_project like '%".$this->input->post('key')."%' OR 
							  A.no_pengajuan like '%".$this->input->post('key')."%' OR
							  D.departemen like '%".$this->input->post('key')."%')";
				}
				if($this->auth["cl_jabatan_id"]==1 OR $this->auth["cl_jabatan_id"]==2){
					$where .=" AND ( D.id='".$this->auth["cl_departemen"]."' ";
					$where .=" OR A.nama_user='".$this->auth["nama_user"]."' )";
				}
				if($this->auth["cl_jabatan_id"]>=3){
					$where .=" AND A.nama_user='".$this->auth["nama_user"]."'";
				}
				$sql="SELECT A.*,E.nama_project,D.departemen as posisi,B.nama_lengkap,B.cl_departemen
					FROM tbl_h_pengajuan A
					LEFT JOIN tbl_user B ON A.nama_user=B.nama_user
					LEFT JOIN tbl_tahapan_sop C ON A.tbl_tahapan_sop_id=C.id
					LEFT JOIN cl_departemen D ON C.cl_departemen_id=D.id
					LEFT JOIN cl_project E ON A.kode_project=E.kode_project ".$where." AND (A.flag='P' OR A.flag='R')";
					
				$rs=$this->db->query($sql)->result_array();
				//echo $sql;
				foreach($rs as $x=>$v){
					$sql="SELECT * FROM tbl_status_pengajuan 
						  WHERE tbl_h_pengajuan_id=".$v["id"]." 
						  AND tbl_tahapan_sop_id=".$v["tbl_tahapan_sop_id"];
					$ex=$this->db->query($sql)->row_array();
					if(isset($ex["status"]))$rs[$x]["status_appr"]=$ex["status"];
					else $rs[$x]["status_appr"]='P';
				}
				return json_encode($rs);
			break;
			case "histori_pengajuan":
				if($this->input->post('key')){
					$where .=" AND (E.kode_project like '%".$this->input->post('key')."%' 
							  OR E.nama_project like '%".$this->input->post('key')."%' OR 
							  D.departemen like '%".$this->input->post('key')."%')";
				}
				/*if($this->auth["cl_jabatan_id"]==1 OR $this->auth["cl_jabatan_id"]==2){
					$where .=" AND D.id='".$this->auth["cl_departemen"]."' ";
					$where .=" OR A.nama_user='".$this->auth["nama_user"]."'";
				}*/
				if($this->auth["cl_jabatan_id"]>=3){
					$where .=" AND A.nama_user='".$this->auth["nama_user"]."'";
				}
				$sql="SELECT A.*,E.nama_project,D.departemen as posisi,B.nama_lengkap,B.cl_departemen
					FROM tbl_h_pengajuan A
					LEFT JOIN tbl_user B ON A.nama_user=B.nama_user
					LEFT JOIN tbl_tahapan_sop C ON A.tbl_tahapan_sop_id=C.id
					LEFT JOIN cl_departemen D ON C.cl_departemen_id=D.id
					LEFT JOIN cl_project E ON A.kode_project=E.kode_project ".$where." AND A.flag='F'";
					
				$rs=$this->db->query($sql)->result_array();
				//echo $sql;
				foreach($rs as $x=>$v){
					$sql="SELECT * FROM tbl_status_pengajuan 
						  WHERE tbl_h_pengajuan_id=".$v["id"]." 
						  AND tbl_tahapan_sop_id=".$v["tbl_tahapan_sop_id"];
					$ex=$this->db->query($sql)->row_array();
					if(isset($ex["status"]))$rs[$x]["status_appr"]=$ex["status"];
					else $rs[$x]["status_appr"]='P';
				}
				return json_encode($rs);
			break;
			case "login":
				$sql = " 
					SELECT A.*
					FROM tbl_user A WHERE A.email='".$p1."' OR nama_user='".$p1."'
				";
			break;
			case "project":
				if($this->input->post('key')){
					$where .=" AND (kode_project like '%".$this->input->post('key')."%' OR nama_project like '%".$this->input->post('key')."%')";
				}
				$sql="SELECT * FROM cl_project ".$where." AND status='A' ";
			break;
			case "vendor":
				if($this->input->post('key')){
					$where .=" AND (nama_vendor like '%".$this->input->post('key')."%' OR alamat like '%".$this->input->post('key')."%' OR pic like '%".$this->input->post('key')."%')";
				}
				$sql="SELECT * FROM cl_vendor ".$where;
			break;
			
		}
		if($balikan == 'row_array'){
			return $this->result_query($sql,'row_array');
		}elseif($balikan == 'result_array'){
			return $this->result_query($sql);
		}else{
			return $this->result_query($sql,'json');
		}
	}
	function result_query($sql,$type="",$p1=""){
		switch($type){
			case "json":
				$page = (integer) (($this->input->post('page')) ? $this->input->post('page') : "1");
				$limit = (integer) (($this->input->post('rows')) ? $this->input->post('rows') : "10");
				$count = $this->db->query($sql)->num_rows();
				if( $count >0 ) { $total_pages = ceil($count/$limit); } else { $total_pages = 0; } 
				if ($page > $total_pages) $page=$total_pages; 
				$start = $limit*$page - $limit; // do not put $limit*($page - 1)
				if($start<0) $start=0;
				  
				$sql = $sql . " LIMIT $start,$limit";
			
				$data=$this->db->query($sql)->result_array();  
				
				if($p1=='stok'){
					foreach($data as $v=>$x){
						$sql="SELECT * FROM tbl_stok WHERE sku='".$x["sku"]."'";
						$res=$this->db->query($sql)->result_array();
						if(count($res)>0){
							foreach($res as $a=>$b){
								$data[$v]["ukuran_".$b["cl_size_kode"]]=$b["qty"];
							}
						}
					}
				}
				
				
						
				if($data){
				   $responce = new stdClass();
				   $responce->rows= $data;
				   $responce->total =$count;
				   return json_encode($responce);
				}else{ 
				   $responce = new stdClass();
				   $responce->rows = 0;
				   $responce->total = 0;
				   return json_encode($responce);
				} 
			break;
			case "row_obj":return $this->db->query($sql)->row();break;
			case "row_array":return $this->db->query($sql)->row_array();break;
			default:return $this->db->query($sql)->result_array();break;
		}
	}
	function upload_so(){
		if(isset($_FILES['file']) && $_FILES['file']['name']!=""){
			$file = $_FILES['file']['tmp_name'];
			$load = PHPExcel_IOFactory::load($file);
			$sheets = $load->getActiveSheet()->toArray(null,true,true,true);
			$i = 1;
			$dt=array();
			$col=array('C','D','E','F','G','H','I','J','K','L','M','N');
			$idx=0;
			$size=$this->db->get('cl_size')->result_array();
			  foreach ($sheets as $sheet) {
				if ($i > 4) {
					  $dt[$idx]=array();
					  $dt[$idx]=array('sku'=>$sheet['B']);
					  $dt[$idx]["size"]=array();
					  foreach($size as $x=>$v){
						  if($sheet[$col[$x]]!=""){
							  $dt[$idx]["size"][$v["id"]]=array(
										'cl_size_kode'=>$v["id"],
										'qty'=>$sheet[$col[$x]]
							);
							  
								
								//DELETE STOK 
							//	$this->db->delete('tbl_stok',array('sku'=>$sheet['B'],'cl_size_kode'=>$v["id"]));
								//END DELETE
						  }
					  }
					  $idx++;
				}

				$i++;
			  }
			  //echo "<pre>";print_r($dt);exit;
			  if(count($dt)>0)return $dt;
					
			}
			
	}
	function upload_barang(){
		$this->db->trans_begin();
		if(isset($_FILES['file']) && $_FILES['file']['name']!=""){
			$file = $_FILES['file']['tmp_name'];
			$load = PHPExcel_IOFactory::load($file);
			$sheets = $load->getActiveSheet()->toArray(null,true,true,true);
			$i = 1;
			$dt=array();
			$col=array('C','D','E','F','G','H','I','J','K','L','M','N');
			$idx=0;
			$size=$this->db->get('cl_size')->result_array();
			  foreach ($sheets as $sheet) {
				if ($i > 4) {
					  $ex=$this->db->get_where('tbl_master_barang',array('sku'=>$sheet['B']))->row_array();
					  if(!isset($ex["sku"])){
						  $dt[$idx]=array();
						  $dt[$idx]=array('sku'=>$sheet['B'],'nama_produk'=>$sheet['C']);
					  }
					  $idx++;
				}

				$i++;
			  }
			 // echo "<pre>";print_r($dt);exit;
			  if(count($dt)>0)$this->db->insert_batch('tbl_master_barang',$dt);
					
			}
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			return 0;
		}else{
			return $this->db->trans_commit();	
		}
			
	}
	// GOYZ CROTZZZ
	function simpan_data($table,$data,$get_id=""){ //$sts_crud --> STATUS NYEE INSERT, UPDATE, DELETE
		//echo $table;exit;
		//print_r($data);exit;
		$this->db->trans_begin();
		$post = array();
		$id = $this->input->post('id');
		if(isset($data["id"]))unset($data["id"]);
		$field_id = "id";
		$sts_crud = $this->input->post('sts_crud');
		unset($data['sts_crud']);
		
		switch ($table){
			case "tbl_profil":
				if(isset($_FILES['logo']) && $_FILES['logo']['name']!="")$data["logo"]=$this->upload_single("logo","logo");
			break;
			case "cl_kelurahan_desa":
				$data["id"]=$this->input->post('id');
				unset($data["kab_kota_id"]);
				unset($data["provinsi_id"]);
			break;
			case "cl_kecamatan":
				$data["id"]=$this->input->post('id');
				unset($data["provinsi_id"]);
			break;
			case "cl_provinsi":
			case "cl_kab_kota":
				$data["id"]=$this->input->post('id');
			break;
			case "ganti_pwd":
				$table="tbl_user";
				$data["password"]=$this->encrypt->encode($data['pwd_baru']);
				unset($data['pwd_baru']);
				unset($data['pwd_lama']);
				unset($data['u_pwd_baru']);
			break;
			
			case "tbl_user":
				if(isset($_FILES['ttd']) && $_FILES['ttd']['name']!="")$data["ttd"]=$this->upload_single("ttd","ttd");
				if(!isset($data['status']))$data['status']='T';
				if($sts_crud=='add'){
					$data['password']=$this->encrypt->encode($data['password']);
					$ex=$this->db->get_where('tbl_user',array('nama_user'=>$data["nama_user"]))->row_array();
					if(isset($ex["nama_user"])){echo 2;exit;}
					
				}
			break;
			case "cl_item_barang":
				if(!isset($data['status']))$data['status']='T';
			break;
			
			case "cl_jabatan":
				if(!isset($data['status']))$data['status']='T';
			break;
			case "cl_departemen":
				if(!isset($data['status']))$data['status']='T';
			break;
			
			
			default:$table = $table;break;
		}
		
		if($sts_crud == 'add'){
			$data['create_date']=date('Y-m-d H:i:s');
			$data['create_by']=$this->auth["nama_user"];
			$this->db->insert($table, $data);
		}elseif($sts_crud == 'edit'){
			
			$data['update_date']=date('Y-m-d H:i:s');
			$data['update_by']=$this->auth["nama_user"];
			if($table=='tbl_master_barang'){
				$this->db->update($table, $data, array('sku'=>$sku_lama));
			}else{
				$this->db->update($table, $data, array($field_id=>$id) );
			}
		}elseif($sts_crud == 'delete'){
			if($table=="cl_vendor"){
				$this->db->delete('cl_katalog', array('cl_vendor_id'=>$id) );
			}
			elseif($table=='tbl_master_barang'){
				$this->db->delete('tbl_master_barang', array('sku'=>$id) );
			}else{
				$this->db->delete($table, array($field_id=>$id) );
			}
		}
		
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			return 0;
		}else{
			return $this->db->trans_commit();	
		}
	}
	
	function upload_single($mod,$object){
		$file=date('YmdHis');
		switch($mod){
			case "logo": $upload_path='__assets/img/logo/';$file=date('YmdHis'); break;
			case "file_surat_kuasa": $upload_path='__repo/dok/';$file=$this->auth["id"]."_surat_kuasa_".date('YmdHis'); break;
			case "ttd": $upload_path='__repo/ttd/';$file="ttd_".date('YmdHis'); break;
			case "bukti": $upload_path='__repo/bukti_pembayaran/';$file=date('YmdHis'); break;
			case "po": $upload_path='__repo/pengajuan_po/';$file=date('YmdHis'); break;
		}
		
		$upload=$this->lib->uploadnong($upload_path, $object, $file);
		if($upload){return $upload;}
		else{echo 2;exit;}
	}
}