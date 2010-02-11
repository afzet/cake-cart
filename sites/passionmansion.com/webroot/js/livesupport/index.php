<?php

/****************************************************************************************
* LiveZilla index.php // VERSION 3.1.8.4
* 
* Copyright 2010 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors. It is strongly 
* recommended to desist from editing this file.
* 
***************************************************************************************/ 

define("ACCESSID",md5(microtime()));
define("IN_LIVEZILLA",true);
if(!defined("LIVEZILLA_PATH"))
	define("LIVEZILLA_PATH","./");
header("Content-Type: text/html; charset=UTF-8");

require(LIVEZILLA_PATH . "_config/config.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.files.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.protocol.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.dynamic.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.index.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.global.inc.php");

languageSelect();
@set_error_handler("handleError");
if (function_exists('date_default_timezone_set'))
   date_default_timezone_set(@date_default_timezone_get());
   
$scheme = getScheme();
if(isset($_GET[GET_INDEX_SERVER_ACTION]) && $_GET[GET_INDEX_SERVER_ACTION] == "addserver")
{
	$html = doReplacements(getFile(TEMPLATE_HTML_ADD_SERVER));
	$html = str_replace("<!--lz_add_url-->",getServerAddLink($scheme),$html);
	exit($html);
}
else
{
	$html = getFile(TEMPLATE_HTML_INDEX);
	$errorbox = null;
	$errors['write'] = getFolderPermissions();
	$errors['php_version'] = getPhpVersion();
	if(!isnull($errors['write']) || !isnull($errors['php_version']))
	{
		$errorbox = getFile(TEMPLATE_HTML_INDEX_ERRORS);
		$errorbox = str_replace("<!--write_access-->",$errors['write'],$errorbox);
		if(strlen($errors['write']) > 0 && !isnull($errors['php_version']))
			$errors['php_version'] = "<br><br>" . $errors['php_version'];
		$errorbox = str_replace("<!--php_version-->",$errors['php_version'],$errorbox);
	}
	$html = str_replace("<!--index_errors-->",$errorbox,$html);
	$html = str_replace("<!--height-->",$CONFIG["wcl_window_height"],$html);
	$html = str_replace("<!--width-->",$CONFIG["wcl_window_width"],$html);
	$html = str_replace("<!--lz_add_server-->",$scheme . getServerParam("HTTP_HOST") . getServerParam("PHP_SELF") . "?" . GET_INDEX_SERVER_ACTION ."=addserver",$html);
	$html = str_replace("<!--lz_version-->",VERSION,$html);
	echo(doReplacements($html));
}
?>