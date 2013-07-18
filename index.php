<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

include "Snoopy.class.php";
include "Robird.php";
include "common.token.php";

$rbird = new Robird();

if($rbird->checkSignature()){
	$recieve = $rbird->parseData();
	
	
	/*$simi = new Simi();
	
	$reply = $simi->request($recieve['Content']);
	if($reply['result'] == 100 )
		$reply = $reply['response'];
	else $reply = "小火鸟";
	$rbird->sendText($recieve['FromUserName'],$recieve['ToUserName'],"text",$reply);*/
	$content = $recieve['Content'];
	$rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $content);
	if($content == ZHHELP || $content == ENHELP){
		$reply = '1.输入数字可以切换频道';
		$rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $reply);
	}
}
?>
