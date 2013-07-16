<?php
include "Snoopy.class.php";
include "Robird.php";
include "Simi.class.php";

$rbird = new Robird();
if($rbird->checkSignature()){
	$recieve = $rbird->parseData();
	
	/*$name = "firebird";
	$sim = new Simsimi(array('sid'=>$name,'datapath'=>'sim_'));
	$ready = $sim->init();*/
	
	//$rbird->sendText($recieve['FromUserName'],$recieve['ToUserName'],"text","y");
	
	//$reply = $sim->talk($recieve['Content']);
	$simi = new Simi();
	
	$reply = $simi->request($recieve['Content']);
	if($reply['result'] == 100 )
		$reply = $reply['response'];
	else $reply = "小火鸟";
	$rbird->sendText($recieve['FromUserName'],$recieve['ToUserName'],"text",$reply);
}
?>
