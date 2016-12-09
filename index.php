<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	//header('Content-type: text/xml');
	include_once('ressources/Sax4PHP.php');
	include_once("sax.php");
	$xml = file_get_contents('./ressources/mondial.xml');
	$sax = new SaxParser(new sax());
	try {
		$sax->parse($xml);
	}catch(SAXException $e){  
		echo "\n",$e;
	}catch(Exception $e) {
		echo "Default exception >>", $e;
	}
?>