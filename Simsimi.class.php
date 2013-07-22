<?php
define("DATA","data");
class Simsimi {
	private $sid;
	private $_datapath;
	private $cookie;
	private $debug = false;
	private $_cookieexpired = 3600;
	private $_proxy;

	public function __construct($options)
	{
		$this->sid = isset($options['sid'])?$options['sid']:md5(uniqid());
		$this->_datapath = isset($options['datapath'])?$options['datapath']:$this->_datapath;
		$this->debug = isset($options['debug'])?$options['debug']:false;
		$this->_proxy = isset($options['proxy'])?$options['proxy']:false;
		$cookiename = $this->_datapath.$this->sid;
		$this->getCookie($this->_cookiename);
	}

	/**
	 * 鎶奵ookie鍐欏叆缂撳瓨
	 * @param  string $filename 缂撳瓨鏂囦欢鍚�	 * @param  string $content  鏂囦欢鍐呭
	 * @return bool
	 */
	public function saveCookie($filename,$content){
		$s = new SaeStorage();
		$s->write(DATA, $filename, $content);
	}

	/**
	 * 璇诲彇cookie缂撳瓨鍐呭
	 * @param  string $filename 缂撳瓨鏂囦欢鍚�	 * @return string cookie
	 */
	public function getCookie($filename){
		$s = new SaeStorage();
		if ($s->fileExists(DATA, $filename)) {
			$mtime = $s->getAttr(DATA, $filename, array("mtime"));
			$mtime = $mtime['mtime'];
			if ($mtime<time()-$this->_cookieexpired) return false;
			$data = $s->read(DATA, $filename);
			if ($data) $this->cookie = $data;
		} 
		return $this->cookie;
	}

	private function log($log){
		if ($this->debug ) {
			//file_put_contents('data/logdebug.txt', $log, FILE_APPEND);
		}
		return false;
	}

	public function init($lang='ch'){
		if ($this->cookie) return true;
		$url = "http://www.simsimi.com/talk.htm?lc=".$lang;     
	    //杩欎釜curl鏄洜涓哄畼鏂规瘡娆¤姹傞兘鏈夊敮涓�殑COOKIE锛屾垜浠繀椤诲厛鎶奀OOKIE鎷垮嚭鏉ワ紝涓嶇劧浼氫竴鐩磋繑鍥炩�HI鈥�    
	    $ch = curl_init();     
	    curl_setopt($ch, CURLOPT_URL, $url);
	    if ($this->_proxy) {
			curl_setopt($ch, CURLOPT_PROXY, $this->_proxy); 
	    }
	    curl_setopt($ch, CURLOPT_HEADER, 1); 
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:16.0) Gecko/20100101 Firefox/16.0'); 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
	    $content = curl_exec($ch);     
	    curl_close($ch);     
	    list($header, $body) = explode("\r\n\r\n", $content);     
	    preg_match("/set\-cookie:([^\r\n]*)/i", $header, $matches);  
	    $this->log($header); 
	    if (count($matches)>1)  {
	    	$this->cookie = $matches[1]; 
	    	$this->saveCookie($this->_datapath.$this->sid,$this->cookie);
	    	return true;
	    }
	    return false;
	}

	public function talk($msg,$lang="ch") {
			
			if (!$this->cookie) {
				$re = $this->init();
				if (!$re)
			    		return '鍏堢潯涓噿瑙�;
			}
			$snooy = new Snoopy();
			
			$submit = 'http://www.simsimi.com/func/req?msg='.urlencode($msg).'&lc='.$lang;
			$snooy->referer = "http://www.simsimi.com/talk.htm?lc=".$lang;	
		    if ($this->_proxy) {
				$snooy->proxy_host = $this->_proxy;
	    	}
	    	$snoopy->rawheaders['Cookie']= $this->cookie;
			$snooy->submit($submit);
			$result = $snooy->results;
			//return $result;
			//$this->log($result); 
			return "xx";
			if ($result) {
				$json = json_decode($result,true);
				if ($json['id']>2) return $json['response'];
			}
			
			return false;
	}
}