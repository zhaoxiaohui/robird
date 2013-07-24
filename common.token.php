<?php
/*
 * Created on 2013-7-18
 *
 * @author firebird
 * @description 全局token设置
 */
 define("ZHHELP", "帮助");
 define("ENHELP", "help");
 
 
 define('MSG_TEXT', 'text');
 define('MSG_NEWS', 'news');
 define('MSG_URL',  'url');
 
 /*=============================*\
  * 页面链接
 \*=============================*/
 define('CREATE_ROOM', 'createRoom.php');
 define('SIGN_UP', 'http://219.142.86.69/robird/signUp.php');
 define('ADD_CASH', 'addCash.php');
 define('CHECK_ROOMS', 'checkRooms.php');
 
 //操作正常
 define('OP_OK',1);
 //当前房间号最高到999
 define('ROOM_NUM', 3);
 //每个人能够创建的房间数
 define('ROOM_ONE_PERSON', 5);
 
 
 /*====================================*\
  * 错误
 \*====================================*/
 define('ERROR_STRING_LONG', 55);  //string too long
 define('ERROR_NO_AUTH', 56);      //无权限
 define('ERROR_ROOM_EXCEED', 57);       //房间数超出
 /*=====================================*\
  * 信息返回值
  * 类似枚举
 /*=====================================*/
 define('CHRM',11);   //change room
 define('CRRM',12);   //create room
 define('CKRM',13);   //check room
 define('CKRMA',14);  //check all room
 define('SDM',15);   //standard message
 
 define('SEARCH_MUSIC', 16); //点歌
 define('SEARCH_PIC', 17);   //搜图
  define('SEARCH_PICS', 18);   //搜图
?>
