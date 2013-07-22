<?php
/*=======================================*\
 * @author zh
 * Redis相关操作
 * 
\*=======================================*/
class RedisOp {

	/* private vars */
	var $_redis;
	
    function RedisOp() {
    	$_redis = new Redis();
    	$_redis->connect('127.0.0.1');
    }
    
    function changeRoom($username, $roomid){
    	$cur_roomid = $_redis->get("username:$username:roomid");
    	if(!$cur_roomid || $cur_roomid != $roomid){
    		if($cur_roomid)$_redis->sRem("roomid:$cur_roomid:following", $username);
    		$_redis->set("username:$username:roomid", $roomid);
    		$_redis->sAdd("roomid:$roomid:following", $username);
    	}
    }
}
?>