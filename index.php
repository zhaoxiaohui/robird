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
	$rbird->parseData();
	
	//获得命令
   	$parseResult = json_decode($parse->parseContent($rbird->postStr['Content']));
    //$pic = $rbird->postStr['PicUrl'];
    
    $news = array(
        'one'=>array(
            'Title'=>'哈哈',
            'Description'=>'你好',
            'PicUrl'=>'http://219.142.86.69/robird/rong.JPG',
            'Url'=>'http://219.142.86.69/robird/signUp.php'
        ),
        'two'=>array(
            'Title'=>'哈哈',
            'Description'=>'你好',
            'PicUrl'=>'http://219.142.86.69/robird/rong.JPG',
            'Url'=>'http://219.142.86.69/robird/signUp.php'
        )
    );
    $rbird->sendNews($news);
    /*
    try{
        switch($parseResult->type){
		    //改变房间
		    case CHRM:
			    $redisOp->changeRoom($rbird->postStr['FromUserName'], $parseResult->con);
			    $rbird->sendText($rbird->postStr['FromUserName'], $rbird->postStr['ToUserName'], 'text', '更新房间成功 现在房间号'.$parseResult->con);
		    	break;
		    //创建房间
		    case CRRM:
		    	$createRsult = $redisOp->createRoom($rbird->postStr['FromUserName']);
		    	switch($createRsult){
		    		case ERROR_NO_AUTH:
		    			//$rbird->sendUrl($recieve['FromUserName'], $recieve['ToUserName'], 'link', '创建房间', '创建房间', SIGN_UP);
		    			$rbird->sendText($rbird->postStr['FromUserName'], $rbird->postStr['ToUserName'], 'text', '<a href="'.SIGN_UP.'">没有注册，无法创建房间，请先创建房间</a>');
		    			break;
		    		case ERROR_ROOM_EXCEED:
		    			$rbird->sendText($rbird->postStr['FromUserName'], $rbird->postStr['ToUserName'], 'text', '房间已满，请删除不需要的房间');
		    			break;
		    		case OP_OK:
		    			$rbird->sendText($rbird->postStr['FromUserName'], $rbird->postStr['ToUserName'], 'text', '创建房间');
		    			break;	
		    	}
		    	break;
		    //查看房间
		    case CKRM:
		    	break;
		    //查看所有房间
		    case CKRMA:
		    	break;
		    //发送消息
		    case SDM:
		    	break;
	    }
    }catch(Exception $e){
        $rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $e->getMessage());
    }
    */
	/*if($content == '帮助' || $content == 'help'){
		$reply = '你好';
        //$reply = urldecode($reply);
		$rbird->sendText($recieve['FromUserName'], $recieve['ToUserName'], 'text', $reply);
	    //echo $reply;
    }*/
}
?>
