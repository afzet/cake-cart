<?php

/****************************************************************************************
* LiveZilla functions.tracking.inc.php // VERSION 3.1.8.4
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
	
function processActions()
{
	global $BROWSER,$CONFIG;
	$actionData = "";
	$BROWSER->GetActions();
	if($BROWSER->Request != null && $BROWSER->Request->Status == REQUEST_STATUS_ACTIVE)
	{
		$requestUser = new Operator($BROWSER->Request->SenderSessId,$BROWSER->Request->SenderUserId);
		$requestUser->Load();
		
		if(($requestUser->LastActive < (time()-$CONFIG["timeout_clients"])) || $requestUser->Status == USER_STATUS_OFFLINE)
		{
			$BROWSER->Request->Destroy();
			$actionData .= "lz_tracking_close_request();";
		}
		
		if(isset($_GET[GET_TRACK_REQUEST_DECLINED]) || isset($_GET[GET_TRACK_REQUEST_ACCEPTED]))
		{
			if(isset($_GET[GET_TRACK_REQUEST_DECLINED]))
				$BROWSER->Request->Decline();
			if(isset($_GET[GET_TRACK_REQUEST_ACCEPTED]))
				$BROWSER->Request->Accept();
			$actionData .= "lz_tracking_close_request();";
		}
	}
	else if($BROWSER->Request != null && $BROWSER->Request->Status != REQUEST_STATUS_ACTIVE)
		$actionData .= "lz_tracking_close_request();";
	
	if($BROWSER->Request != null && $BROWSER->Request->Status == REQUEST_STATUS_ACTIVE && !isset($_GET[GET_TRACK_REQUEST_ACTIVE]))
	{
		$height = (!@file_exists(FILE_INVITATIONLOGO)) ? 220 : 302;
		$actionData .= $BROWSER->Request->GetCommand(getInvitationTemplate($BROWSER->Request->Id,$BROWSER->Request->SenderUserId,$BROWSER->UserId,$BROWSER->Request->SenderFullname,$BROWSER->Request->SenderGroupId),htmlentities($BROWSER->Request->Text,ENT_QUOTES,"UTF-8"),302,$height);
	}
	else if($BROWSER->Request != null && $BROWSER->Request->Status == REQUEST_STATUS_ACCEPTED)
	{
		// hold
	}
	if($BROWSER->Guide != null)
	{
		$actionData .= $BROWSER->Guide->GetCommand();
		$BROWSER->Guide->Destroy();
	}
	if($BROWSER->Alerts != null)
	{
		foreach($BROWSER->Alerts as $alert)
		{
			$actionData .= $alert->GetCommand();
			$alert->Destroy();
		}
		$actionData = str_replace("<!--server-->",LIVEZILLA_URL,$actionData);
	}
	return $actionData;
}

function getInvitationTemplate($_requestid,$_internid,$_sessid,$_name,$_groupid)
{
	global $CONFIG;
	$template = (!@file_exists(FILE_INVITATIONLOGO)) ? getFile(TEMPLATE_SCRIPT_INVITATION) : getFile(TEMPLATE_SCRIPT_INVITATION_LOGO);;
	$template = str_replace("<!--request_id-->",$_requestid,$template);
	$template = str_replace("<!--site_name-->",$CONFIG["gl_site_name"],$template);
	$template = str_replace("<!--sess_id-->",$_sessid,$template);
	$template = str_replace("<!--intern_name-->",$_name,$template);
	$template = str_replace("<!--group_id-->",base64UrlEncode($_groupid),$template);
	$template = str_replace("<!--intern_id-->",base64UrlEncode($_internid),$template);
	$template = str_replace("<!--width-->",$CONFIG["wcl_window_width"],$template);
	$template = str_replace("<!--height-->",$CONFIG["wcl_window_height"],$template);
	$template = str_replace("<!--server-->",LIVEZILLA_URL,$template);
	$template = str_replace("<!--intern_image-->",(file_exists(PATH_INTERN_IMAGES.md5($_internid).FILE_EXTENSION_PROFILE_PICTURE)) ? md5($_internid) . FILE_EXTENSION_PROFILE_PICTURE ."?acid=" . uniqid(rand()) : "nopic" . FILE_EXTENSION_PROFILE_PICTURE,$template);
	return doReplacements($template);
}
?>