<?php
/**
 * 自动应答引擎
 * @author firebird
 * @date 20130710
 */
define("KEY","1c7af913-3590-4fca-a54e-310606f63181");
define("URL","http://sandbox.api.simsimi.com/request.p");
define("LANGUAGE","ch");
define("FILTER",0.0);
class Simi{
	/**
	 * 发送消息信息给SIMI服务器 ...
	 * @param string $message 消息
	 * @return SIMI服务器返回信息
	 */
	public function request($message){
		$send_snoopy = new Snoopy;
		/*设置发送信息*/
		$post = array();
		$post['key'] = KEY; 
		$post['lc'] = LANGUAGE;
		$post['ft'] = FILTER;
		$post['text'] = $message;
		
		
		$submit = URL;
		$send_snoopy->submit($submit,$post);
		return json_decode($send_snoopy->results, true);
	}
}
?>