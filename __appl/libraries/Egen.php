<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * EGen library
 *
 * @author Eko Junaidi Salam <ekojs@kejaksaan.go.id,eko_junaidisalam@live.com>
 */
class Egen {
    var $CI = NULL;
	const DEFAULT_ENDPOINT_BASE = 'https://api.kejaksaan.go.id/';
	const DEFAULT_KEY = '12345_default_key';
	
	private $channelAccessToken;
    private $my_host;
    private $endpointBase;
    private $mime;
    private $my_header;
    private $my_method;
    private $my_param;
	
    function __construct() {
        $this->CI = & get_instance();
        $this->endpointBase = Egen::DEFAULT_ENDPOINT_BASE;
    }
	
	private function base64url_encode($data) {
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	private function base64url_decode($data) {
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}

	public function jwt_expire($iat){	
		$tgl = date('d-m-Y H:i:s',$iat);
		$time = new DateTime($tgl);
		$time->add(new DateInterval('PT'.API_EXPIRE.'M'));
		$stamp = $time->format('Y-m-d H:i:s');
		return intval(strtotime($stamp));
	}

	public function jwt_sign($header=null,$payload=null,$secret=null,$algo="HS256"){
		if(!empty($header) && !empty($payload) && !empty($secret) && !empty($algo)){
			$signature = null;
			switch($algo){
				case "RS256":
				case "HS256":
					$signature = $this->base64url_encode(hash_hmac("sha256",$header.".".$payload,hash('sha256',$secret),true));
					break;
			}
			return (!empty($signature)?$signature:false);
		}
		return false;
	}

	public function jwt($header=null,$payload=null,$secret=null,$algo="HS256"){
		if(!empty($header) && !empty($payload) && !empty($secret)){
			$h = $this->base64url_encode(json_encode($header));
			$p = $this->base64url_encode(json_encode($payload));
			$signature = $this->jwt_sign($h,$p,$secret,$algo);
			
			return (!empty($signature)?$h.".".$p.".".$signature:false);
		}
		return false;
	}

	public function jwt_read($token=null){
		if(!empty($token)){
			$t = explode('.',$token);
			if(count($t) == 3){
				$header = json_decode($this->base64url_decode($t[0]));
				return (!empty($header->alg) && !empty($header->typ)?$header:false);
			}
		}
		return false;
	}

	public function jwt_verify($token=null,$secret){
		$cek = $this->jwt_read($token);
		if(!empty($token) && $cek){
			$t = explode('.',$token);
			$jwt = json_decode($this->base64url_decode($t[1]));
			$expiring = (!empty($jwt->exp)?intval($jwt->exp):null);
			if(empty($expiring))
				return false;
			
			if($t[2] === $this->jwt_sign($t[0],$t[1],$secret,$cek->alg) && ($expiring >= intval(time()))){
				// return array(
					// 'header' => json_decode($this->base64url_decode($t[0])),
					// 'payload' => json_decode($this->base64url_decode($t[1])),
					// 'signature' => ($t[2] === jwt_sign($t[0],$t[1],$secret,$cek->alg) && ($expiring >= intval(time()))?'valid':'invalid')
				// );
				return json_decode($this->base64url_decode($t[1]));
			}
		}
		return false;
	}

	private function get_nocache_headers(){
		$headers = array(
			'Expires' => 'Tue, 1 Jan 1980 00:00:00 GMT',
			'Last-Modified' => gmdate( 'D, d M Y H:i:s' ) . ' GMT',
			'Cache-Control' => 'no-cache, must-revalidate, max-age=0',
			'Pragma' => 'no-cache',
		);
		return $headers;
	}

	public function nocache_headers(){
		$headers = $this->get_nocache_headers();
		foreach($headers as $name => $field_value)
			@header("{$name}: {$field_value}");
	}
	
	public function get_client_ip(){
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	public function log_activity($data){
		$file = (isset($data['file']) && !empty($data['file'])?$data['file']:'log_akses.log');
		$isi = (isset($data['isi']) && !empty($data['isi'])?$data['isi']:NULL);
		file_put_contents($file,$isi."\n",FILE_APPEND);
	}
	
	public function set_myheader($code = NULL,$msg = NULL){
		$obj = new StdClass();
		if ($code !== NULL){
			switch ($code) {
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'OK'; break;
				case 201: $text = 'Created'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'Conflict'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Internal Server Error'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Service Unavailable'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				case 600: $text = 'Database Unavailable'; break;
				case 701: $text = 'Uang titipan telah dikembalikan'; break;
				case 702: $text = 'Uang titipan belum dikembalikan'; break;
				case 'FFFE': $text = 'Maintenance'; break;
				case 'FFFF': $text = 'Unknown Error'; break;
				default:
					exit('Unknown http status code "' . htmlentities($code) . '"');
				break;
			}

			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
			// header($protocol . ' ' . $code . ' ' . $text);
			$obj->protocol = $protocol;
			$obj->code = $code;
			$obj->text = $text;
			$obj->json = json_encode(array('code'=>$code,'status'=>(!empty($msg)?$msg:$text)));
		}else{
			$obj->protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
			$obj->code = 200;
			$obj->text = 'OK';
			$obj->json = json_encode(array('code'=>200,'status'=>(!empty($msg)?$msg:'OK')));
		}
		
		header('Content-Type: application/json');
		header($obj->protocol . ' ' . $obj->code . ' ' . $obj->text);
		echo $obj->json;
		// $this->log_activity(array('isi' => "RESP ".date("Y-m-d H:i:s")." : ".json_encode($obj)."\n"));
		exit;
	}
	
	private function call_api(){
		$mime_default = 'application/x-www-form-urlencoded';
		$mime = (!empty($this->mime)?$this->mime:$mime_default);
		$header_default = array(
			'Content-Type: '.$mime,
			'Apikey: ' . $this->channelAccessToken
		);
		
		if(empty($this->my_host) || !isset($this->my_host)) $this->set_myheader(503,'Alamat Host tidak valid atau tidak ada !!!');
		
		$host = $this->my_host;
		$header = (!empty($this->my_header)?$this->my_header:$header_default);
		$method = (!empty($this->my_method)?$this->my_method:'GET');
		$param = $this->my_param;
		
		$process = curl_init($host);
		curl_setopt($process, CURLOPT_HTTPHEADER, $header);
		curl_setopt($process, CURLOPT_USERAGENT, 'EJS API');
		curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($process, CURLOPT_HEADER, FALSE);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($process, CURLOPT_BINARYTRANSFER, FALSE);
		curl_setopt($process, CURLOPT_FAILONERROR, TRUE);
		
		if('POST' === $method){
			if(empty($param) || !isset($param)) $this->set_myheader(400,'Parameter POST tidak valid atau tidak ada !!!');
			$param['Apikey'] = $this->channelAccessToken;
			curl_setopt($process, CURLOPT_POST, TRUE);
			curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($param));
		}else{
			curl_setopt($process, CURLOPT_HTTPGET, TRUE);
			curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		}
		
		$result = curl_exec($process);
		$errno = curl_errno($process);
		$errmsg = curl_error($process);
		curl_close($process);
		
		if($errno!=0) $this->set_myheader(503,'Service : '.$host.' '.$errmsg);
		
		return $result;
	}
	
	public function makeCall($host=null,$channelAccessToken=null){
		$this->my_host = (!empty($host)?$host:$this->endpointBase.'/api');
        $this->channelAccessToken = (!empty($channelAccessToken)?$channelAccessToken:Egen::DEFAULT_KEY);
		return $this;
	}
	
	public function setMime($mime=null){
		$mime_default = 'application/x-www-form-urlencoded';
		$this->mime = (!empty($mime)?$mime:$mime_default);
		return $this;
	}
	
	public function setHeader($header=null,$method='GET',$mode=true){
		$mime_default = 'application/x-www-form-urlencoded';
		$mime = (!empty($this->mime)?$this->mime:$mime_default);
		
		$header_default = array(
			'Content-Type: '.$mime,
			'x-api-key: ' . $this->channelAccessToken
		);
		$this->my_header = (!empty($header) && $mode?array_merge($header_default,$header):$header);
		$this->my_method = (!empty($method)?$method:'GET');
		
		return $this;
	}
	
	public function setParam($param=null){
		$this->my_param = (!empty($param)?$param:null);
		return $this;
	}
	
	public function sendIt(){
		return $this->call_api();
	}
	
	public function toJSON($data){
		$this->CI->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

}