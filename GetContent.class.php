<?php
/*
 * Created on 2013��7��23��
 *
 * 获取网络内容
 */
class GetContent{
	const MUSIC_URL = 'http://api2.sinaapp.com/search/music/?';
	const APP_KEY = '0020120430';
	const APP_SECERT = 'fa6095e113cd28fd';
	const REQ_TYPE = 'music';
	
	/**
	 * {
    	"errcode": 0,
   	 	"msgtype": "music",
    	"music": {
	        "title": "最炫民族风",
	        "description": "music",
	        "musicurl": "http://stream10.qqmusic.qq.com/31432174.mp3",
	        "hqmusicurl": "http://stream10.qqmusic.qq.com/31432174.mp3"
   		 }
		}
	 */
	public function getMusic($music){
		$send_snoopy = new Snoopy; 
		$apiparams = array('appkey'=>self::APP_KEY, 'appsecert'=>self::APP_SECERT, 'reqtype'=>self::REQ_TYPE);
		$apikeyword = "&keyword=".urlencode($music);
		$submit = self::MUSIC_URL.http_build_query($apiparams).$apikeyword;
		$send_snoopy->submit($submit);
		return $send_snoopy->results;
		/*$apihost = self::MUSIC_URL;
	    //$apimethod = "search/music/?";
	    $apiparams = array('appkey'=>self::APP_KEY, 'appsecert'=>self::APP_SECERT, 'reqtype'=>self::REQ_TYPE);
	    $apikeyword = "&keyword=".urlencode($music);
	    $apicallurl = $apihost.http_build_query($apiparams).$apikeyword;
	    return file_get_contents($apicallurl);*/
	}
}
?>
