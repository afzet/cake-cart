<?php

/****************************************************************************************
* LiveZilla extern.php // VERSION 3.1.8.4
* 
* Copyright 2010 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors. It is strongly 
* recommended to desist from editing this file.
* 
***************************************************************************************/ 

if(!defined("IN_LIVEZILLA"))
	die();
	
require(LIVEZILLA_PATH . "_lib/objects.external.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.external.inc.php");

if(isset($_POST[POST_EXTERN_SERVER_ACTION]))
{
	languageSelect();
	getData(false,true,false,true);
	$externalUser = new UserExternal($_POST[POST_EXTERN_USER_USERID]);
	$externalUser->ExternalStatic = new ExternalStatic($externalUser->UserId);
	array_push($externalUser->Browsers,new ExternalChat($externalUser->UserId,$_POST[POST_EXTERN_USER_BROWSERID]));
	
	define("IS_FILTERED",$FILTERS->Match(getIP(),formLanguages(((getServerParam("HTTP_ACCEPT_LANGUAGE") != null) ? getServerParam("HTTP_ACCEPT_LANGUAGE") : "")),$_POST[POST_EXTERN_USER_USERID]));
	define("IS_FLOOD",(!dataSetExists($externalUser->Browsers[0]->SessionFile) && isFlood()));

	if(dataSetExists($externalUser->Browsers[0]->SessionFile))
		$externalUser->Browsers[0]->Load();
		
	$externalUser->ExternalStatic->Language = (getServerParam("HTTP_ACCEPT_LANGUAGE") != null) ? getServerParam("HTTP_ACCEPT_LANGUAGE") : "";
	$externalUser->Browsers[0]->LoadChat($CONFIG,null);
	
	if($_POST[POST_EXTERN_SERVER_ACTION] == EXTERN_ACTION_LISTEN)
		$externalUser = listen($externalUser);
	else if($_POST[POST_EXTERN_SERVER_ACTION] == EXTERN_ACTION_MAIL)
	{
		getData(false,true,false,false);
		if($externalUser->SaveTicket(AJAXDecode($_POST[POST_EXTERN_USER_GROUP])) && ($CONFIG["gl_scom"] != null || $CONFIG["gl_sgom"] != null))
			$externalUser->SendCopyOfMail(AJAXDecode($_POST[POST_EXTERN_USER_GROUP]),$CONFIG,$GROUPS);
	}
	else if($_POST[POST_EXTERN_SERVER_ACTION] == EXTERN_ACTION_RATE)
	{
		getData(true,false,false,false);
		$externalUser->SaveRate(AJAXDecode($_POST[POST_EXTERN_REQUESTED_INTERNID]));
	}
	else
	{
		if($externalUser->Browsers[0]->Chat != null)
		{
			$externalUser->Browsers[0]->DestroyChatFiles();
			$externalUser->Browsers[0]->Chat->ExternalDestroy();
		}
		unregisterChat(@$_POST[POST_EXTERN_CHAT_ID]);
		$externalUser->Browsers[0]->Waiting = false;
		$externalUser->Browsers[0]->WaitingMessageDisplayed = null;
		
		if($_POST[POST_EXTERN_SERVER_ACTION] == EXTERN_ACTION_RELOAD_GROUPS)
		{
			if(isset($_GET[GET_EXTERN_USER_NAME]) && !isnull($_GET[GET_EXTERN_USER_NAME]))
				$externalUser->Browsers[0]->Fullname = base64UrlDecode($_GET[GET_EXTERN_USER_NAME]);
		
			if(isset($_GET[GET_EXTERN_USER_EMAIL]) && !isnull($_GET[GET_EXTERN_USER_EMAIL]))
				$externalUser->Browsers[0]->Email = base64UrlDecode($_GET[GET_EXTERN_USER_EMAIL]);
			
			if(isset($_GET[GET_EXTERN_USER_COMPANY]) && !isnull($_GET[GET_EXTERN_USER_COMPANY]))
				$externalUser->Browsers[0]->Company = base64UrlDecode($_GET[GET_EXTERN_USER_COMPANY]);
			
			$externalUser = reloadGroups($externalUser);
		}
		else
		{
			$externalUser->Browsers[0]->Destroy();
			exit();
		}
	}

	if(!dataSetExists($externalUser->ExternalStatic->SessionFile) && isset($_POST[POST_EXTERN_RESOLUTION_WIDTH]))
		createStaticFile($externalUser,Array($_POST[POST_EXTERN_RESOLUTION_WIDTH],$_POST[POST_EXTERN_RESOLUTION_HEIGHT]),$_POST[POST_EXTERN_COLOR_DEPTH],$_POST[POST_EXTERN_TIMEZONE_OFFSET],@$_POST[GEO_LATITUDE],@$_POST[GEO_LONGITUDE],@$_POST[GEO_COUNTRY_ISO_2],@$_POST[GEO_CITY],@$_POST[GEO_REGION],@$_POST[GEO_TIMEZONE],@$_POST[GEO_ISP],@$_POST[GEO_SSPAN],@$_POST[GEO_RESULT_ID]);

	if(isset($_GET[GET_TRACK_SPECIAL_AREA_CODE]))
		$externalUser->Browsers[0]->Code = base64UrlDecode($_GET[GET_TRACK_SPECIAL_AREA_CODE]);
	
	if(IS_FILTERED)
		$externalUser->Browsers[0]->Destroy();
	else
		$externalUser->Browsers[0]->Save();
	$EXTERNSCRIPT = $externalUser->Response;
}
?>
