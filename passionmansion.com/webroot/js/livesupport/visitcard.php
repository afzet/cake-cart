<?php

/****************************************************************************************
* LiveZilla visitcard.php // VERSION 3.1.8.4
* 
* Copyright 2010 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors. It is strongly 
* recommended to desist from editing this file.
* 
***************************************************************************************/ 

define("IN_LIVEZILLA",true);
if(!defined("LIVEZILLA_PATH"))
	define("LIVEZILLA_PATH","./");
require(LIVEZILLA_PATH . "_definitions/definitions.protocol.inc.php");

if(isset($_GET[GET_EXTERN_VISITCARD_ID]) && strpos($_GET[GET_EXTERN_VISITCARD_ID],"..") === false)
{
	$file = "./vcards/" . utf8_decode($_GET[GET_EXTERN_VISITCARD_ID]) . ".vcf";
	if(file_exists($file))
	{
		header("Content-Type: application/vcard;");
		header("Content-Disposition: attachment; filename=" . utf8_decode($_GET[GET_EXTERN_VISITCARD_ID]) . ".vcf");
		exit(readfile($file));
	}
}
header("HTTP/1.0 404 Not Found");
?>
