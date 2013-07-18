<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

header("Content-Type:text/html;Charset=utf-8");

include "Snoopy.class.php";
include "Robird.php";
include "common.token.php";

define("HELP","帮助");

$rbird = new Robird();


//echo "hello";
function zhencode($str){//对中文进行编码的函数
    $str=base64_encode($str);
    $str= "=?"."UTF-8?B?".$str."?=";
    return $str;
}
if($rbird->checkSignature()){
	$recieve = $rbird->parseData();
	
	
	/*$simi = new Simi();
	
	$reply = $simi->request($recieve['Content']);
	if($reply['result'] == 100 )
		$reply = $reply['response'];
	else $reply = "小火鸟";
	$rbird->sendText($recieve['FromUserName'],$recieve['ToUserName'],"text",$reply);*/
	$content = $recieve['Content'];
    //$content = $_GET['m'];
	//$rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $content);
	if($content == '帮助' || $content == 'help'){
		$reply = '你好';
        //$reply = urldecode($reply);
		$rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $reply);
	    //echo $reply;
    }
}
?>
