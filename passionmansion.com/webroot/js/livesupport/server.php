<?php

/****************************************************************************************
* LiveZilla server.php // VERSION 3.1.8.4
* 
* Copyright 2010 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors. It is strongly 
* recommended to desist from editing this file.
* 
***************************************************************************************/ 

define("ACCESSTIME",microtime());
define("ACCESSID",md5(microtime()));
define("IN_LIVEZILLA",true);
define("SAFE_MODE",@ini_get('safe_mode'));
define("LIVEZILLA_PATH","./");
@error_reporting(E_ALL);

require(LIVEZILLA_PATH . "_definitions/definitions.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.files.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.protocol.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.global.inc.php");
require(LIVEZILLA_PATH . "_lib/objects.global.users.inc.php");

define("LIVEZILLA_URL",getScheme() . $_SERVER["HTTP_HOST"] . str_replace(FILE_SERVER_FILE,"",$_SERVER["PHP_SELF"]));
header("Connection: close");
processHeaderValues();

$RESPONSE = new Response();
if(!isset($_POST[POST_INTERN_ADMINISTRATE]) && isset($_POST[POST_SERVER_REQUEST_TYPE]) && $_POST[POST_SERVER_REQUEST_TYPE]==CALLER_TYPE_INTERNAL)
{
	header("Content-Type: text/xml; charset=UTF-8");
	if(getIdle())
	{
		$RESPONSE->SetValidationError(LOGIN_REPLY_IDLE);
		exit($RESPONSE->GetXML());
	}
	if(!getAvailability() && $_POST[POST_INTERN_SERVER_ACTION]==INTERN_ACTION_LOGIN && !isset($_POST[POST_INTERN_ACCESSTEST]))
	{
		$RESPONSE->SetValidationError(LOGIN_REPLY_DEACTIVATED);
		exit($RESPONSE->GetXML());
	}
}

require(LIVEZILLA_PATH . "_config/config.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.dynamic.inc.php");
setDataProvider();
@set_time_limit($CONFIG["timeout_clients"]);
@ini_set('session.use_cookies', '0');
@set_error_handler("handleError");

if (function_exists('date_default_timezone_set'))
   date_default_timezone_set(@date_default_timezone_get());
  
if(isset($_POST[POST_SERVER_REQUEST_TYPE]) || isset($_GET[GET_SERVER_REQUEST_TYPE]))
{
	if(DB_CONNECTION && ((isset($_POST[POST_SERVER_REQUEST_TYPE]) && $_POST[POST_SERVER_REQUEST_TYPE]==CALLER_TYPE_TRACK) || (isset($_GET[GET_SERVER_REQUEST_TYPE]) && $_GET[GET_SERVER_REQUEST_TYPE] == CALLER_TYPE_TRACK)))
	{
		define("CALLER_TYPE",CALLER_TYPE_TRACK);
		header("Cache-Control: no-cache, must-revalidate");
		require(LIVEZILLA_PATH . "track.php");
		$response = @$TRACKINGSCRIPT;
	}
	else if(DB_CONNECTION && isset($_POST[POST_SERVER_REQUEST_TYPE]) && $_POST[POST_SERVER_REQUEST_TYPE]==CALLER_TYPE_EXTERNAL)
	{
		define("CALLER_TYPE",CALLER_TYPE_EXTERNAL);
		header("Content-Type: text/xml; charset=UTF-8");
		require(LIVEZILLA_PATH . "extern.php");
		$response = utf8_encode("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><livezilla_js>" . base64_encode(((isset($EXTERNSCRIPT)) ? $EXTERNSCRIPT : "")) . "</livezilla_js>");
	}
	else if(isset($_POST[POST_SERVER_REQUEST_TYPE]) && $_POST[POST_SERVER_REQUEST_TYPE]==CALLER_TYPE_INTERNAL)
	{
		define("CALLER_TYPE",CALLER_TYPE_INTERNAL);
		header("Cache-Control: no-cache, must-revalidate");
		header("Content-Type: text/xml; charset=UTF-8");
		require(LIVEZILLA_PATH . "intern.php");
		$response = utf8_encode($response);
	}
}
if(!isset($response))
{
	header("Location: http://www.livezilla.net");
	exit(getFile(TEMPLATE_HTML_SUPPORT));
}
if(DB_CONNECTION)
	runPeriodicJobs($CONFIG);
unloadDataProvider();
exit($response);
?>