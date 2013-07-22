<?php
/*=========================================*\
 * @author zh
 * 数据库相关操作
 * 单例模式
\*=========================================*/
class DB {

    private static $__instance = null;

    private function __construct(){
    }

    public static function getInstance(){
        if(is_null(self::$__instance)){
            self::$__instance = new self();
        }
        return self::$__instance;
    }

    /*==================================*\
     *function: 检查用户是否已经注册
     *return: yes or false
    \*==================================*/
    public function checkIsSignUp($username){
        return false;
    }
    /*=================================*\
     *function: 获得用户的房间列表
     *return: list
    \*=================================*/
    public function getRooms($username){
        return null;
    }
    /*=================================*\
     *function: 获得用户创建的房间数
     *return: int
    \*=================================*/
    public function getRoomNum($username){
        return 0;
    }
    /*=================================*\
     *function: 用户房间增加1
    \*=================================*/
    public function addOneRoom($username){
    }
    /*=================================*\
     *function: 用户房间减少1
    \*=================================*/
    public function removeOneRoom($username){
    }
    /*=================================*\
     *function: 用户注册
    \*=================================*/
    public function signUp($username, $usernickname, $password){
    }
    /*================================*\
     *function: 用户注销，删除记录
    \*================================*/
    public function signOut($username){
    }
    /*================================*\
     *function: 用户充值，用户金币用于
     *          广播
    \*================================*/
    public function addCash($username){
    }
}
?>
