<?php
header("Content-Type:text/html;Charset=utf-8");
//include class file
include("GoogleImages.class.php");

//create class instance
$gimage = new GoogleImages();

/*****************************
call get_images method by providing 3 parameters
1.) query - what should be searched for
2.) cols - number of images per row
3.) rows - number of rows
*****************************/

$key = $_GET['key'];
if($key){
    $gimage->get_images($key, 4, 5);
}
else{
	echo "hello world";
	$send_snoopy = new Snoopy;
	$send_snoopy->proxy_host = "127.0.0.1";
	$send_snoopy->proxy_port = "8087";
	
	$url = "http://cl.cn.mu/index.php";
	
	$send_snoopy->fetch($url);
	//$web_page = file_get_contents( str_replace("##query##",urlencode($k), $url ));
	
 	$web_page = $send_snoopy->results;
 	print_r($web_page);
}

?>
