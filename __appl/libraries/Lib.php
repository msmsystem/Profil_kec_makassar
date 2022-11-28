<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	LIBRARY CIPTAAN JINGGA LINTAS IMAJI
	KONTEN LIBRARY :
	- Upload File
	- Upload File Multiple
	- RandomString
	- CutString
	- Kirim Email
	- Konversi Bulan
	- Fillcombo
*/
class Lib {
	public function __construct(){
		
	}
	

	
	//class Upload File Version 1.0 - Beta
	function uploadnong($upload_path="", $object="", $file=""){
		//$upload_path = "./__repository/".$folder."/";
		
		//$ext = explode('.',$_FILES[$object]['name']);
		//$exttemp = sizeof($ext) - 1;
		//$extension = $ext[$exttemp];
		$extension = pathinfo($_FILES[$object]['name'], PATHINFO_EXTENSION); 
		$filename =  $file.'.'.$extension;
		
		$files = $_FILES[$object]['name'];
		$tmp  = $_FILES[$object]['tmp_name'];
		if(!file_exists($upload_path))mkdir($upload_path, 0777, true);
		if(file_exists($upload_path.$filename)){
			unlink($upload_path.$filename);
			$uploadfile = $upload_path.$filename;
		}else{
			$uploadfile = $upload_path.$filename;
		} 
		//echo $_SERVER["DOCUMENT_ROOT"].preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])).'/'.$uploadfile;exit;
		move_uploaded_file($tmp, $uploadfile);
		/*if (!chmod($uploadfile, 0775)) {
			echo "Gagal mengupload file";
			exit;
		}*/
		
		return $filename;
	}
	// end class Upload File
	
	//class Upload File Multiple Version 2.0 - Beta Update Goyz
	function uploadmultiplenong($upload_path=""){
		$nama_file=array();
		//print_r($_FILES);exit;
		if(!file_exists($upload_path))mkdir($upload_path, 0777, true);
		if($_FILES){
			foreach($_FILES as $v=>$x){
				$fileElementName=$v;
				if(count($_FILES[$fileElementName]['name'])>0){
					foreach($_FILES[$fileElementName]['name'] as $a=>$b){
						if($b!=""){
							//echo $b.'<br>';
							$new_name=date('YmdHis')."_".$a;
							$ext = explode('.',$b);
							$exttemp = sizeof($ext) - 1;
							$extension = $ext[$exttemp];
							$filename =  $new_name.'.'.$extension;
							$nama_file[]=$filename;
							$files = $_FILES[$fileElementName]['name'][$a];
							$tmp  = $_FILES[$fileElementName]['tmp_name'][$a];
							if(file_exists($upload_path.$filename)){
								unlink($upload_path.$filename);
								$uploadfile = $upload_path.$filename;
							}else{
								$uploadfile = $upload_path.$filename;
							} 
							
							move_uploaded_file($tmp, $uploadfile);
							if (!chmod($uploadfile, 0775)) {
								echo "Gagal mengupload file";
								exit;
							}
						}
					}
				}
				
				
			}
			return $nama_file;
		}
		
		//exit;
		
	}
	//end Class Upload File
	
	//Hapus File
	function hapus_file($type="", $path=""){
		switch($type){
			case "satu":
				unlink($path);
			break;
		}
	}
	//End Hapus File
	
	//class Make Directory
	function makedir($dirpath="", $mode=0777){
		if(!is_dir($dirpath)) {
			mkdir($dirpath);
		}
		return true;
	}
	//End class Make Directory	
	
	//class Random String Version 1.0
	function randomString($length,$parameter="") {
        $str = "";
		$rangehuruf = range('A','Z');
		$rangeangka = range('0','9');
		if($parameter == 'angka'){
			$characters = array_merge($rangeangka);
		}elseif($parameter == "huruf"){
			$characters = array_merge($rangeangka);
		}else{
			$characters = array_merge($rangehuruf, $rangeangka);
		}
         $max = count($characters) - 1;
         for ($i = 0; $i < $length; $i++) {
              $rand = mt_rand(0, $max);
              $str .= $characters[$rand];
         }
         return $str;
    }
	//end Class Random String
	
	//Class CutString
	function cutstring($text, $length) {
		//$isi_teks = htmlentities(strip_tags($text));
		$isi_teks = $text;
		$isi = substr($isi_teks,0,$length);
		$isi = substr($isi_teks,0,strrpos($isi," "));
		$isi = $isi.' ...';
		return $isi;
	}
	//end Class CutString
	
	//Class Kirim Email
	function kirimemail($email="", $subject="", $isi="",$atc="",$cc=""){
		$ci =& get_instance();
		$ci->load->library('encrypt');
		$config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => $ci->config->item('Host'),
		    'smtp_port' => $ci->config->item('Port'),
		    'smtp_user' => $ci->config->item('Username'),
			'smtp_pass' => $ci->config->item('Password'),
		    'mailtype'  => 'html',
		    'charset'   => 'iso-8859-1'
		    );
		
