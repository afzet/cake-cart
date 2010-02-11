<?php

/****************************************************************************************
* LiveZilla functions.external.inc.php // VERSION 3.1.8.4
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

function listen($_user)
{
	global $CONFIG,$GROUPS,$INTERNAL,$USER,$INTLIST,$INTBUSY;
	$USER = $_user;
	if(!IS_FILTERED)
	{
		if(isnull($USER->Browsers[0]->Chat))
		{
			getData(true,false,false,false);
			$USER->Browsers[0]->SetCookieGroup();
			
			if(isset($_POST[POST_EXTERN_USER_GROUP]))
			{
				$USER->Browsers[0]->DesiredChatGroup = utf8_decode($_POST[POST_EXTERN_USER_GROUP]);
				$USER->Browsers[0]->SetCookieGroup();
			}
			
			getInternal();
			$chatId = getChatId($USER,$USER->Browsers[0]->DesiredChatGroup);
			$chatPosition = getQueuePosition($chatId,$USER->Browsers[0]->DesiredChatGroup);
			$chatWaitingTime = getQueueWaitingTime($chatPosition,$INTBUSY);
			login();
			$USER->Browsers[0]->Waiting = !($chatPosition == 1 && count($INTLIST) > 0 && !(!isnull($USER->Browsers[0]->DesiredChatPartner) && $INTERNAL[$USER->Browsers[0]->DesiredChatPartner]->Status != USER_STATUS_ONLINE));

			if(!$USER->Browsers[0]->Waiting)
			{
				$USER->AddFunctionCall("lz_chat_show_connected();",false);
				$USER->Browsers[0]->DestroyChatFiles();
				$USER->AddFunctionCall("lz_chat_set_status(lz_chat_data.STATUS_ALLOCATED);",false);
				if($CONFIG["gl_alloc_mode"] != ALLOCATION_MODE_ALL || !isnull($USER->Browsers[0]->DesiredChatPartner))
				{
					$USER->Browsers[0]->CreateChat($INTERNAL[$USER->Browsers[0]->DesiredChatPartner],$chatId);
				}
				else
				{
					foreach($INTLIST as $intid => $am)
						$USER->Browsers[0]->CreateChat($INTERNAL[$intid],$chatId);
				}
			}
			else
				$USER->AddFunctionCall("lz_chat_show_queue_position(".$chatPosition.",".$chatWaitingTime.");",false);
				
			closeOpenChatLog($USER->Browsers[0]->DesiredChatPartner,$USER->Browsers[0]->SystemId);
		}
		else
		{
			activeListen();
		}
	}
	else
		displayFiltered();
	return $USER;
}

function activeListen($runs=1,$picUpdate=false,$isPost=false)
{
	global $CONFIG,$GROUPS,$INTERNAL,$USER,$ISFORWARD;
	$start = time();
	$USER->Browsers[0]->Typing = isset($_POST[POST_EXTERN_TYPING]);

	while($runs == 1)
	{
		isForward();
		processForward();

		if($runs > 1)
		{
			getDataSets();
			getData(false,false,false,true);
			$USER->Browsers[0]->LoadChat($CONFIG,null);
			if(isnull($USER->Browsers[0]->Chat))
				break;
		}
		if($USER->Browsers[0]->Chat->Closed)
		{
			displayQuit();
			return $USER;
		}
		else if($USER->Browsers[0]->Chat->Declined)
		{
			displayDeclined();
			return $USER;
		}
		else if($USER->Browsers[0]->Chat->Activated == CHAT_STATUS_WAITING && !$ISFORWARD)
		{
			beginnConversation();
		}
		if($USER->Browsers[0]->Chat->Activated >= CHAT_STATUS_WAITING && !$ISFORWARD)
		{
			$picUpdate = refreshPicture();
			processTyping();
		}

		if($runs == 1 && isset($_POST[POST_EXTERN_USER_FILE_UPLOAD_NAME]) && !isset($_POST[POST_EXTERN_USER_FILE_UPLOAD_ERROR]) && !$ISFORWARD)
			$USER = $USER->Browsers[0]->RequestFileUpload($USER,$_POST[POST_EXTERN_USER_FILE_UPLOAD_NAME]);
		else if($runs == 1 && isset($_POST[POST_EXTERN_USER_FILE_UPLOAD_NAME]) && isset($_POST[POST_EXTERN_USER_FILE_UPLOAD_ERROR]))
			$USER->Browsers[0]->AbortFileUpload($USER,namebase($_POST[POST_EXTERN_USER_FILE_UPLOAD_NAME]),$_POST[POST_EXTERN_USER_FILE_UPLOAD_ERROR]);
	
		if($runs++ == 1 && isset($_POST[POST_GLOBAL_SHOUT]))
			processPosts();
			
		if($USER->Browsers[0]->Chat->Activated == CHAT_STATUS_ACTIVE)
		{
			 $isPost = getNewPosts();
			 updateRoom($USER->Browsers[0]->Chat->Id,CHAT_STATUS_ACTIVE);
		}
		else
			updateRoom($USER->Browsers[0]->Chat->Id,CHAT_STATUS_WAITING);
			 
		if(isset($_POST[POST_GLOBAL_SHOUT]) || isset($_POST[POST_GLOBAL_NO_LONG_POLL]) || $isPost || $ISFORWARD)
		{
			break;
		}
		else if(md5($USER->Response) != $_POST[POST_GLOBAL_XMLCLIP_HASH_ALL] || $picUpdate)
		{
			$_POST[POST_GLOBAL_XMLCLIP_HASH_ALL] = md5($USER->Response);
			$USER->AddFunctionCall("lz_chat_listen_hash('". md5($USER->Response) . "','".getId(5)."');",false);
			break;
		}
		else
		{
			$USER->Response = "";
			$wait = max($CONFIG["poll_frequency_clients"]-1,1);
			if(time()+$wait < $start+getLongPollRuntime())
				sleep($wait);
			else
				break;
		}
	}
}

function isForward()
{
	global $USER,$CONFIG,$ISFORWARD,$ISFORWARDPROCESSED;
	$USER->Browsers[0]->GetForwards();
	$ISFORWARD = (!isnull($USER->Browsers[0]->Forward) && !$USER->Browsers[0]->Forward->Processed);
	$ISFORWARDPROCESSED = (!isnull($USER->Browsers[0]->Forward) && $USER->Browsers[0]->Forward->Processed);
}

function processForward()
{
	global $USER,$CONFIG,$ISFORWARD;
	if($ISFORWARD && !isnull($USER->Browsers[0]->Forward->TargetGroupId))
	{
		$USER->AddFunctionCall("lz_chat_initiate_forwarding('".base64_encode($USER->Browsers[0]->Forward->TargetGroupId)."');",false);

		$USER->Browsers[0]->Chat->ExternalDestroy();
		$USER->Browsers[0]->Chat->InternalDestroy();
		$USER->Browsers[0]->DestroyChatFiles();
		$USER->Browsers[0]->DesiredChatGroup = $USER->Browsers[0]->Forward->TargetGroupId;
		$USER->Browsers[0]->DesiredChatPartner = $USER->Browsers[0]->Forward->TargetSessId;
		$USER->Browsers[0]->SetCookieGroup();
		$USER->Browsers[0]->Forward->Processed = true;
		$USER->Browsers[0]->Forward->Save();
	}
	else
	{
		if(!isnull($USER->Browsers[0]->Forward) && !isnull($USER->Browsers[0]->Chat) && $USER->Browsers[0]->Chat->Activated == CHAT_STATUS_ACTIVE)
			if($USER->Browsers[0]->Forward->SenderSessId != $USER->Browsers[0]->DesiredChatPartner)
				$USER->Browsers[0]->Forward->Destroy();
	}
}

function getNewPosts()
{
	global $USER;
	$isPost = false;
	foreach($USER->Browsers[0]->GetPosts() as $post)
		if($USER->Browsers[0]->DesiredChatPartner == $post->Sender)
		{
			$USER->AddFunctionCall($post->GetCommand(),false);
			$isPost = true;
		}
		
	return $isPost;
}

function processPosts($counter=0)
{
	global $USER;
	while(isset($_POST["p_p" . $counter]))
	{
		$id = md5($USER->Browsers[0]->SystemId . $_POST[POST_EXTERN_CHAT_ID] . $_POST["p_i" . $counter]);
		$post = new Post($id,$USER->Browsers[0]->SystemId,$USER->Browsers[0]->Chat->InternalUser->SystemId,AJAXDecode($_POST["p_p" . $counter]),time());
		$post->Save();
		$USER->AddFunctionCall("lz_chat_release_post('".$_POST["p_i" . $counter]."');",false);
		$counter++;
	}
	$counter=0;
	while(isset($_POST["pr_i" . $counter]))
	{
		markPostReceived($_POST["pr_i" . $counter]);
		$USER->AddFunctionCall("lz_chat_message_set_received('".$_POST["pr_i" . $counter]."');",false);
		$counter++;
	}
}

function login()
{
	global $INTERNAL,$USER;
	
	if(isnull($_POST[POST_EXTERN_USER_NAME]) && !isnull(getCookieValue("login_name")))
		$USER->Browsers[0]->Fullname = getCookieValue("login_name");
	else
		$USER->Browsers[0]->Fullname = AJAXDecode($_POST[POST_EXTERN_USER_NAME]);

	if(isnull($_POST[POST_EXTERN_USER_EMAIL]) && !isnull(getCookieValue("login_email")))
		$USER->Browsers[0]->Email = getCookieValue("login_email");
	else
		$USER->Browsers[0]->Email = AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]);
		
	if(isnull($_POST[POST_EXTERN_USER_COMPANY]) && !isnull(getCookieValue("login_company")))
		$USER->Browsers[0]->Company = getCookieValue("login_company");
	else
		$USER->Browsers[0]->Company = AJAXDecode($_POST[POST_EXTERN_USER_COMPANY]);

	if(isset($_POST[POST_EXTERN_USER_NAME]) && !isnull($_POST[POST_EXTERN_USER_NAME]))
		setCookieValue("login_name",$USER->Browsers[0]->Fullname);
	if(isset($_POST[POST_EXTERN_USER_EMAIL]) && !isnull($_POST[POST_EXTERN_USER_EMAIL]))
		setCookieValue("login_email",$USER->Browsers[0]->Email);
	if(isset($_POST[POST_EXTERN_USER_COMPANY]) && !isnull($_POST[POST_EXTERN_USER_COMPANY]))
		setCookieValue("login_company",$USER->Browsers[0]->Company);
	$USER->AddFunctionCall("lz_chat_set_status(lz_chat_data.STATUS_INIT);",false);
}

function replaceLoginDetails($_html)
{
	$data = (!isset($_GET[GET_EXTERN_USER_EMAIL])) ? getCookieValue("login_email") : base64UrlDecode($_GET[GET_EXTERN_USER_EMAIL]);
	$_html = str_replace("<!--login_value_email-->",htmlentities($data,ENT_QUOTES,"UTF-8"),$_html);
	$data = (!isset($_GET[GET_EXTERN_USER_NAME])) ? getCookieValue("login_name") : base64UrlDecode($_GET[GET_EXTERN_USER_NAME]);
	$_html = str_replace("<!--login_value_name-->",htmlentities($data,ENT_QUOTES,"UTF-8"),$_html);
	$data = (!isset($_GET[GET_EXTERN_USER_COMPANY])) ? getCookieValue("login_company") : base64UrlDecode($_GET[GET_EXTERN_USER_COMPANY]);
	$_html = str_replace("<!--login_value_company-->",htmlentities($data,ENT_QUOTES,"UTF-8"),$_html);
	return $_html;
}

function refreshPicture()
{
	global $CONFIG,$USER;
	$update = (@filemtime($USER->Browsers[0]->Chat->InternalUser->PictureFile) >  time()-$CONFIG["poll_frequency_clients"]*2);
	if(isset($USER->Browsers[0]->Chat->InternalUser->UserId) && (file_exists($USER->Browsers[0]->Chat->InternalUser->WebcamFile) || file_exists($USER->Browsers[0]->Chat->InternalUser->PictureFile)))
		$USER->AddFunctionCall("lz_chat_set_intern_image(true," . parseBool($update) . "," . parseBool(file_exists($USER->Browsers[0]->Chat->InternalUser->WebcamFile)) . ",false,'".$USER->Browsers[0]->Chat->InternalUser->WebcamFile."','".$USER->Browsers[0]->Chat->InternalUser->PictureFile."');",false);
	else
		$USER->AddFunctionCall("lz_chat_set_intern_image(false,false,false,false,'','');",false);
	$USER->AddFunctionCall("lz_chat_set_config(".$CONFIG["timeout_clients"].",".$CONFIG["poll_frequency_clients"].");",false);
	return (file_exists($USER->Browsers[0]->Chat->InternalUser->WebcamFile) || $update);
}

function processTyping()
{
	global $CONFIG,$USER,$GROUPS;
	$groupname = addslashes($GROUPS[$USER->Browsers[0]->DesiredChatGroup]["gr_desc"]);
	$USER->AddFunctionCall("lz_chat_set_intern(\"".base64_encode($USER->Browsers[0]->Chat->InternalUser->UserId)."\",\"".base64_encode(addslashes($USER->Browsers[0]->Chat->InternalUser->Fullname))."\",\"". base64_encode($groupname)."\",".parseBool($USER->Browsers[0]->DesiredChatPartnerTyping).",".parseBool(file_exists(PATH_VISITCARDS.$USER->Browsers[0]->Chat->InternalUser->UserId.".vcf")).");",false);
}

function beginnConversation()
{
	global $USER,$CONFIG;
	$USER->Browsers[0]->Chat->ExternalActivate();
	if(!isnull($CONFIG["gl_save_op"]))
		setCookieValue("internal_user",$USER->Browsers[0]->Chat->InternalUser->UserId);
	$USER->Browsers[0]->DesiredChatPartner = $USER->Browsers[0]->Chat->InternalUser->SystemId;
	
	$USER->AddFunctionCall("lz_chat_add_system_text(1,'".base64_encode($USER->Browsers[0]->Chat->InternalUser->Fullname)."');",false);
	$USER->AddFunctionCall("lz_chat_set_status(lz_chat_data.STATUS_ACTIVE);",false);
	$USER->AddFunctionCall("lz_chat_shout();",false);
}

function displayFiltered()
{
	global $FILTERS,$USER;
	$USER->Browsers[0]->DestroyChatFiles();
	$USER->AddFunctionCall("lz_chat_set_intern('','','',false,false);",false);
	$USER->AddFunctionCall("lz_chat_set_status(lz_chat_data.STATUS_STOPPED);",false);
	$USER->AddFunctionCall("lz_chat_add_system_text(2,'".base64_encode("&nbsp;<b>".$FILTERS->Filters[ACTIVE_FILTER_ID]->Reason."</b>")."');",false);
	$USER->AddFunctionCall("lz_chat_stop_system();",false);
	$USER->AddFunctionCall("lz_chat_set_intern_image(false,false,false,true,'','');",false);
}

function displayQuit()
{
	global $GROUPS,$USER;
	$USER->Browsers[0]->DestroyChatFiles();
	$USER->AddFunctionCall("lz_chat_set_intern('','','',false,false);",false);
	$USER->AddFunctionCall("lz_chat_set_status(lz_chat_data.STATUS_STOPPED);",false);
	$USER->AddFunctionCall("lz_chat_add_system_text(3,null);",false);
	$USER->AddFunctionCall("lz_chat_stop_system();",false);
}

function displayDeclined()
{
	global $GROUPS,$USER;
	$USER->Browsers[0]->DestroyChatFiles();
	$USER->AddFunctionCall("lz_chat_set_intern('','','',false,false);",false);
	$USER->AddFunctionCall("lz_chat_set_status(lz_chat_data.STATUS_STOPPED);",false);
	$USER->AddFunctionCall("lz_chat_add_system_text(4,null);",false);
	$USER->AddFunctionCall("lz_chat_stop_system();",false);
}

function buildLoginErrorField($error="",$addition = "")
{
	global $FILTERS,$LZLANG;
	if(!getAvailability())
		return $LZLANG["client_error_deactivated"];
		
	if(!DB_CONNECTION)
		return $LZLANG["client_error_unavailable"];

	if(IS_FILTERED)
	{
		$error = $LZLANG["client_error_unavailable"];
		if(isset($FILTERS->Message) && strlen($FILTERS->Message) > 0)
			$addition = "<br><br>" . $FILTERS->Message;
	}
	return $error . $addition;
}

function reloadGroups($_user)
{
	global $CONFIG,$INTERNAL,$GROUPS;
	getData(true,false,false,true);
	$groupbuilder = new GroupBuilder($INTERNAL,$GROUPS,$CONFIG);
	$groupbuilder->Generate();
	
	if(isset($_POST[POST_EXTERN_REQUESTED_INTERNID]) && !isnull($_POST[POST_EXTERN_REQUESTED_INTERNID]))
		$_user->Browsers[0]->DesiredChatPartner = getInternSessIdByUserId(AJAXDecode($_POST[POST_EXTERN_REQUESTED_INTERNID]));

	$_user->AddFunctionCall("top.lz_chat_set_groups(\"" . $groupbuilder->Result . "\" ,". $groupbuilder->ErrorHTML .");",true);
	$_user->AddFunctionCall("top.lz_chat_set_groups(\"" . $groupbuilder->Result . "\" ,". $groupbuilder->ErrorHTML .");",true);
	$_user->AddFunctionCall("lz_chat_release(".parseBool(($groupbuilder->GroupAvailable || (isset($_POST[GET_EXTERN_RESET]) && strlen($groupbuilder->ErrorHTML) <= 2))).",".$groupbuilder->ErrorHTML.");",false);
	return $_user;
}

function getInternal($desired = "",$util = 0,$fromCookie = null)
{
	global $CONFIG,$INTERNAL,$GROUPS,$USER,$ISFORWARDPROCESSED,$INTLIST,$INTBUSY;
	$INTLIST = array();
	$INTBUSY = 0;
	$backup_target = null;
	$fromDepartment = $fromDepartmentBusy = false;
	if(!isnull($USER->Browsers[0]->DesiredChatPartner) && isset($INTERNAL[$USER->Browsers[0]->DesiredChatPartner]) && $INTERNAL[$USER->Browsers[0]->DesiredChatPartner]->Status < USER_STATUS_OFFLINE)
		$desired = $USER->Browsers[0]->DesiredChatPartner;
	else
	{
		$USER->Browsers[0]->DesiredChatPartner = null;
		if(isset($_POST[POST_EXTERN_REQUESTED_INTERNID]) && !isnull($_POST[POST_EXTERN_REQUESTED_INTERNID]))
			$desired = getInternSessIdByUserId(AJAXDecode($_POST[POST_EXTERN_REQUESTED_INTERNID]));
		else if(!isnull(getCookieValue("internal_user")) && !isnull($CONFIG["gl_save_op"]))
		{
			$desired = getInternSessIdByUserId(getCookieValue("internal_user"));
			$fromCookie = $desired;
		}
	}
	foreach($GROUPS as $id => $group)
		$utilization[$id] = 0;
	foreach($INTERNAL as $sessId => $internal)
	{
		if($internal->LastActive > (time()-$CONFIG["timeout_clients"]))
		{
			$group_chats[$sessId] = $internal->GetExternalChatAmount();
			$group_names[$sessId] = $internal->Fullname;
			$group_available[$sessId] = GROUP_STATUS_UNAVAILABLE;

			if(in_array($USER->Browsers[0]->DesiredChatGroup,$internal->Groups))
			{
				$lca = $internal->GetLastChatAllocation();
				if($internal->Status == USER_STATUS_ONLINE && $lca < (time()-($CONFIG["poll_frequency_clients"]*3)))
					$group_available[$sessId] = GROUP_STATUS_AVAILABLE;
				elseif($internal->Status== USER_STATUS_BUSY || $lca >= (time()-($CONFIG["poll_frequency_clients"]*3)))
				{
					$group_available[$sessId] = GROUP_STATUS_BUSY;
					$INTBUSY++;
				}
			}
			else
			{
				if($internal->Status == USER_STATUS_ONLINE)
					$backup_target = $internal;
				else if($internal->Status == USER_STATUS_BUSY && isnull($backup_target))
					$backup_target = $internal;
					
				if(!isnull($USER->Browsers[0]->DesiredChatPartner) && $USER->Browsers[0]->DesiredChatPartner == $sessId)
					$USER->Browsers[0]->DesiredChatPartner = null;
			}
			for($count=0;$count<count($internal->Groups);$count++)
			{
				if($USER->Browsers[0]->DesiredChatGroup == $internal->Groups[$count])
				{
					if(!is_array($utilization[$internal->Groups[$count]]))
						$utilization[$internal->Groups[$count]] = Array();
					if($group_available[$sessId] == GROUP_STATUS_AVAILABLE)
						$utilization[$internal->Groups[$count]][$sessId] = $group_chats[$sessId];
					
				}
			}
		}
	}
	
	if(isset($utilization[$USER->Browsers[0]->DesiredChatGroup]) && is_array($utilization[$USER->Browsers[0]->DesiredChatGroup]))
	{
		arsort($utilization[$USER->Browsers[0]->DesiredChatGroup]);
		reset($utilization[$USER->Browsers[0]->DesiredChatGroup]);
		$util = end($utilization[$USER->Browsers[0]->DesiredChatGroup]);
		$INTLIST = $utilization[$USER->Browsers[0]->DesiredChatGroup];
	}
	
	if(isset($group_available) && is_array($group_available) && in_array(GROUP_STATUS_AVAILABLE,$group_available))
		$fromDepartment = true;
	elseif(isset($group_available) && is_array($group_available) && in_array(GROUP_STATUS_BUSY,$group_available))
		$fromDepartmentBusy = true;

	isForward();
	if(isset($group_chats) && is_array($group_chats) && isset($fromDepartment) && $fromDepartment)
		foreach($group_chats as $sessId => $amount)
		{
			if(($group_available[$sessId] == GROUP_STATUS_AVAILABLE  && $amount <= $util) || ($ISFORWARDPROCESSED && isset($desired) && $sessId == $desired))
				$available_internals[] = $sessId;
		}

	if($fromDepartment && sizeof($available_internals) > 0)
	{
		if(is_array($available_internals))
		{
			if(!isnull($desired) && (in_array($desired,$available_internals) || $INTERNAL[$desired]->Status == USER_STATUS_ONLINE))
				$matching_internal = $desired;
			else
			{
				$matching_internal = array_rand($available_internals,1);
				$matching_internal = $available_internals[$matching_internal];
			}
		}
		if($CONFIG["gl_alloc_mode"] != ALLOCATION_MODE_ALL || $fromCookie == $matching_internal)
			$USER->Browsers[0]->DesiredChatPartner = $matching_internal;
	}
	elseif($fromDepartmentBusy)
	{	
		if(!$USER->Browsers[0]->Waiting)
			$USER->Browsers[0]->Waiting = true;
	}
	else
	{
		$USER->AddFunctionCall("lz_chat_add_system_text(8,null);",false);
		$USER->AddFunctionCall("lz_chat_stop_system();",false);
		$INTLIST = array();
	}
}

function getSessionId()
{
	global $CONFIG;
	if(!isnull(getCookieValue("userid")))
		$session = getCookieValue("userid");
	else
		setCookieValue("userid",$session = getId(USER_ID_LENGTH));
	return $session;
}

function getChatId($_user,$_targetGroup)
{
	if(isset($_POST[POST_EXTERN_CHAT_ID]))
	{
		return secPrev($_POST[POST_EXTERN_CHAT_ID]);
	}
	else
	{
		$result = queryDB(true,"SELECT `chat_id` FROM `".DB_PREFIX.DATABASE_INFO."`");
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		$cid = $row["chat_id"]+1;
		queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_INFO."` SET `chat_id`='".mysql_real_escape_string($cid)."' WHERE `chat_id`='".mysql_real_escape_string($row["chat_id"])."'");
		if(mysql_affected_rows() == 0)
		{
			return getChatId();
		}
		else
		{
			registerChat($cid,$_targetGroup);
			$_user->AddFunctionCall("lz_chat_set_id('".$cid."');",false);
			return $cid;
		}
	}
}

function closeOpenChatLog($_internalSystemId,$_externalSystemId)
{
	$file = md5(CALLER_TYPE_EXTERNAL . "_" . $_internalSystemId . "_" . $_externalSystemId);
	queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_CHATS."` SET `endtime`='".mysql_real_escape_string(time())."' WHERE `chat_id`='".mysql_real_escape_string($file)."' AND `endtime`=0 ORDER BY `time` ASC LIMIT 1");
}

function registerChat($_chatId,$_targetGroup)
{
	queryDB(true,"INSERT INTO `".DB_PREFIX.DATABASE_ROOMS."` (`id`,`time`,`last_active`,`status`,`target_group`) VALUES ('".mysql_real_escape_string($_chatId)."','".mysql_real_escape_string(time())."','".mysql_real_escape_string(time())."','0','".mysql_real_escape_string($_targetGroup)."');");
}

function unregisterChat($_chatId)
{
	if(!isnull($_chatId))
		queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_ROOMS."` WHERE `id`='".mysql_real_escape_string($_chatId)."' LIMIT 1;");
}

function getQueueWaitingTime($_position,$_intamount,$min=1)
{
	global $CONFIG;
	if($_intamount == 0)
		$_intamount++;
		
	$result = queryDB(true,"SELECT avg(endtime-time) as waitingtime FROM `".DB_PREFIX.DATABASE_CHATS."` WHERE endtime>0;");
	if($result)
	{
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		if(!isnull($row["waitingtime"]))
			$min = ($row["waitingtime"]/60)/$_intamount;
		else
			$min = $min/$_intamount;
		$minb = $min;
		for($i = 1;$i < $_position; $i++)
		{
			$minb *= 0.9;
			$min += $minb;
		}
		$min /= $CONFIG["gl_sim_ch"];
		$min -= (time() - CHAT_START_TIME) / 60;
		if($min <= 0)
			$min = 1;
	}
	return ceil($min);
}

function getQueuePosition($_chatId,$_targetGroup,$_startTime=0,$_position = 1)
{
	global $CONFIG;
	updateRoom($_chatId,CHAT_STATUS_OPEN,$_targetGroup);
	queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_ROOMS."` WHERE `status` = 0 AND `last_active` < " . mysql_real_escape_string(time()-20));
	$result = queryDB(true,"SELECT `id`,`time` FROM `".DB_PREFIX.DATABASE_ROOMS."` WHERE `status` = 0 AND `target_group`='".mysql_real_escape_string($_targetGroup)."' ORDER BY `time` ASC;");
	if($result)
		while($row = mysql_fetch_array($result, MYSQL_BOTH))
		{
			if($row["id"] != $_chatId)
				$_position++;
			else
			{
				$_startTime = $row["time"];
				break;
			}
		}
	define("CHAT_START_TIME",$_startTime);
	return $_position;
}

function updateRoom($_chatId,$_status,$_targetGroup=null)
{
	queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_ROOMS."` SET `last_active`='".mysql_real_escape_string(time())."',`status`='".mysql_real_escape_string($_status)."' WHERE `id`='".mysql_real_escape_string($_chatId)."';");
	if(@mysql_affected_rows() == 0 && !isnull($_targetGroup))
		registerChat($_chatId,$_targetGroup);
}

function isRatingFlood()
{
	$result = queryDB(true,"SELECT count(id) as rating_count FROM `".DB_PREFIX.DATABASE_RATINGS."` WHERE time>".mysql_real_escape_string(time()-86400)." AND ip='".mysql_real_escape_string(getIP())."';");
	if($result)
	{
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		return ($row["rating_count"] >= MAX_RATES_PER_DAY);
	}
	else
		return true;
}

function saveRating($_rating)
{
	$time = time();
	while(true)
	{
		queryDB(true,"SELECT time FROM `".DB_PREFIX.DATABASE_RATINGS."` WHERE time=".mysql_real_escape_string($time).";");
		if(@mysql_affected_rows() > 0)
			$time++;
		else
			break;
	}
	queryDB(true,"INSERT INTO `".DB_PREFIX.DATABASE_RATINGS."` (`id` ,`time` ,`user_id` ,`internal_id` ,`fullname` ,`email` ,`company` ,`qualification` ,`politeness` ,`comment` ,`ip`) VALUES ('".mysql_real_escape_string($_rating->Id)."', ".mysql_real_escape_string($time)." , '".mysql_real_escape_string($_rating->UserId)."', '".mysql_real_escape_string($_rating->InternId)."', '".mysql_real_escape_string($_rating->Fullname)."', '".mysql_real_escape_string($_rating->Email)."', '".mysql_real_escape_string($_rating->Company)."', '".mysql_real_escape_string($_rating->RateQualification)."', '".mysql_real_escape_string($_rating->RatePoliteness)."', '".mysql_real_escape_string($_rating->RateComment)."', '".mysql_real_escape_string(getIP())."');");
}

function isTicketFlood()
{
	$result = queryDB(true,"SELECT count(id) as ticket_count FROM `".DB_PREFIX.DATABASE_TICKET_MESSAGES."` WHERE time>".mysql_real_escape_string(time()-86400)." AND ip='".mysql_real_escape_string(getIP())."';");
	if($result)
	{
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		return ($row["ticket_count"] >= MAX_RATES_PER_DAY);
	}
	else
		return true;
}

function getTicketId()
{
	$result = queryDB(true,"SELECT `ticket_id` FROM `".DB_PREFIX.DATABASE_INFO."`");
	$row = mysql_fetch_array($result, MYSQL_BOTH);
	$tid = $row["ticket_id"]+1;
	queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_INFO."` SET ticket_id='".mysql_real_escape_string($tid)."' WHERE ticket_id='".mysql_real_escape_string($row["ticket_id"])."'");
	return $tid;
}

function saveTicket($_ticket)
{
	$time = time();
	while(true)
	{
		queryDB(true,"SELECT time FROM `".DB_PREFIX.DATABASE_TICKET_MESSAGES."` WHERE time=".mysql_real_escape_string($time).";");
		if(@mysql_affected_rows() > 0)
			$time++;
		else
			break;
	}
	queryDB(true,"INSERT INTO `".DB_PREFIX.DATABASE_TICKETS."` (`id` ,`user_id` ,`target_group_id`) VALUES ('".mysql_real_escape_string($_ticket->Id)."', '".mysql_real_escape_string($_ticket->UserId)."', '".mysql_real_escape_string($_ticket->Group)."');");
	queryDB(true,"INSERT INTO `".DB_PREFIX.DATABASE_TICKET_MESSAGES."` (`id` ,`time` ,`ticket_id` ,`text` ,`fullname` ,`email` ,`company` ,`ip`) VALUES (NULL, ".mysql_real_escape_string($time).", '".mysql_real_escape_string($_ticket->Id)."', '".mysql_real_escape_string($_ticket->Text)."', '".mysql_real_escape_string($_ticket->Fullname)."', '".mysql_real_escape_string($_ticket->Email)."', '".mysql_real_escape_string($_ticket->Company)."', '".mysql_real_escape_string($_ticket->IP)."');");
}
?>
