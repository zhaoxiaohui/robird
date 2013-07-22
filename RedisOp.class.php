i<?php
/*=======================================*\
 * @author zh
 * Redis相关操作
 * 单例模式
\*=======================================*/
class RedisOp {

	/* private vars */
	private static $redis = null;
	private static $redisOp = null;
    private function __construct() {
    	self::$redis = new Redis();
    	self::$redis->connect('127.0.0.1');
    }
    
    static function getInstance(){
        if(is_null(self::$redisOp))
            self::$redisOp = new self();
        return self::$redisOp;
    }
    /*======================================*\
     * 用户改变房间
     * 不需要权限认证
     * 查看用户当前房间，如果没有设置房间或者
     * 当前房间和欲进入房间不一样，则更新记录
     * 更新的记录主要有房间following
     * 和用户房间ID号
    \*======================================*/
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
    
    /*=======================================*\
     * 创建房间
     * 需要权限认证
     * 首先检查权限，如果用户已经注册则可以
     * 创建房间，每个人限制创建房间为5个
    \*=======================================*/

    public function createRoom($username, $roomid){
        //首先检查是否已经注册 注册俩表regist:username
        if(!self::$redis->sIsMember("regist:username", $username)){
            return ERROR_NO_AUTH;    
        }
        //检查用户房间数是不是超过限定
        if(self::$redis->lSize("username:$username:rooms") > ROOM_ONE_PERSON){
            return ERROR_ROOM_EXCEED;
        }
        //OK 检查完转到创建房间页面
        return OP_OK;
    }
}
?>
