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
    	if(preg_match("/(c|C)+(h|H)+\d+$/", $content)){//change room ch0
    		if(strlen($content)>5)return ERROR_STRING_LONG;
    		return json_encode(array("type"=>CHRM, "con"=>substr($content,2)));
    	}else if(preg_match("/(c|C)+(r|R)+\d+$/", $content)){//create room cr0
    		if(strlen($content)>5)return ERROR_STRING_LONG;
    		return json_encode(array("type"=>CRRM, "con"=>substr($content,2)));
    	}else if(preg_match("/(c|C)+(r|R)+\d+$/", $content)){//check room ck0
    		if(strlen($content)>5)return ERROR_STRING_LONG;
    		return json_encode(array("type"=>CKRM, "con"=>substr($content,2)));
    	}else return json_encode(array("type"=>SDM, "con"=>$content));
    }
    
}
?>