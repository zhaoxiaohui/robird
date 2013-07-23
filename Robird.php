<?php
define("TOKEN", "firebird");
define("ACCOUNT", "downtownguy.hui@gmain.com");
define("PASSWORD", "710.MENGxiangHUI");
define("METHOD", "redis");

class Robird
{
    private $postStr = array();
	// 构造函数
	public function __construct(){
		// 读取cookie
		if(METHOD == 'redis'){
			$this->cookie = $this->redisCookie();
		}else{
			$this->cookie = $this->read('cookie.log');
		}
	}

	/**
	 * 检查是否是合理的请求(官方函数)
	 * @return boolean 
	 */
	public function checkSignature()
	{
		if($_GET){		
			$signature = $_GET["signature"];
			$timestamp = $_GET["timestamp"];
			$nonce = $_GET["nonce"];	

			$token = TOKEN;
			$tmpArr = array($token, $timestamp, $nonce);
			sort($tmpArr);
			$tmpStr = implode( $tmpArr );
			$tmpStr = sha1( $tmpStr );
			if( $tmpStr == $signature ){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	/**
	 * 主动发消息
	 * @param  string $id      用户的fakeid
	 * @param  string $content 发送的内容
	 * @return [type]          [description]
	 */
	public function send($id,$content)
	{
		$send_snoopy = new Snoopy; 
		$post = array();
		$post['tofakeid'] = $id;
		$post['type'] = 1;
		$post['content'] = $content;
		$post['ajax'] = 1;
        $send_snoopy->referer = "http://mp.weixin.qq.com/cgi-bin/singlemsgpage?fromfakeid={$id}&msgid=&source=&count=20&t=wxm-singlechat&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "http://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response";
		$send_snoopy->submit($submit,$post);
		return $send_snoopy->results;
	}


	/**
	 * 批量发送(可能需要设置超时)
	 * @param  [type] $ids     用户的fakeid集合,逗号分割
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function batSend($ids,$content)
	{
		$ids_array = explode(",", $ids);
		$result = array();
		foreach ($ids_array as $key => $value) {
			$send_snoopy = new Snoopy; 
			$post = array();
			$post['type'] = 1;
			$post['content'] = $content;
			$post['ajax'] = 1;
            $send_snoopy->referer = "http://mp.weixin.qq.com/cgi-bin/singlemsgpage?fromfakeid={$value}&msgid=&source=&count=20&t=wxm-singlechat&lang=zh_CN";
			$send_snoopy->rawheaders['Cookie']= $this->cookie;
			$submit = "http://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response";
			$post['tofakeid'] = $value;
			$send_snoopy->submit($submit,$post);
			$tmp = $send_snoopy->results;
			array_push($result, $tmp);
		}
		return $result;
	}	

	/**
	 * 获取用户的信息
	 * @param  string $id 用户的fakeid
	 * @return [type]     [description]
	 */
	public function getInfo($id)
	{
		$send_snoopy = new Snoopy; 
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "http://mp.weixin.qq.com/cgi-bin/getcontactinfo?t=ajax-getcontactinfo&lang=zh_CN&fakeid=".$id;
		$send_snoopy->submit($submit,array());
		$result = json_decode($send_snoopy->results,1);
		if(!$result){
			$this->login();
		}
		return $result;
    }
    public static function xmlSafeStr($str)
    {   
        return '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str).']]>';   
    } 
    /**
    * 数据XML编码
    * @param mixed $data 数据
    * @return string
    */
    public static function data_to_xml($data) {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml    .=  "<$key>";
            $xml    .=  ( is_array($val) || is_object($val)) ? self::data_to_xml($val)  : self::xmlSafeStr($val);
            list($key, ) = explode(' ', $key);
            $xml    .=  "</$key>";
        }
        return $xml;
    }   

