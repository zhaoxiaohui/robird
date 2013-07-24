<?php

/**
 * @author zh
 * 信息解析类 主要负责解析发送过来的消息
 * 
 */
class Parse {
	
    function Parse() {
    }
    
    /*================================*\
     * function: parseContent
     * purpose: 解析内容
     * input: 
     * output:json string
    \*================================*/
    function parseContent($content){
    	if(preg_match("/^(c|C)+(h|H)+\d+$/", $content)){//change room ch0
    		if(strlen($content)>5)return ERROR_STRING_LONG;
    		return json_encode(array("type"=>CHRM, "con"=>substr($content,2)));
    	}else if(preg_match("/^(c|C)+(r|R)+$/", $content)){//create room cr
    		if(strlen($content)>5)return ERROR_STRING_LONG;
    		return json_encode(array("type"=>CRRM, "con"=>substr($content,2)));
    	}else if(preg_match("/^(c|C)+(k|K)+\s+\w{1,}/", $content)){//check room ck username
    		if(strlen($content)>5)return ERROR_STRING_LONG;
    		return json_encode(array("type"=>CKRM, "con"=>substr($content,2)));
    	}else if(preg_match("/^(c|C)+(k|K)+$/", $content)){  //check all room
    		return json_encode(array("type"=>CKRMA));
    	}else if(substr($content, 0, 6) == '点歌'){
    		return json_encode(array('type'=>SEARCH_MUSIC, 'con'=>ltrim(substr($content, 6, strlen($content)))));
    	}else if(substr($content, 0, 6) == '图片'){
    		return json_encode(array('type'=>SEARCH_PIC, 'con'=>ltrim(substr($content, 6, strlen($content)))));
    	}else if(substr($content, 0, 3) == '草'){
    		return json_encode(array('type'=>SEARCH_PICS, 'con'=>ltrim(substr($content, 6, strlen($content)))));
    	}else return json_encode(array("type"=>SDM, "con"=>$content));
    }
    
}
?>