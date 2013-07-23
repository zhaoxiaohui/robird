<?php
/*
 * Created on 2013��7��23��
 *
 * 获取网络内容
 */
class GetContent{
	const MUSIC_URL = 'http://api2.sinaapp.com/search/music';
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
		$send_snoopy = new Snoopy(); 
		$post = array();
		$post['appkey'] = self::APP_KEY;
		$post['appsecert'] = self::APP_SECERT;
		$post['reqtype'] = self::REQ_TYPE;
		$post['keyword'] = urlencode($music);
		$submit = MUSIC_URL;
		$send_snoopy->submittext($submit,$post);
		return $send_snoopy->results;
		/*$apihost = "http://api2.sinaapp.com/";
	    $apimethod = "search/music/?";
	    $apiparams = array('appkey'=>"0020120430", 'appsecert'=>"fa6095e113cd28fd", 'reqtype'=>"music");
	    $apikeyword = "&keyword=".urlencode($music);
	    $apicallurl = $apihost.$apimethod.http_build_query($apiparams).$apikeyword;
	    return file_get_contents($apicallurl);*/
	}
}
?>