    /**
    * XML编码
    * @param mixed $data 数据
    * @param string $root 根节点名
    * @param string $item 数字索引的子节点名
    * @param string $attr 根节点属性
    * @param string $id   数字索引子节点key转换的属性名
    * @param string $encoding 数据编码
    * @return string
    */
    public function xml_encode($data, $root='xml', $item='item', $attr='', $id='id', $encoding='utf-8') {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml   = "<{$root}{$attr}>";
        $xml   .= self::data_to_xml($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }
    /**
     * 回复消息
     */
    public function reply($msg){
        $xmldata = $this->xml_encode($msg);
        echo $xmldata;
    }
    public function setFromUsername($fromUsername){
        $this->postStr['FromUserName'] = $fromUsername;
    }
    public function getFromUsername(){
    	return $this->postStr['FromUserName'];
    }
    public function setToUsername($toUsername){
        $this->postStr['ToUserName'] = $toUsername;    
    }
    public function getToUsername(){
    	return $this->postStr['ToUserName'];
    }
    public function setCreateTime(){
        $this->postStr['CreateTime'] = time();
    }
    public function getCreateTime(){
    	return $this->postStr['CreateTime'];
    }
    public function setMsgType($msgType){
        $this->postStr['MsgType'] = $msgType;
    }
    public function getMsgType(){
    	return $this->postStr['MsgType'];
    }
    public function setFuncFlag($funcFlag = 0){
        $this->postStr['FuncFlag'] = $funcFlag;
    }
    /**
    * 设置回复图文
    * @param array $newsData 
    * 数组结构:
    *  array(
    *   [0]=>array(
    *       'Title'=>'msg title',
    *       'Description'=>'summary text',
    *       'PicUrl'=>'http://www.domain.com/1.jpg',
    *       'Url'=>'http://www.domain.com/1.html'
    *   ),
    *   [1]=>....
    *  )
    */
    public function sendNews($newsData=array())
    {

        $count = count($newsData);

        $msg = array(
            'ToUserName' => $this->postStr['FromUserName'],
            'FromUserName'=>$this->postStr['ToUserName'],
            'MsgType'=>'news',
            'CreateTime'=>time(),
            'ArticleCount'=>$count,
            'Articles'=>$newsData,
            'FuncFlag'=>0
        );
        $this->reply($msg);
    }
	/**
	 * 被动发送内容
	 * @param  [type] $fromUsername [description]
	 * @param  [type] $toUsername   [description]
	 * @param  [type] $msgType      [description]
	 * @param  [type] $content      [description]
	 * @return [type]               [description]
	 */
	public function sendText($content)
	{
        $msg = array(
            'ToUserName' => $this->getFromUsername(),
            'FromUserName'=>$this->getToUsername(),
            'MsgType'=>'text',
            'CreateTime'=>time(),
            'Content'=>$content,
            'FuncFlag'=>0
        );

        $this->reply($msg);
	}

	/**
	 * 解析数据
	 * @return [type] [description]
	 */
	public function parseData(){
		//$return = array();
		$this->postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($this->postStr)){
			$this->postStr = simplexml_load_string($this->postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->postStr = json_encode($this->postStr);
			$this->postStr = json_decode($this->postStr,1);
		}else {
			return $this->postStr = array();
		}
	}

	/**
	 * 模拟登录获取cookie
	 * @return [type] [description]
	 */
	public function login($locate="file"){
		$snoopy = new Snoopy; 
		$submit = "http://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN";
		$post["username"] = ACCOUNT;
		$post["pwd"] = md5(PASSWORD);
		$post["f"] = "json";
		$snoopy->submit($submit,$post);
		$cookie = '';
		foreach ($snoopy->headers as $key => $value) {
			$value = trim($value);
			if(strpos($value,'Set-Cookie: ') || strpos($value,'Set-Cookie: ')===0){
				$tmp = str_replace("Set-Cookie: ","",$value);
				$tmp = str_replace("Path=/","",$tmp);
				$cookie.=$tmp;
			}
		}
		if($locate == 'file'){
			$this->write("cookie.log",$cookie);
		}
		return $cookie;
	}

	public function redisCookie(){
		$redis = new Redis();
		$redis->connect('127.0.0.1', 6379);
		if ($redis->exists('cookie')) {
			return $redis->get('cookie');
		}else{
			$cookie = $this->login();
			$redis->setex('cookie', 600, $cookie);
			return $cookie;
		}
	}


	/**
	 * 把内容写入文件
	 * @param  string $filename 文件名
	 * @param  string $content  文件内容
	 * @return [type]           [description]
	 */
	public function write($filename,$content){
		/**
		$fp= fopen("./data/".$filename,"w");
		fwrite($fp,$content);
		fclose($fp);
		*/
	}

	/**
	 * 读取文件内容
	 * @param  string $filename 文件名
	 * @return [type]           [description]
	 */
	public function read($filename){
		/**
		if(file_exists("./data/".$filename)){
			$data = '';
			$handle=fopen("./data/".$filename,'r');
			while (!feof($handle)){
				$data.=fgets($handle);
			}
			fclose($handle);
			if($data){
				$send_snoopy = new Snoopy; 
				$send_snoopy->rawheaders['Cookie']= $data;
				$submit = "http://mp.weixin.qq.com/cgi-bin/getcontactinfo?t=ajax-getcontactinfo&lang=zh_CN&fakeid=";
				$send_snoopy->submit($submit,array());
				$result = json_decode($send_snoopy->results,1);
				if(!$result){
					return $this->login();
				}else{
					return $data;
				}
			}else{
				return $this->login();
			}
		}else{
			return $this->login();
		}
		*/
	}

	/**
	 * 验证cookie的有效性
	 * @return [type] [description]
	 */
	public function checkValid()
	{
		$send_snoopy = new Snoopy; 
		$post = array();
		$submit = "http://mp.weixin.qq.com/cgi-bin/getregions?id=1017&t=ajax-getregions&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		if(json_decode($result,1)){
			return true;
		}else{
			return false;
		}
	}

}
