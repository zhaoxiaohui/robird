<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

header("Content-Type:text/html;Charset=utf-8");

include "Snoopy.class.php";
include "Robird.php";
include "common.token.php";
include "Parse.class.php";
include "RedisOp.class.php";

$rbird = new Robird();
$parse = new Parse();
$redisOp = RedisOp::getInstance();
//$redisOp = new RedisOp();

if($rbird->checkSignature()){
	$recieve = $rbird->parseData();
	
	//获得命令
   	$parseResult = json_decode($parse->parseContent($recieve['Content']));
    //$rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $parseResult->type);
	try{
        switch($parseResult->type){
		    //改变房间
		    case CHRM:
			    $redisOp->changeRoom($recieve['FromUserName'], $parseResult->con);
			    $rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', '更新房间成功 现在房间号'.$parseResult->con);
		    	break;
		    //创建房间
		    case CRRM:
		    	break;
		    //查看房间
		    case CKRM:
		    	break;
		    //发送消息
		    case SDM:
		    	break;
	    }
    }catch(Exception $e){
        $rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $e->getMessage());
    }
	/*if($content == '帮助' || $content == 'help'){
		$reply = '你好';
        //$reply = urldecode($reply);
		$rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $reply);
	    //echo $reply;
    }*/
}
?>
