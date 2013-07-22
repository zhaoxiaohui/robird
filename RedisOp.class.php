i<?php
/*=======================================*\
 * @author zh
 * Redis相关操作
 * 
\*=======================================*/
class RedisOp {

	/* private vars */
	private static $redis = null;
	private static $redisOp = null;
    public function __construct() {
    	self::$redis = new Redis();
    	self::$redis->connect('127.0.0.1');
    }
    
    static function getInstance(){
        if(is_null(self::$redisOp))
            self::$redisOp = new self();
        return self::$redisOp;
    }
    public function changeRoom($username, $roomid){
    	
        //self::$redis = new Redis();
        //self::$redis->connect('127.0.0.1');
        
        $cur_roomid = self::$redis->get("username:$username:roomid");
    	if(!$cur_roomid || $cur_roomid != $roomid){
    		if($cur_roomid)self::$redis->sRem("roomid:$cur_roomid:following", $username);
    		self::$redis->set("username:$username:roomid", $roomid);
    		self::$redis->sAdd("roomid:$roomid:following", $username);
    	}
    }
}
?>
