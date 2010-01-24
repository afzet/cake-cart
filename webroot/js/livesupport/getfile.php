<?php

/****************************************************************************************
* LiveZilla getfile.php // VERSION 3.1.8.4
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
	
require(LIVEZILLA_PATH . "_definitions/definitions.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.files.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.global.inc.php");
require(LIVEZILLA_PATH . "_lib/objects.global.users.inc.php");
require(LIVEZILLA_PATH . "_config/config.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.dynamic.inc.php");

if(isset($_GET["id"]) && setDataProvider())
{
	$id = $_GET["id"];
	if(strpos($id,".") === false && !isnull($res = getResource(secPrev($id))))
	{
		if(file_exists("./uploads/" . $res["value"]))
		{
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Length: ' . filesize("./uploads/" . $res["value"]));
			header('Content-Disposition: attachment; filename=' . urlencode($res["title"]));
			readfile("./uploads/" . $res["value"]);
		}
		else
			header("HTTP/1.0 404 Not Found");
	}
	else
		header("HTTP/1.0 404 Not Found");
}
else
	header("HTTP/1.0 404 Not Found");
?>