		$ci->load->library('email', $config);
		$ci->email->set_newline("\r\n");
		$ci->email->from($ci->config->item('EmaiFrom'), $ci->config->item('EmaiFromName'));
		$ci->email->to($email);
		if($cc!=""){
			if(is_array($cc))$ci->email->cc($cc);
		}
		$ci->email->subject($subject);
		$ci->email->message($isi);
		if($atc!=""){
			$ci->email->attach($atc);
		}
		if($ci->email->send())return  1;
		else return 2;
		
	}	
	//End Class KirimEmail
	
	//Class Konversi Bulan
	function konversi_bulan($bln){
		switch($bln){
			case 1:$bulan='Januari';break;
			case 2:$bulan='Februari';break;
			case 3:$bulan='Maret';break;
			case 4:$bulan='April';break;
			case 5:$bulan='Mei';break;
			case 6:$bulan='Juni';break;
			case 7:$bulan='Juli';break;
			case 8:$bulan='Agustus';break;
			case 9:$bulan='September';break;
			case 10:$bulan='Oktober';break;
			case 11:$bulan='November';break;
			case 12:$bulan='Desember';break;
		}
		return $bulan;
	}
	//End Class Konversi Bulan
	
	//Class Fillcombo
	function fillcombo($type="", $balikan="", $p1="", $p2="", $p3=""){
		$ci =& get_instance();
		$ci->load->model('mbackend');
		
		$v = $ci->input->post('v');
		if($v != ""){
			$selTxt = $v;
		}else{
			$selTxt = $p1;
		}
		
		
		$optTemp = '<option value="0"> -- Pilih -- </option>';
		
		switch($type){
			case "status":
				$data = array(
					'0' => array('id'=>'1','txt'=>'Aktif'),
					'1' => array('id'=>'0','txt'=>'Non-Aktif'),
				);
			break;
			case "flag_outlet":
				$data = array(
					'0' => array('id'=>'1','txt'=>'Semua Outlet'),
					'1' => array('id'=>'0','txt'=>'Outlet Tertentu'),
				);
			break;
			default:
				$data = $ci->mbackend->get_combo($type, $p1, $p2);
			break;
		}
		
		if($data){
			foreach($data as $k=>$v){
				$optTemp .= '<option value="'.$v['id'].'">'.$v['txt'].'</option>';	
			}
		}
		
		if($balikan == 'return'){
			return $optTemp;
		}elseif($balikan == 'echo'){
			echo $optTemp;
		}
		
	}
	//End Class Fillcombo
	
	function assetsmanager($type,$p1){
		$assets = "";
		$ci =& get_instance();
		$base_url = $ci->config->item('base_url');
		switch($type){
			case "js":
				
				switch($p1){
					
					case "main":
						$arrayjs = array(
							'__assets/template/bower_components/jquery/dist/jquery.min.js',
							'__assets/template/bower_components/bootstrap/dist/js/bootstrap.min.js',
							'__assets/template/bower_components/fastclick/lib/fastclick.js',
							'__assets/template/easyui/jquery.easyui.min.js',
							//'__assets/pluginsall/jquery-validation/dist/jquery.validate.js',
							//'__assets/pluginsall/maskmoney/jquery.maskMoney.js',
							//'__assets/pluginsall/ckeditor/ckeditor.js',
							'__assets/template/dist/js/adminlte.min.js',
							'__assets/template/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js',
							'__assets/template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
							//'__assets/template/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
							//'__assets/template/bower_components/timepicker/bootstrap-timepicker.min.js',
							'__assets/template/bower_components/moment/moment.js',
							'__assets/template/bower_components/select2/dist/js/select2.full.min.js',
							//'__assets/pluginsall/highcharts/highcharts.js',
							//'__assets/pluginsall/highcharts/exporting.js',
							'__assets/template/dist/js/typeahead.js',
							//'__assets/template/dist/js/loading-overlay.js',
							//'__assets/template/dist/js/autoNumeric.js',
							'__assets/js/fungsi.js',
							
						);
					break;
				}
				
				foreach($arrayjs as $k){
					$version = filemtime($k);
					$assets .= "
						<script src='".$base_url.$k."?v=".$version."'></script> 
					";
				}
				
			break;
			case "css":
				
				switch($p1){
					
					case "main":
						$arraycss = array(
							'__assets/template/bower_components/bootstrap/dist/css/bootstrap.min.css',
							'__assets/template/bower_components/font-awesome/css/font-awesome.min.css',
							'__assets/template/bower_components/Ionicons/css/ionicons.min.css',
							//'__assets/template/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
							//'__assets/template/bower_components/timepicker/bootstrap-timepicker.min.css',
							'__assets/template/easyui/themes/metro-gray/easyui.css',
							'__assets/template/bower_components/select2/dist/css/select2.min.css',
							'__assets/template/dist/css/AdminLTE.min.css',
							'__assets/template/dist/css/skins/_all-skins.min.css',
						);
					break;
				}
				
				foreach($arraycss as $k){
					$version = filemtime($k);
					$assets .= "
						<link href='".$base_url.$k."?v=".$version."' rel='stylesheet'>
					";
				}
				
			break;
			
			
		}
		
		return $assets;
	}
	public function buat_excel($parameter){
		$ci =& get_instance();
		$ci->load->library('php_excel');
    	$alphas = range("A", "Z");
    	$objPHPExcel = new PHPExcel();
    	$sNAMESS = "";
    	foreach ($parameter as $key => $value) {
    		${$key}=$value;
    	}
			if(!isset($col)){
				echo "Definisi Kolom tidak ada!";
				die();
			}else{
				$jumlahKolom = count($col);
				if($jumlahKolom==0){
					echo "Definisi Kolom tidak ada!";
					die();
				}else{
					if($jumlahKolom>26){
						$alphas = $ci->createColumnsArray("c");
					}
				}
			}
			
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle($sNAMESS);
			$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(55);
			$loop = 1;
			$arrKolom =0;
			foreach ($col as $colvalue) {
				foreach ($colvalue as $keycol => $valuecol) {
					${$keycol}=$valuecol;
				}
				if(isset($nilai)){
					if($nilai!=""){
						$objPHPExcel->getActiveSheet()->setCellValue($alphas[$arrKolom]."1", $nilai);
					}
				}
				if(isset($fontsize)){
					if($fontsize!=0){
						$objPHPExcel->getActiveSheet()->getStyle($alphas[$arrKolom]."1")->getFont()->setSize($fontsize);
					}
				}
				if(isset($bold)){	
					if($bold){
						$objPHPExcel->getActiveSheet()->getStyle($alphas[$arrKolom]."1")->getFont()->setBold(true);
					}
				}
				$classvertical = PHPExcel_Style_Alignment::VERTICAL_CENTER;
				if(isset($valign)){	
					switch ($valign) {
						case 'top':
							$classvertical = PHPExcel_Style_Alignment::VERTICAL_TOP;
							break;
						case 'bottom':
							$classvertical = PHPExcel_Style_Alignment::VERTICAL_BOTTOM;
							// $objPHPExcel->getActiveSheet()->getStyle($alphas[$arrKolom]."1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
							break;
					}
				}
				$objPHPExcel->getActiveSheet()->getStyle($alphas[$arrKolom]."1")->getAlignment()->setVertical($classvertical);

				$classhorizontal = PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
				if(isset($halign)){	
					switch ($halign) {
						case 'center':
							$classhorizontal = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;							
							break;
						case 'right':
							$classhorizontal = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
							break;
					}
				}
				$objPHPExcel->getActiveSheet()->getStyle($alphas[$arrKolom]."1")->getAlignment()->setHorizontal($classhorizontal);
				$loop++;
				$arrKolom++;
			}
			if(isset($rsl)){
				$nomor = 1;
				$rowloc = 2;
				// echo "
				foreach ($rsl as $key => $value) {
					$colloc=0;
					$arrKolom =0;
					foreach ($col as $colvalue) {
					        foreach ($colvalue as $keycol => $valuecol) {
							$ketemu = false;
							if($keycol=="namanya"){
								if($valuecol=="nomor"){
									$valueval = $nomor;	
								}else{
									$valueval = $value[$valuecol];	
								}
								$objPHPExcel->getActiveSheet()->setCellValue($alphas[$arrKolom].$rowloc, $valueval);
								$objPHPExcel->getActiveSheet()->getColumnDimension($alphas[$arrKolom])->setAutoSize(true);
								$ketemu = true;
							}
							if($keycol=="format"){
								switch ($valuecol) {
									case 'datetime':
										$objPHPExcel->getActiveSheet()->getStyle($alphas[$arrKolom].$rowloc)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
										break;
									case 'angkakoma':
										$objPHPExcel->getActiveSheet()->getStyle($alphas[$arrKolom].$rowloc)->getNumberFormat()->setFormatCode('_(#,##0.00_);_(\(#,##0.00\);_("-"??_);_(@_)');
										break;			
								}
							}
						}
						$arrKolom++;			
					}
					$rowloc++;
					$nomor++;
				}
			}
			
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$sFILNAM.'.xls"'); 
			header('Cache-Control: max-age=0'); //no cache
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');    	
	}
	function result_query($sql,$type=""){
		$ci =& get_instance();
		switch($type){
			case "json":
				$page = (integer) (($ci->input->post('page')) ? $ci->input->post('page') : "1");
				$limit = (integer) (($ci->input->post('rows')) ? $ci->input->post('rows') : "10");
				$count = $ci->db->query($sql)->num_rows();
				if( $count >0 ) { $total_pages = ceil($count/$limit); } else { $total_pages = 0; } 
				if ($page > $total_pages) $page=$total_pages; 
				$start = $limit*$page - $limit; // do not put $limit*($page - 1)
				if($start<0) $start=0;
				  
				$sql = $sql . " LIMIT $start,$limit";
			
				$data=$ci->db->query($sql)->result_array();  
						
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
			case "row_obj":return $ci->db->query($sql)->row();break;
			case "row_array":return $ci->db->query($sql)->row_array();break;
			default:return $ci->db->query($sql)->result_array();break;
		}
	}
	
}