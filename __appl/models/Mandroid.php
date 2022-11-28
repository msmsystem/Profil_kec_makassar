<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}
//
class Mandroid extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->auth = unserialize(base64_decode($this->session->userdata('s3ntr4lb0')));
		//$this->setting = $this->db->get("tbl_setting_apps")->row_array();
	}
	
	function getdata($type="", $balikan="", $p1="", $p2="",$p3="",$p4=""){
		$where  = " WHERE 1=1 ";
		$where2 = "";
		
		$dbdriver = $this->db->dbdriver;
		if($dbdriver == "postgre"){
			$select = " ROW_NUMBER() OVER (ORDER BY A.id DESC) as rowID, ";
		}else{
			$select = "";
		}
		
		if($this->input->post('key')){
			$key = $this->input->post('key');
			$kat = $this->input->post('kat');
			
			$where .= " AND LOWER(".$kat.") like '%".strtolower(trim($key))."%' ";				
		}
		
		if($this->auth["cl_user_group_id"] == '4'){
			//$where .= " AND A.create_by = '".$this->auth["nama_lengkap"]."' ";
		}

		switch($type){
			case "data_login":
				$sql = "
					SELECT A.*,B.user_group 
					FROM tbl_user A 
					LEFT JOIN cl_user_group B ON A.cl_user_group_id = B.id 
					WHERE A.username = '".$p1."' 
					ORDER BY A.id DESC
				";
			break;
			
			//Dashboard
			case "summary_persuratan_desa":
				$sql="SELECT * FROM cl_group_surat";
				$res=$this->db->query($sql)->result_array();
				
				foreach($res as $x=>$v){
					
					$sql = "
						SELECT A.alias, B.total 
						FROM cl_jenis_surat A
						LEFT JOIN (
							SELECT COUNT(id) as total, cl_jenis_surat_id
							FROM tbl_data_surat 
							WHERE cl_kelurahan_desa_id='".$this->input->post('desa')."'
							GROUP BY cl_jenis_surat_id
						) B ON B.cl_jenis_surat_id = A.id 
						WHERE A.group_id=".$v["id"];
					$res[$x]["child"]=$this->db->query($sql)->result_array();
				}
				
				return $res;
				
			break;
			case "summary_persuratan":
				$sql="SELECT * FROM cl_group_surat";
				$res=$this->db->query($sql)->result_array();
				
				foreach($res as $x=>$v){
					
					$sql = "
						SELECT A.alias, B.total 
						FROM cl_jenis_surat A
						LEFT JOIN (
							SELECT COUNT(id) as total, cl_jenis_surat_id
							FROM tbl_data_surat
							GROUP BY cl_jenis_surat_id
						) B ON B.cl_jenis_surat_id = A.id 
						WHERE A.group_id=".$v["id"];
					$res[$x]["child"]=$this->db->query($sql)->result_array();
				}
				
				return $res;
				
			break;
			//End Dashboard
			
			case "cetak_surat":
				$dbapak = array();
				$dibu = array();
				
				$sql = "
					SELECT A.*, B.nama_pekerjaan, C.nama_agama as agama,
						D.nama_status_kawin as status_kawin
					FROM tbl_data_penduduk A
					LEFT JOIN cl_jenis_pekerjaan B ON B.id = A.cl_jenis_pekerjaan_id
					LEFT JOIN cl_agama C ON C.id = A.agama
					LEFT JOIN cl_status_kawin D ON D.id = A.status_kawin
					WHERE A.nik = '".$p2."'
				";
				$data = $this->db->query($sql)->row_array();
				if($data){
					$sbapak = "
						SELECT A.*, B.nama_pekerjaan, C.nama_agama as agama,
							D.nama_status_kawin as status_kawin
						FROM tbl_data_penduduk A
						LEFT JOIN cl_jenis_pekerjaan B ON B.id = A.cl_jenis_pekerjaan_id
						LEFT JOIN cl_agama C ON C.id = A.agama
						LEFT JOIN cl_status_kawin D ON D.id = A.status_kawin
						WHERE A.no_kk = '".$data['no_kk']."' AND A.cl_status_hubungan_keluarga_id = '1'
					";
					$dbapak = $this->db->query($sbapak)->row_array();
					
					$sibu = "
						SELECT A.*, B.nama_pekerjaan, C.nama_agama as agama,
							D.nama_status_kawin as status_kawin
						FROM tbl_data_penduduk A
						LEFT JOIN cl_jenis_pekerjaan B ON B.id = A.cl_jenis_pekerjaan_id
						LEFT JOIN cl_agama C ON C.id = A.agama
						LEFT JOIN cl_status_kawin D ON D.id = A.status_kawin
						WHERE A.no_kk = '".$data['no_kk']."' AND A.cl_status_hubungan_keluarga_id = '2'
					";
					$dibu = $this->db->query($sibu)->row_array();
				}
				
				$ssurat = "
					SELECT A.*, 
						DATE_FORMAT(A.tgl_surat, '%d-%m-%Y') as tanggal_surat 
					FROM tbl_data_surat A
					WHERE A.id = '".$p3."'
				";
				// A.cl_jenis_surat_id = '".$p1."' AND A.nik = '".$p2."'
				$dsurat = $this->db->query($ssurat)->row_array();
				if($dsurat['info_tambahan'] != ""){
					$dsurat['info_tambahan'] = json_decode($dsurat['info_tambahan'], true);
				}
				
				$array['pemohon'] = $data;
				$array['bapak'] = $dbapak;
				$array['ibu'] = $dibu;
				$array['surat'] = $dsurat;
			break;
			case "data_surat":
				$sql = "
					SELECT A.*, B.nama_lengkap, C.jenis_surat,
						DATE_FORMAT(A.tgl_surat, '%d-%m-%Y') as tanggal_surat, 
						DATE_FORMAT(A.create_date, '%d-%m-%Y %H:%i') as tanggal_buat 
					FROM tbl_data_surat A
					LEFT JOIN tbl_data_penduduk B ON B.nik = A.nik
					LEFT JOIN cl_jenis_surat C ON C.id = A.cl_jenis_surat_id
					$where
					ORDER BY A.id DESC
				";
			break;
			case "data_keluarga":
				$sql = "
					SELECT A.*, B.nama_lengkap as nama_kepala_keluarga,
						C.total as jumlah_anggota_keluarga,
						DATE_FORMAT(A.create_date, '%d-%m-%Y %H:%i') as tanggal_buat 
					FROM tbl_kartu_keluarga A
					LEFT JOIN (
						SELECT no_kk, nama_lengkap
						FROM tbl_data_penduduk
						WHERE cl_status_hubungan_keluarga_id = '1'
					) B ON B.no_kk = A.no_kk
					LEFT JOIN (
						SELECT no_kk, COUNT(id) as total
						FROM tbl_data_penduduk
						GROUP BY no_kk
					) C ON C.no_kk = A.no_kk
					$where 
					ORDER BY A.id DESC
				";
			break;
			case "data_penduduk":
				$sql = "
					SELECT A.*, 
						B.nama_agama, C.nama_status_kawin, D.nama_pendidikan,
						DATE_FORMAT(A.create_date, '%d-%m-%Y %H:%i') as tanggal_buat 
					FROM tbl_data_penduduk A
					LEFT JOIN cl_agama B ON B.id = A.agama
					LEFT JOIN cl_status_kawin C ON C.id = A.status_kawin
					LEFT JOIN cl_pendidikan D ON D.id = A.pendidikan
					$where 
					ORDER BY A.id DESC
				";
			break;
			
			// Modul User Management
			case "tbl_user":
				$sql = "
					SELECT A.*, B.user_group
					FROM tbl_user A
					LEFT JOIN cl_user_group B ON B.id = A.cl_user_group_id
					$where
				";
			break;
			case "menu":
				$sql = "
					SELECT a.tbl_menu_id, b.nama_menu, b.type_menu, b.icon_menu, b.url, b.ref_tbl
						FROM tbl_user_prev_group a
					LEFT JOIN tbl_user_menu b ON a.tbl_menu_id = b.id 
					WHERE a.cl_user_group_id=".$this->auth['cl_user_group_id']." 
					AND (b.type_menu='P' OR b.type_menu='PC') AND b.status='1'
					ORDER BY b.urutan ASC
				";
				
				$parent = $this->db->query($sql)->result_array();
				$menu = array();
				foreach($parent as $v){
					$menu[$v['tbl_menu_id']]=array();
					$menu[$v['tbl_menu_id']]['parent']=$v['nama_menu'];
					$menu[$v['tbl_menu_id']]['icon_menu']=$v['icon_menu'];
					$menu[$v['tbl_menu_id']]['url']=$v['url'];
					$menu[$v['tbl_menu_id']]['type_menu']=$v['type_menu'];
					$menu[$v['tbl_menu_id']]['judul_kecil']=$v['ref_tbl'];
					$menu[$v['tbl_menu_id']]['child']=array();
					$sql="
						SELECT a.tbl_menu_id, b.nama_menu, b.url, b.icon_menu , b.type_menu, b.ref_tbl
							FROM tbl_user_prev_group a
						LEFT JOIN tbl_user_menu b ON a.tbl_menu_id = b.id 
						WHERE a.cl_user_group_id=".$this->auth['cl_user_group_id']." 
						AND (b.type_menu = 'C' OR b.type_menu = 'CHC') 
						AND b.status='1' AND b.parent_id=".$v['tbl_menu_id']." 
						ORDER BY b.urutan ASC
						";
					$child = $this->db->query($sql)->result_array();
					foreach($child as $x){
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]=array();
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['menu']=$x['nama_menu'];
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['type_menu']=$x['type_menu'];
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['url']=$x['url'];
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['icon_menu']=$x['icon_menu'];
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['judul_kecil']=$x['ref_tbl'];
						
						if($x['type_menu'] == 'CHC'){
							$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'] = array();
							$sqlSubChild="
								SELECT a.tbl_menu_id, b.nama_menu, b.url, b.icon_menu , b.type_menu, b.ref_tbl
									FROM tbl_user_prev_group a
								LEFT JOIN tbl_user_menu b ON a.tbl_menu_id = b.id 
								WHERE a.cl_user_group_id=".$this->auth['cl_user_group_id']." 
								AND b.type_menu = 'CC'
								AND b.parent_id_2 = ".$x['tbl_menu_id']."
								AND b.status='1' 
								ORDER BY b.urutan ASC
							";
							$SubChild = $this->db->query($sqlSubChild)->result_array();
							foreach($SubChild as $z){
								$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'][$z['tbl_menu_id']]['sub_menu'] = $z['nama_menu'];
								$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'][$z['tbl_menu_id']]['type_menu'] = $z['type_menu'];
								$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'][$z['tbl_menu_id']]['url'] = $z['url'];
								$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'][$z['tbl_menu_id']]['icon_menu'] = $z['icon_menu'];
							}
						}
						
					}
				}
				
				/*
				echo "<pre>";
				print_r($menu);exit;
				//*/
				
				$array = $menu;
			break;		

			case "menu_parent":
				$sql = "
					SELECT A.*
					FROM tbl_user_menu A
					WHERE (A.type_menu = 'P' OR A.type_menu = 'PC') AND A.status = '1'
					ORDER BY A.urutan ASC
				";
			break;
			case "menu_child":
				$sql = "
					SELECT A.*
					FROM tbl_user_menu A
					WHERE (A.type_menu = 'C') AND A.parent_id = '".$p1."' AND A.status = '1'
					ORDER BY A.urutan ASC
				";
			break;
			case "menu_child_2":
				$sql = "
					SELECT A.*
					FROM tbl_user_menu A
					WHERE A.type_menu = 'CC' AND A.parent_id_2 = '".$p1."' AND A.status = '1'
					ORDER BY A.urutan ASC
				";
			break;
			case "previliges_menu":
				$sql = "
					SELECT A.*
					FROM tbl_user_prev_group A
					WHERE A.tbl_menu_id = '".$p1."' AND A.cl_user_group_id = '".$p2."'
				";
			break;		
			// End Modul User Management
			
			
			default:
				if($balikan=='get'){$where .=" AND A.id=".$this->input->post('id');}
				$sql="
					SELECT A.*
					FROM ".$type." A ".$where."
				";
				if($balikan=='get')return $this->db->query($sql)->row_array();
			break;
		}
		
		if($balikan == 'json'){
			return $this->lib->json_grid($sql,$type);
		}elseif($balikan == 'row_array'){
			return $this->db->query($sql)->row_array();
		}elseif($balikan == 'result'){
			return $this->db->query($sql)->result();
		}elseif($balikan == 'result_array'){
			return $this->db->query($sql)->result_array();
		}elseif($balikan == 'json_encode'){
			$data = $this->db->query($sql)->result_array(); 
			return json_encode($data);
		}elseif($balikan == 'variable'){
			return $array;
		}
		
	}
	
	function getdata_laporan($type="", $balikan="", $p1="", $p2="",$p3="",$p4=""){
		$where  = " WHERE 1=1 ";
		switch($type){
			case "dashboard_pendidikan":
				if($this->input->post('desa')){
					$where .=" AND cl_kelurahan_desa_id ='".$this->input->post('desa')."' ";
				}
				$sql = "
					SELECT A.nama_pendidikan as keterangan, A.color,
						COALESCE(B.total, 0 ) as total
					FROM cl_pendidikan A
					LEFT JOIN (
						SELECT COUNT(id) as total, pendidikan
						FROM tbl_data_penduduk
						".$where." AND status_data = 'AKTIF'
						GROUP BY pendidikan
					) B ON B.pendidikan = A.id
				";
				
				/*
				$sql = "
					SELECT COUNT(id) as total, 'DOKTOR (S3)' as keterangan,
						'#D30102' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'DOKTOR (S3)'
					UNION ALL
					SELECT COUNT(id) as total, 'MAGISTER (S2)' as keterangan,
						'#3EDE37' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'MAGISTER (S2)'
					UNION ALL
					SELECT COUNT(id) as total, 'SARJANA (S1/D4)' as keterangan,
						'#FEF200' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'SARJANA (S1/D4)'
					UNION ALL
					SELECT COUNT(id) as total, 'AHLI MADYA (D3)' as keterangan,
						'#376FDE' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'AHLI MADYA (D3)'
					UNION ALL
					SELECT COUNT(id) as total, 'SMA' as keterangan,
						'#FFAA00' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'SMA'
					UNION ALL
					SELECT COUNT(id) as total, 'SMP' as keterangan,
						'#CDCDCD' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'SMP'
					UNION ALL
					SELECT COUNT(id) as total, 'SD' as keterangan,
						'#FE0000' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'SD'
					UNION ALL
					SELECT COUNT(id) as total, 'TK' as keterangan,
						'#EFFB01' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'TK'
					UNION ALL
					SELECT COUNT(id) as total, 'TIDAK SEKOLAH' as keterangan,
						'#27AAE2' as color
					FROM tbl_data_penduduk
					WHERE pendidikan = 'TIDAK SEKOLAH'
				";
				*/
			break;
			case "dashboard_status_kawin":
				if($this->input->post('desa')){
					$where .=" AND cl_kelurahan_desa_id ='".$this->input->post('desa')."' ";
				}
				$sql = "
					SELECT A.nama_status_kawin as keterangan, A.color,
						COALESCE(B.total, 0 ) as total
					FROM cl_status_kawin A
					LEFT JOIN (
						SELECT COUNT(id) as total, status_kawin
						FROM tbl_data_penduduk
						".$where." AND status_data = 'AKTIF'
						GROUP BY status_kawin
					) B ON B.status_kawin = A.id
				";
				
				/*
				$sql = "
					SELECT COUNT(id) as total, 'BELUM KAWIN' as keterangan,
						'#FE0000' as color
					FROM tbl_data_penduduk
					WHERE status_kawin = 'BELUM KAWIN'
					UNION ALL
					SELECT COUNT(id) as total, 'KAWIN' as keterangan,
						'#EFFB01' as color
					FROM tbl_data_penduduk
					WHERE status_kawin = 'KAWIN'
					UNION ALL
					SELECT COUNT(id) as total, 'DUDA' as keterangan,
						'#27AAE2' as color
					FROM tbl_data_penduduk
					WHERE status_kawin = 'DUDA'
					UNION ALL
					SELECT COUNT(id) as total, 'JANDA' as keterangan,
						'#EC365F' as color
					FROM tbl_data_penduduk
					WHERE status_kawin = 'JANDA'
				";
				//*/
			break;
			case "dashboard_status_data":
				if($this->input->post('desa')){
					$where .=" AND cl_kelurahan_desa_id ='".$this->input->post('desa')."' ";
				}
				$sql = "
					SELECT COUNT(id) as total, 'AKTIF' as keterangan,
						'#FF3200' as color
					FROM tbl_data_penduduk
					".$where." AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, 'MENINGGAL DUNIA' as keterangan,
						'#01FF25' as color
					FROM tbl_data_penduduk
					".$where." AND status_data = 'MENINGGAL DUNIA'
					UNION ALL
					SELECT COUNT(id) as total, 'PINDAH DOMISILI' as keterangan,
						'#EFFF00' as color
					FROM tbl_data_penduduk
					".$where." AND status_data = 'PINDAH DOMISILI'
				";
			break;
			case "dashboard_range_umur":
				if($this->input->post('desa')){
					$where .=" AND cl_kelurahan_desa_id ='".$this->input->post('desa')."' ";
				}
				$sql = "
					SELECT COUNT(id) as total, '0 - 5' as keterangan,
						'#D30102' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 0 AND 5
					AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, '6 - 10' as keterangan,
						'#3EDE37' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 6 AND 10
					AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, '11 - 15' as keterangan,
						'#FEF200' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 11 AND 15
					AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, '16 - 20' as keterangan,
						'#376FDE' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 16 AND 20
					AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, '21 - 30' as keterangan,
						'#FFAA00' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 21 AND 30
					AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, '31 - 40' as keterangan,
						'#CDCDCD' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 31 AND 40
					AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, '41 - 50' as keterangan,
						'#9ACD32' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 41 AND 50
					AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, '51 - 60' as keterangan,
						'#FF6347' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 51 AND 60
					AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, '> 60' as keterangan,
						'#EE82EE' as color
					FROM tbl_data_penduduk
					".$where." AND TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) > 60
					AND status_data = 'AKTIF'
				";
			break;
			case "dashboard_agama":
				if($this->input->post('desa')){
					$where .=" AND cl_kelurahan_desa_id ='".$this->input->post('desa')."' ";
				}
				$sql = "
					SELECT A.nama_agama as keterangan, A.color,
						COALESCE(B.total, 0 ) as total
					FROM cl_agama A
					LEFT JOIN (
						SELECT COUNT(id) as total, agama
						FROM tbl_data_penduduk
						".$where." AND status_data = 'AKTIF'
						GROUP BY agama
					) B ON B.agama = A.id
				";
				
				/*
				$sql = "
					SELECT COUNT(id) as total, 'ISLAM' as keterangan,
						'#D30102' as color
					FROM tbl_data_penduduk
					WHERE agama = 'ISLAM'
					UNION ALL
					SELECT COUNT(id) as total, 'KATOLIK' as keterangan,
						'#3EDE37' as color
					FROM tbl_data_penduduk
					WHERE agama = 'KATOLIK'
					UNION ALL
					SELECT COUNT(id) as total, 'KRISTEN' as keterangan,
						'#FEF200' as color
					FROM tbl_data_penduduk
					WHERE agama = 'KRISTEN'
					UNION ALL
					SELECT COUNT(id) as total, 'HINDU' as keterangan,
						'#376FDE' as color
					FROM tbl_data_penduduk
					WHERE agama = 'HINDU'
					UNION ALL
					SELECT COUNT(id) as total, 'BUDHA' as keterangan,
						'#FFAA00' as color
					FROM tbl_data_penduduk
					WHERE agama = 'BUDHA'
					UNION ALL
					SELECT COUNT(id) as total, 'KONGHUCU' as keterangan,
						'#CDCDCD' as color
					FROM tbl_data_penduduk
					WHERE agama = 'KONGHUCU'
				";
				*/
			break;
			case "dashboard_jenis_kelamin":
				if($this->input->post('desa')){
					$where .=" AND cl_kelurahan_desa_id ='".$this->input->post('desa')."' ";
				}
				$sql = "
					SELECT COUNT(id) as total, 'LAKI-LAKI' as keterangan,
						'#FFAA00' as color
					FROM tbl_data_penduduk
					".$where." AND jenis_kelamin = 'LAKI-LAKI' AND status_data = 'AKTIF'
					UNION ALL
					SELECT COUNT(id) as total, 'PEREMPUAN' as keterangan,
						'#376FDE' as color
					FROM tbl_data_penduduk
					".$where." AND  jenis_kelamin = 'PEREMPUAN' AND status_data = 'AKTIF'
				";
			break;
		}
		
		if($balikan == 'json'){
			return $this->lib->json_grid($sql,$type);
		}elseif($balikan == 'row_array'){
			return $this->db->query($sql)->row_array();
		}elseif($balikan == 'result'){
			return $this->db->query($sql)->result();
		}elseif($balikan == 'result_array'){
			return $this->db->query($sql)->result_array();
		}elseif($balikan == 'json_encode'){
			$data = $this->db->query($sql)->result_array(); 
			return json_encode($data);
		}elseif($balikan == 'variable'){
			return $array;
		}
	}
	
	function get_combo($type="", $p1="", $p2="", $p3="", $p4=""){
		$where = "";
		switch($type){
			case "cl_kelurahan_desa":
				$sql = "
					SELECT id, nama as txt
					FROM cl_kelurahan_desa
				";
			break;
			case "cl_kecamatan":
				$sql = "
					SELECT id, nama as txt
					FROM cl_kecamatan
				";
			break;
			case "cl_kab_kota":
				$sql = "
					SELECT id, nama as txt
					FROM cl_kab_kota
				";
			break;
			case "cl_provinsi":
				$sql = "
					SELECT id, nama as txt
					FROM cl_provinsi
				";
			break;
			
			case "cl_pendidikan":
				$sql = "
					SELECT id, nama_pendidikan as txt
					FROM cl_pendidikan
				";
			break;
			case "cl_agama":
				$sql = "
					SELECT id, nama_agama as txt
					FROM cl_agama
				";
			break;
			case "cl_status_kawin":
				$sql = "
					SELECT id, nama_status_kawin as txt
					FROM cl_status_kawin
				";
			break;
			case "cl_jenis_surat":
				$sql = "
					SELECT id, jenis_surat as txt
					FROM cl_jenis_surat
				";
			break;
			case "hubungan_keluarga":
				$sql = "
					SELECT id, nama as txt
					FROM cl_hubungan_keluarga
				";
			break;
			case "data_penduduk_anak":
				$sql = "
					SELECT nik as id, CONCAT(nik,' - ',nama_lengkap) as txt
					FROM tbl_data_penduduk
					WHERE cl_status_hubungan_keluarga_id = '3'
				";
				
				//AND status_data = 'AKTIF'
			break;
			case "data_penduduk_belum_menikah":
				$sql = "
					SELECT nik as id, CONCAT(nik,' - ',nama_lengkap) as txt
					FROM tbl_data_penduduk
					WHERE ( status_kawin = '2' OR status_kawin = '3' )
				";
				
				//AND status_data = 'AKTIF'
			break;
			case "data_penduduk":
				$sql = "
					SELECT nik as id, CONCAT(nik,' - ',nama_lengkap) as txt
					FROM tbl_data_penduduk
				";
				
				//WHERE status_data = 'AKTIF'
			break;
			case "cl_jenis_pekerjaan":
				$sql = "
					SELECT id, nama_pekerjaan as txt
					FROM cl_jenis_pekerjaan
				";
			break;
			
			default:
				$txt = str_replace("cl_","",$type);
				$sql = "
					SELECT id, $txt as txt
					FROM $type
				";
			break;
		}
		
		return $this->db->query($sql)->result_array();
	}
	
	function simpandata($table,$data,$sts_crud){ //$sts_crud --> STATUS NYEE INSERT, UPDATE, DELETE
		$this->db->trans_begin();
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}
		
		if($sts_crud == "add"){
			$data['create_date'] = date('Y-m-d H:i:s');
			$data['create_by'] = $this->auth['nama_lengkap'];
				
			unset($data['id']);
		}
		
		if($sts_crud == "edit"){
			$data['update_date'] = date('Y-m-d H:i:s');
			$data['update_by'] = $this->auth['nama_lengkap'];
		}
		
		switch($table){
			
			case "import_data_penduduk":
				$this->load->library('PHPExcel'); 
				if(!empty($_FILES['filename']['name'])){
					$ext = explode('.',$_FILES['filename']['name']);
					$exttemp = sizeof($ext) - 1;
					$extension = $ext[$exttemp];
					$upload_path = "./__repository/tmp_upload/";
					$filen = "IMPORTEXCEL-".date('Ymd_His');
					$filename =  $this->lib->uploadnong($upload_path, 'filename', $filen);
					
					$folder_aplod = $upload_path.$filename;
					$cacheMethod   = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
					$cacheSettings = array('memoryCacheSize' => '1600MB');
					PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
					if($extension=='xls'){
						$lib="Excel5";
					}else{
						$lib="Excel2007";
					}
					$objReader =  PHPExcel_IOFactory::createReader($lib); //excel2007
					ini_set('max_execution_time', 123456);
					$objPHPExcel = $objReader->load($folder_aplod); 
					$objReader->setReadDataOnly(true);
					$nama_sheet=$objPHPExcel->getSheetNames();
					$worksheet = $objPHPExcel->setActiveSheetIndex(0);
					$array_benar = array();
					for($i=2; $i <= $worksheet->getHighestRow(); $i++){
						//echo date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell("E".$i)->getCalculatedValue()));
						//echo $worksheet->getCell("E".$i)->getCalculatedValue();
						//exit;
						
						if($worksheet->getCell("E".$i)->getCalculatedValue()){
							$ultah = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell("E".$i)->getCalculatedValue()));
						}else{
							$ultah = null;
						}
						
						if($worksheet->getCell("C".$i)->getCalculatedValue() == 'L'){
							$jenis_kelamin = "LAKI-LAKI";
						}elseif($worksheet->getCell("C".$i)->getCalculatedValue() == 'P'){
							$jenis_kelamin = "PEREMPUAN";
						}
						
						$sts_kawin = "
							SELECT id
							FROM cl_status_kawin 
							WHERE nama_status_kawin LIKE '%".$worksheet->getCell("I".$i)->getCalculatedValue()."%'
						";
						$status_kawin = $this->db->query($sts_kawin)->row_array();
						
						$spendidikan = "
							SELECT id
							FROM cl_pendidikan
							WHERE nama_pendidikan LIKE '%".$worksheet->getCell("N".$i)->getCalculatedValue()."%'
						";
						$pendidikan = $this->db->query($spendidikan)->row_array();
						
						$spekerjaan = "
							SELECT id
							FROM cl_jenis_pekerjaan
							WHERE nama_pekerjaan LIKE '%".$worksheet->getCell("M".$i)->getCalculatedValue()."%'
						";
						$pekerjaan = $this->db->query($spekerjaan)->row_array();
						
						$cek_kk = $this->db->get_where('tbl_kartu_keluarga', array('no_kk'=>$worksheet->getCell("O".$i)->getCalculatedValue()) )->row_array();
						if(!$cek_kk){
							$array_kk = array(
								'no_kk' => $worksheet->getCell("O".$i)->getCalculatedValue(),
								'create_by' => $this->auth['nama_lengkap']." - Import Excel SIAK",
								'create_date' => date('Y-m-d H:i:s'),
							);
							$this->db->insert('tbl_kartu_keluarga', $array_kk);
						}
						
						$shubkeluarga = "
							SELECT id
							FROM cl_hubungan_keluarga
							WHERE nama LIKE '%".$worksheet->getCell("J".$i)->getCalculatedValue()."%'
						";
						$hubkeluarga = $this->db->query($shubkeluarga)->row_array();
						
						if($worksheet->getCell("L".$i)->getCalculatedValue() == "TDK_TH"){
							$gol_drh = null;
						}else{
							$gol_drh = $worksheet->getCell("L".$i)->getCalculatedValue();
						}
						
						$arrayinsertbenar = array(
							'no_kk' => $worksheet->getCell("O".$i)->getCalculatedValue(),
							'cl_status_hubungan_keluarga_id' => $hubkeluarga['id'],
							'nik' => $worksheet->getCell("A".$i)->getCalculatedValue(),
							'nama_lengkap' => $worksheet->getCell("B".$i)->getCalculatedValue(),
							'jenis_kelamin' => $jenis_kelamin,
							'agama' => $worksheet->getCell("F".$i)->getCalculatedValue(),
							'tempat_lahir' => $worksheet->getCell("D".$i)->getCalculatedValue(),
							'tgl_lahir' => $ultah,
							'status_kawin' => $status_kawin['id'],
							'pendidikan' => $pendidikan['id'],
							'cl_jenis_pekerjaan_id' => $pekerjaan['id'],
							'golongan_darah' => $gol_drh,
							'alamat' => $worksheet->getCell("U".$i)->getCalculatedValue(),
							
							'cl_provinsi_id' => $this->setting['cl_provinsi_id'],
							'cl_kab_kota_id' => $this->setting['cl_kab_kota_id'],
							'cl_kecamatan_id' => $this->setting['cl_kecamatan_id'],
							'cl_kelurahan_desa_id' => $this->setting['cl_kelurahan_desa_id'],
							
							'status_data' => "AKTIF",
							'create_by' => $this->auth['nama_lengkap'],
							'create_date' => date('Y-m-d H:i:s'),
						);
						
						array_push($array_benar, $arrayinsertbenar);
						
					}
					
					if(!empty($array_benar)){
						$this->db->insert_batch('tbl_data_penduduk', $array_benar);
					}
				}
			break;
			
			case "identitas_desa":
				$table = "tbl_setting_apps";
				
				$nama_provinsi = $this->db->get_where('cl_provinsi', array('id'=>$data['cl_provinsi_id']) )->row_array();
				$nama_kab_kota = $this->db->get_where('cl_kab_kota', array('id'=>$data['cl_kab_kota_id']) )->row_array();
				$nama_kecamatan = $this->db->get_where('cl_kecamatan', array('id'=>$data['cl_kecamatan_id']) )->row_array();
				$nama_kelurahan = $this->db->get_where('cl_kelurahan_desa', array('id'=>$data['cl_kelurahan_desa_id']) )->row_array();
				
				$data['nama_provinsi'] = $nama_provinsi['nama'];
				$data['nama_kab_kota'] = $nama_kab_kota['nama'];
				$data['nama_kecamatan'] = $nama_kecamatan['nama'];
				$data['nama_desa'] = $nama_kelurahan['nama'];
			break;
			case "data_surat":
				$table = "tbl_data_surat";
				$array = array();
				
				if($sts_crud == "add"){
					$data['cl_provinsi_id'] = $this->setting['cl_provinsi_id'];
					$data['cl_kab_kota_id'] = $this->setting['cl_kab_kota_id'];
					$data['cl_kecamatan_id'] = $this->setting['cl_kecamatan_id'];
					$data['cl_kelurahan_desa_id'] = $this->setting['cl_kelurahan_desa_id'];
					
					if($data['cl_jenis_surat_id'] == '16'){
						$penduduk = array(
							'status_kawin' => '3',
							'update_date' => date('Y-m-d H:i:s'),
							'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Keterangan Cerai Nikah",
						);
						$this->db->update('tbl_data_penduduk', $penduduk, array('nik'=>$data['nik']) );
					}
					if($data['cl_jenis_surat_id'] == '12'){
						$penduduk = array(
							'status_data' => 'PINDAH DOMISILI',
							'update_date' => date('Y-m-d H:i:s'),
							'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Keterangan Pindah Penduduk",
						);
						$this->db->update('tbl_data_penduduk', $penduduk, array('nik'=>$data['nik']) );
					}
					
					if($data['cl_jenis_surat_id'] == '9'){
						$penduduk = array(
							'status_data' => 'MENINGGAL DUNIA',
							'update_date' => date('Y-m-d H:i:s'),
							'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Kematian",
						);
						$this->db->update('tbl_data_penduduk', $penduduk, array('nik'=>$data['nik']) );
					}
				}
				
				if($sts_crud == "edit"){
					$data['cl_provinsi_id'] = $this->setting['cl_provinsi_id'];
					$data['cl_kab_kota_id'] = $this->setting['cl_kab_kota_id'];
					$data['cl_kecamatan_id'] = $this->setting['cl_kecamatan_id'];
					$data['cl_kelurahan_desa_id'] = $this->setting['cl_kelurahan_desa_id'];
					
					if($data['cl_jenis_surat_id'] == '16'){
						$penduduk = array(
							'status_kawin' => '3',
							'update_date' => date('Y-m-d H:i:s'),
							'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Keterangan Cerai Nikah",
						);
						$this->db->update('tbl_data_penduduk', $penduduk, array('nik'=>$data['nik']) );
						
						if($data['nik'] != $data['nik_lama']){
							$aktifkan = array(
								'status_kawin' => '1',
								'update_date' => date('Y-m-d H:i:s'),
								'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Keterangan Cerai Nikah",
							);
							$this->db->update('tbl_data_penduduk', $aktifkan, array('nik'=>$data['nik_lama']) );
						}
					}

					if($data['cl_jenis_surat_id'] == '12'){
						$penduduk = array(
							'status_data' => 'PINDAH DOMISILI',
							'update_date' => date('Y-m-d H:i:s'),
							'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Keterangan Pindah Penduduk",
						);
						$this->db->update('tbl_data_penduduk', $penduduk, array('nik'=>$data['nik']) );
						
						if($data['nik'] != $data['nik_lama']){
							$aktifkan = array(
								'status_data' => 'AKTIF',
								'update_date' => date('Y-m-d H:i:s'),
								'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Keterangan Pindah Penduduk",
							);
							$this->db->update('tbl_data_penduduk', $aktifkan, array('nik'=>$data['nik_lama']) );
						}
					}
					
					if($data['cl_jenis_surat_id'] == '9'){
						$penduduk = array(
							'status_data' => 'MENINGGAL DUNIA',
							'update_date' => date('Y-m-d H:i:s'),
							'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Kematian",
						);
						$this->db->update('tbl_data_penduduk', $penduduk, array('nik'=>$data['nik']) );
						
						if($data['nik'] != $data['nik_lama']){
							$aktifkan = array(
								'status_data' => 'AKTIF',
								'update_date' => date('Y-m-d H:i:s'),
								'update_by' => $this->auth['nama_lengkap']." - Via Data Surat Kematian",
							);
							$this->db->update('tbl_data_penduduk', $aktifkan, array('nik'=>$data['nik_lama']) );
						}
					}
				}
				
				if($sts_crud == "delete"){
					$datax = $this->db->get_where('tbl_data_surat', array('id'=>$id) )->row_array();
					if($datax){
						$aktifkan = array(
							'status_data' => 'AKTIF',
							'update_date' => date('Y-m-d H:i:s'),
							'update_by' => $this->auth['nama_lengkap']." - Via Data Surat",
						);
						$this->db->update('tbl_data_penduduk', $aktifkan, array('nik'=>$datax['nik']) );
					}
				}
				
				if($sts_crud == "add" || $sts_crud == "edit"){
					switch($data['cl_jenis_surat_id']){
						case "19":
							$array['nama_usaha'] = $data['nama_usaha'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
						case "17":
						case "16":
							$array['nama_pasangan'] = $data['nama_pasangan'];
							$array['nik_pasangan'] = $data['nik_pasangan'];
							$array['alamat_pasangan'] = $data['alamat_pasangan'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
						case "15":
							$array['nama_usaha'] = $data['nama_usaha'];
							$array['alamat_usaha'] = $data['alamat_usaha'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
						case "14":
							$array['tgl_mulai_berlaku'] = $data['tgl_mulai_berlaku'];
							$array['tgl_selesai_berlaku'] = $data['tgl_selesai_berlaku'];
							$array['keperluan'] = $data['keperluan'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
						case "12":
							$array['tgl_pindah'] = $data['tgl_pindah'];
							$array['alasan_pindah'] = $data['alasan_pindah'];
							$array['alamat_pindah'] = $data['alamat_pindah'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
						case "11":
							$array['nama_pasangan'] = $data['nama_pasangan'];
							$array['nama_ayah_pasangan'] = $data['nama_ayah_pasangan'];
							$array['alamat_pasangan'] = $data['alamat_pasangan'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
						case "9":
							$array['hari_kematian'] = $data['hari_kematian'];
							$array['tgl_kematian'] = $data['tgl_kematian'];
							$array['jam_kematian'] = $data['jam_kematian'];
							$array['tempat_kematian'] = $data['tempat_kematian'];
							$array['penyebab_kematian'] = $data['penyebab_kematian'];
							$array['nik_pelapor'] = $data['nik_pelapor'];
							$array['hubungan_pelapor'] = $data['hubungan_pelapor'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
						case "8":
							$array['usia_kandungan_kematian'] = $data['usia_kandungan_kematian'];
							$array['hari_kematian'] = $data['hari_kematian'];
							$array['tgl_kematian'] = $data['tgl_kematian'];
							$array['jam_kematian'] = $data['jam_kematian'];
							$array['tempat_kematian'] = $data['tempat_kematian'];
							$array['nama_pelapor'] = $data['nama_pelapor'];
							$array['hubungan_pelapor'] = $data['hubungan_pelapor'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
						case "6":
							$array['nama_bayi'] = $data['nama_bayi'];
							$array['jenis_kelamin_bayi'] = $data['jenis_kelamin_bayi'];
							$array['jam_lahir'] = $data['jam_lahir'];
							$array['hari_lahir'] = $data['hari_lahir'];
							$array['tgl_lahir'] = $data['tgl_lahir'];
							$array['tempat_lahir'] = $data['tempat_lahir'];
							
							$data['info_tambahan'] = json_encode($array);
						break;
					}
				}
				
				if(!empty($array)){
					foreach($array as $k => $v){
						unset($data[$k]);
					}
				}
				
				unset($data['nik_lama']);
				
				//echo "<pre>";
				//print_r($data);exit;
			break;
			case "data_keluarga":
				$table = "tbl_kartu_keluarga";
				
				if($sts_crud == "add" || $sts_crud == "edit"){
					$nik = $data['nik'];	
					$cl_status_hubungan_keluarga_id = $data['cl_status_hubungan_keluarga_id'];	
					
					unset($data['nik']);
					unset($data['cl_status_hubungan_keluarga_id']);
				}
				
				if($sts_crud == "delete"){
					$cekdata = $this->db->get_where('tbl_kartu_keluarga', array('no_kk'=>$data['no_kk']) )->row_array();
					if($cekdata){
						$this->db->update('tbl_data_penduduk', array('no_kk'=>null,'cl_status_hubungan_keluarga_id'=>null), array('no_kk'=>$cekdata['no_kk']) );
					}
				}
			break;
			case "data_penduduk":
				$table = "tbl_data_penduduk";
				
				if($sts_crud == "add" || $sts_crud == "edit"){
					$data['cl_provinsi_id'] = $this->setting['cl_provinsi_id'];
					$data['cl_kab_kota_id'] = $this->setting['cl_kab_kota_id'];
					$data['cl_kecamatan_id'] = $this->setting['cl_kecamatan_id'];
					$data['cl_kelurahan_desa_id'] = $this->setting['cl_kelurahan_desa_id'];
				}
			break;
			
			case "user_role_group":
				$id_group = $id;
				$this->db->delete('tbl_user_prev_group', array('cl_user_group_id'=>$id_group) );
				if(isset($data['data'])){
					$postdata = $data['data'];
					$row=array();
					foreach($postdata as $v){
						$pecah = explode("_",$v);
						$row["buat"]=0;
						$row["baca"]=0;
						$row["ubah"]=0;
						$row["hapus"]=0;
						
						switch($pecah[0]){
							case "C":
								$row["buat"]=1;
							break;
							case "R":
								$row["baca"]=1;
							break;
							case "U":
								$row["ubah"]=1;
							break;
							case "D":
								$row["hapus"]=1;
							break;
						}
						
						$row["tbl_menu_id"] = $pecah[1];
						$row["cl_user_group_id"] = $id_group;
						
						$cek_data = $this->db->get_where('tbl_user_prev_group', array('tbl_menu_id'=>$pecah[1], 'cl_user_group_id'=>$id_group) )->row_array();
						if(!$cek_data){
							$row['create_date'] = date('Y-m-d H:i:s');
							$row['create_by'] = $this->auth['nama_lengkap'];
							
							$this->db->insert('tbl_user_prev_group', $row);
						}else{
							if($row["buat"]==0)unset($row["buat"]);
							if($row["baca"]==0)unset($row["baca"]);
							if($row["ubah"]==0)unset($row["ubah"]);
							if($row["hapus"]==0)unset($row["hapus"]);

							$row['update_date'] = date('Y-m-d H:i:s');
							$row['update_by'] = $this->auth['nama_lengkap'];
							
							$this->db->update('tbl_user_prev_group', $row, array('tbl_menu_id'=>$pecah[1], 'cl_user_group_id'=>$id_group) );
						}
					}	
				}
			break;
			case "user_mng":
				$table = "tbl_user";
				if($sts_crud=='add' || $sts_crud == 'edit'){
					$data['password']=$this->encrypt->encode($data['password']);
				}
			break;
			case "user_group":
				$table = "cl_user_group";
			break;
			case "ubah_password":
				$this->load->library("encrypt");

				$table = "tbl_user";
				$password_lama = $this->encrypt->decode($this->auth["password"]);
				if($data["pwd_lama"] != $password_lama){
					echo 2;
					exit;
				}

				$data["password"] = $this->encrypt->encode($data["pwd_baru"]);

				unset($data["pwd_lama"]);
				unset($data["pwd_baru"]);
			break;
		}
		
		switch ($sts_crud){
			case "add":
				$insert = $this->db->insert($table,$data);
				$id = $this->db->insert_id();
				
				if($insert){
					if($table == "tbl_kartu_keluarga"){
						if(isset($nik)){
							foreach($nik as $k => $v){
								if(trim($v) != ""){
									$array_update = array(
										'no_kk' => $data['no_kk'],
										'cl_status_hubungan_keluarga_id' => $cl_status_hubungan_keluarga_id[$k],
									);
									$this->db->update( 'tbl_data_penduduk', $array_update, array('nik'=>$v) );
								}
							}
						}
					}
				}
			break;
			case "edit":
				$update = $this->db->update($table, $data, array('id' => $id) );	
				
				if($update){
					if($table == "tbl_kartu_keluarga"){
						if(isset($nik)){
							foreach($nik as $k => $v){
								if(trim($v) != ""){
									$array_update = array(
										'no_kk' => $data['no_kk'],
										'cl_status_hubungan_keluarga_id' => $cl_status_hubungan_keluarga_id[$k],
									);
									$this->db->update( 'tbl_data_penduduk', $array_update, array('nik'=>$v) );
								}
							}
						}
					}
				}
			break;
			case "delete":
				$this->db->delete($table, array('id' => $id));	
			break;
		}
		
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			return 'gagal';
		}else{
			 return $this->db->trans_commit();
		}
	}
	
}