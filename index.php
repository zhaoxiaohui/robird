<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

header("Content-Type:text/html;Charset=utf-8");

include "Snoopy.class.php";
include "Robird.php";
include "common.token.php";
include "Parse.class.php";
include "RedisOp.class.php";
include "GetContent.class.php";


$rbird = new Robird();
$parse = new Parse();
$redisOp = RedisOp::getInstance();
//$redisOp = new RedisOp();

if($rbird->checkSignature()){
	$rbird->parseData();
	if($rbird->getMsgType() == MSG_TEXT){
		//获得命令
	   	$parseResult = json_decode($parse->parseContent($rbird->getContent()));
	    try{
	        switch($parseResult->type){
			    //改变房间
			    case CHRM:
				    $redisOp->changeRoom($rbird->getFromUserName(), $parseResult->con);
				    $rbird->sendText('更新房间成功 现在房间号'.$parseResult->con);
			    	break;
			    //创建房间
			    case CRRM:
			    	$createRsult = $redisOp->createRoom($rbird->getFromUserName());
			    	switch($createRsult){
			    		case ERROR_NO_AUTH:
			    			$rbird->sendText('<a href="'.SIGN_UP.'">没有注册，无法创建房间，请先创建房间</a>');
			    			break;
			    		case ERROR_ROOM_EXCEED:
			    			$rbird->sendText('房间已满，请删除不需要的房间');
			    			break;
			    		case OP_OK:
			    			$rbird->sendText('创建房间');
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
			    	$rbird->sendText($parseResult->con);
			    	break;
			    //点歌服务
			    case SEARCH_MUSIC:
			    	//$rbird->sendText('sorry啊，木有找到~~~换首歌呗^_^');
			    	$getContent = new GetContent();
			    	$musicinfo = json_decode($getContent->getMusic($parseResult->con), true);
			    	//$rbird->sendText($getContent->getMusic($parseResult->con));
			    	if($musicinfo['music']['hqmusicurl'] == ""){
			    		$rbird->sendText('sorry啊，木有找到~~~换首歌呗^_^');
			    	}else{
			    		$rbird->sendMusic($musicinfo['music']);
			    	}
			    	break;
			    //搜图服务
			    case SEARCH_PIC:
			    	//$rbird->sendText('sorry啊，木有找到~~~换首歌呗^_^');
			    	$getContent = new GetContent();
			    	$picsinfo = $getContent->getPics($parseResult->con);
			    	if(count($picsinfo) == 0){
			    		$rbird->sendText('哎呀，搜到外太空也没有!');
			    	}else{
			    		$pics = array(
			    			array(
			    				'Title'=>$picsinfo[0]['title'],
			    				'Description'=>$picsinfo[0]['content'],
			    				'PicUrl'=>$picsinfo[0]['url'],
			    				'Url'=>$picsinfo[0]['originalContextUrl']
			    			),
			    			array(
			    				'Title'=>$picsinfo[1]['title'],
			    				'Description'=>$picsinfo[1]['content'],
			    				'PicUrl'=>$picsinfo[1]['url'],
			    				'Url'=>$picsinfo[1]['originalContextUrl']
			    			),
			    			array(
			    				'Title'=>'更多图片',
			    				'Description'=>'更多图片哦~~~',
			    				'PicUrl'=>$picsinfo[2]['url'],
			    				'Url'=>'http://219.142.86.69/robird/googleImages.php?key='.$parseResult->con
			    			)
			    		);
			    		$rbird->sendNews($pics);
			    	}
			    	break;
		    }
	    }catch(Exception $e){
	        $rbird->sendText($e->getMessage());
	    }
	}
	
}
?>
