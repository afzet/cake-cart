<?php

/****************************************************************************************
* LiveZilla functions.internal.inc.php // VERSION 3.1.8.4
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

function processAcceptedConversations()
{
	if(isset($_POST[POST_INTERN_PROCESS_ACCEPTED_CHAT . "_va"]))
		appendAcceptedConversations();
}

function processAuthentications()
{
	if(isset($_POST[POST_INTERN_PROCESS_AUTHENTICATIONS . "_va"]))
		appendAuthentications();
}

function processStatus()
{
	global $INTERNAL;
	if(isset($_POST[POST_INTERN_USER_STATUS]))
		appendStatus();
}

function processAlerts()
{
	if(isset($_POST[POST_INTERN_PROCESS_ALERTS . "_va"]))
		appendAlerts();
}

function processClosures()
{
	if(isset($_POST[POST_INTERN_PROCESS_CLOSURES . "_va"]))
		appendClosures();
}

function processPosts()
{
	if(isset($_POST[POST_INTERN_PROCESS_POSTS . "_va"]))
		appendPosts();
}

function processForwards()
{
	if(isset($_POST[POST_INTERN_PROCESS_FORWARDS . "_va"]))
		appendForwards();
}

function processRequests()
{
	if(isset($_POST[POST_INTERN_PROCESS_REQUESTS . "_va"]))
		appendRequests();
}

function processGuides()
{
	if(isset($_POST[POST_INTERN_PROCESS_GUIDES . "_va"]))
		appendGuides();
}

function processFilters()
{
	if(isset($_POST[POST_INTERN_PROCESS_FILTERS . "_va"]))
		appendFilters();
}

function processVisitcards()
{
	if(isset($_POST[POST_INTERN_PROCESS_VISITCARDS]))
		appendVisitcards();
}

function processProfilePictures()
{
	if(isset($_POST[POST_INTERN_PROCESS_PICTURES]))
		appendProfilePictures();
}

function processWebcamPictures()
{
	if(isset($_POST[POST_INTERN_PROCESS_PICTURES_WEBCAM]))
		appendWebcamPictures();
}

function processBannerPictures()
{
	if(isset($_POST[POST_INTERN_PROCESS_BANNERS]))
		appendBannerPictures();
}

function processPermissions()
{
	if(isset($_POST[POST_INTERN_PROCESS_PERMISSIONS . "_va"]))
		appendPermissions();
}

function processExternalReloads()
{
	if(isset($_POST[POST_INTERN_PROCESS_EXTERNAL_RELOADS]))
		appendExternalReloads();
}

function processClosedTickets()
{
	if(isset($_POST[POST_INTERN_PROCESS_ACCEPTED_MESSAGES . "_va"]))
		appendClosedTickets();
}

function processArchiveChats()
{
	if(isset($_POST[POST_INTERN_PROCESS_CHATS . "_va"]))
		appendArchivedChats();
}

function processResources()
{
	if(isset($_POST[POST_INTERN_PROCESS_RESOURCES]))
		appendResources();
}

function processReceivedPosts()
{
	if(isset($_POST[POST_INTERN_PROCESS_RECEIVED_POSTS]))
		appendReceivedPosts();
}

function appendArchivedChats($xml="")
{
	global $RESPONSE;
	$cids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_CHATS . "_va"]);
	$html = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_CHATS . "_vb"]));
	$timeend = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_CHATS . "_vc"]);
	$gzip = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_CHATS . "_vd"]);
	$times = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_CHATS . "_ve"]);
	foreach($cids as $key => $id)
	{
		queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_CHATS."` SET `html`='".mysql_real_escape_string($html[$key])."',`time`='".mysql_real_escape_string($times[$key])."',`endtime`='".mysql_real_escape_string($timeend[$key])."',`closed`='".mysql_real_escape_string(time()+1)."',`gzip`='".mysql_real_escape_string($gzip[$key])."',`internal_id`='".mysql_real_escape_string(CALLER_SYSTEM_ID)."' WHERE `chat_id`='".mysql_real_escape_string($cids[$key])."' AND `html`='0' ORDER BY `time` ASC LIMIT 1");
		$xml .= "<c cid=\"".base64_encode($cids[$key])."\" te=\"".base64_encode($timeend[$key])."\" />\r\n";
	}
	$RESPONSE->SetStandardResponse(1,$xml);
}

function appendResources($xml="")
{
	global $RESPONSE;
	$rids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_RESOURCES . "_va"]);
	$html = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_RESOURCES . "_vb"]));
	$type = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_RESOURCES . "_vc"]);
	$title = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_RESOURCES . "_vd"]));
	$disc = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_RESOURCES . "_ve"]);
	$parent = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_RESOURCES . "_vf"]);
	$rank = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_RESOURCES . "_vg"]);
	$size = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_RESOURCES . "_vh"]);
	foreach($rids as $key => $id)
	{
		processResource(CALLER_SYSTEM_ID,$rids[$key],$html[$key],$type[$key],$title[$key],$disc[$key],$parent[$key],$rank[$key],$size[$key]);
		$xml .= "<r rid=\"".base64_encode($rids[$key])."\" disc=\"".base64_encode($disc[$key])."\" />\r\n";
	}
	$RESPONSE->SetStandardResponse(1,$xml);
}

function appendClosedTickets()
{
	global $INTERNAL;
	$msgnames = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_ACCEPTED_MESSAGES . "_va"]);
	$msgids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_ACCEPTED_MESSAGES . "_vb"]);
	$msgstatus = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_ACCEPTED_MESSAGES . "_vc"]);
	foreach($msgids as $key => $id)
	{
		$ticket = new ClosedTicket($id);
		$ticket->Sender = $msgnames[$key];
		queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_TICKET_EDITORS."` SET `internal_fullname`='".mysql_real_escape_string($ticket->Sender)."',`status`='".mysql_real_escape_string($msgstatus[$key])."',`time`='".mysql_real_escape_string(time())."' WHERE `ticket_id`='".mysql_real_escape_string($ticket->Id)."';");
		if(@mysql_affected_rows() <= 0)
			queryDB(true,"INSERT INTO `".DB_PREFIX.DATABASE_TICKET_EDITORS."` (`ticket_id` ,`internal_fullname` ,`status`,`time`) VALUES ('".mysql_real_escape_string($ticket->Id)."', '".mysql_real_escape_string($ticket->Sender)."', '".mysql_real_escape_string($msgstatus[$key])."', '".mysql_real_escape_string(time())."');");
	}
}

function appendReceivedPosts()
{
	global $INTERNAL;
	$pids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_RECEIVED_POSTS]);
	foreach($pids as $id)
		markPostReceived($id);
}

function appendExternalReloads()
{
	global $INTERNAL;
	$INTERNAL[CALLER_SYSTEM_ID]->ExternalReloads = Array();
	$userids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_EXTERNAL_RELOADS]);
	foreach($userids as $id)
		$INTERNAL[CALLER_SYSTEM_ID]->VisitorStaticReload[$id] = true;
}

function appendPermissions()
{
	$ids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_PERMISSIONS . "_va"]);
	$results = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_PERMISSIONS . "_vb"]);

	foreach($ids as $key => $id)
	{
		$fur = new FileUploadRequest($ids[$key],CALLER_SYSTEM_ID);
		$fur->Load();
		$fur->Permission = $results[$key];
		$fur->Save();
	}
}

function appendForwards()
{
	global $INTERNAL;
	$users = explode(POST_ACTION_VALUE_SPLITTER,utf8_decode($_POST[POST_INTERN_PROCESS_FORWARDS . "_va"]));
	$receivers = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FORWARDS . "_vb"]);
	$additions = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_FORWARDS . "_vc"]));
	$conversations = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_FORWARDS . "_vd"]));
	$targetgroups = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_FORWARDS . "_ve"]));
	
	foreach($users as $key => $user)
	{
		$parts = explode("~",$user);
		$forward = new Forward($parts[0],$parts[1],$INTERNAL[CALLER_SYSTEM_ID]->SystemId);
		$forward->TargetSessId = $receivers[$key];
		$forward->TargetGroupId = $targetgroups[$key];
		if(strlen($additions[$key]) > 0)
			$forward->Text = $additions[$key];
		if(strlen($conversations[$key]) > 0)
			$forward->Conversation = $conversations[$key];
		$forward->Save();
	}
}

function appendAuthentications()
{
	global $INTERNAL,$RESPONSE;
	$users = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_AUTHENTICATIONS . "_va"]);
	$passwords = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_AUTHENTICATIONS . "_vb"]);
	foreach($users as $key => $user)
	{
		$INTERNAL[$user]->ChangePassword($passwords[$key]);
		$RESPONSE->Authentications = "<val userid=\"".base64_encode($user)."\" pass=\"".base64_encode($passwords[$key])."\" />\r\n";
	}
}

function appendGuides()
{
	global $INTERNAL,$VISITOR;
	$guides = Array();
	$visitors = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_GUIDES . "_va"]);
	$asks = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_GUIDES . "_vb"]);
	$urls = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_GUIDES . "_vc"]));
	$browids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_GUIDES . "_vd"]);
	$texts = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_GUIDES . "_ve"]));
	foreach($visitors as $key => $visitor)
	{
		if(isset($VISITOR[$visitors[$key]]))
		{
			$guide = new Guide($INTERNAL[CALLER_SYSTEM_ID]->SessId,$visitors[$key],$browids[$key],$asks[$key]);
			$guide->SenderFullname = $INTERNAL[CALLER_SYSTEM_ID]->Fullname;
			$guide->Text = $texts[$key];
			$guide->TargetURL = $urls[$key];
			$guides[$visitors[$key].$browids[$key]] = $guide;
		}
	}
	foreach($guides as $guide)
		$guide->Save();
}

function appendAlerts()
{
	global $VISITOR;
	$alerts = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_ALERTS . "_va"]));
	$visitors = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_ALERTS . "_vb"]);
	$browsers = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_ALERTS . "_vc"]);
	foreach($alerts as $key => $text)
	{
		if(isset($visitors[$key]) && isset($VISITOR[$visitors[$key]]))
		{
			$alert = new Alert(getId(32),$visitors[$key],$browsers[$key]);
			$alert->Alert = $alerts[$key];
			$alert->Save();
		}
	}
}

function appendRequests()
{
	global $INTERNAL,$VISITOR;
	$visitors = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_REQUESTS . "_va"]);
	$browids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_REQUESTS . "_vb"]);
	$reqnames = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_REQUESTS . "_vc"]));
	$reqids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_REQUESTS . "_vd"]);
	$reqtexts = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_REQUESTS . "_ve"]));
	$sendergroup = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_REQUESTS . "_vf"]));
	foreach($reqids as $key => $requestid)
		if(isset($VISITOR[$visitors[$key]]))
		{
			$request = new ChatRequest($INTERNAL[CALLER_SYSTEM_ID]->SessId,$visitors[$key],$browids[$key],REQUEST_STATUS_ACTIVE);
			$request->SenderUserId = $INTERNAL[CALLER_SYSTEM_ID]->UserId;
			$request->SenderGroupId = $sendergroup[$key];
			$request->SenderFullname = ($INTERNAL[CALLER_SYSTEM_ID]->Fullname != null) ? $INTERNAL[CALLER_SYSTEM_ID]->Fullname : $INTERNAL[CALLER_SYSTEM_ID]->UserId;
			$request->Text = $reqtexts[$key];
			$request->Save();
		}
}

function appendFilters()
{
	$creators = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_va"]);
	$createds = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vb"]);
	$editors = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_FILTERS . "_vc"]));
	$ips = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vd"]);
	$expiredates = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_ve"]);
	$userids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vf"]);
	$filternames = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vg"]);
	$reasons = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_FILTERS . "_vh"]));
	$filterids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vi"]);
	$activestates = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vj"]);
	$actiontypes = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vk"]);
	$exertions = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vl"]);
	$languages = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vm"]);
	$activeuserids = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vn"]);
	$activeipaddresses = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vo"]);
	$activelanguages = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_FILTERS . "_vp"]);
	
	foreach($filterids as $key => $id)
	{
		$filter = new Filter($filterids[$key]);
		$filter->Creator = $creators[$key];
		$filter->Created = ($createds[$key] != "0") ? $createds[$key] : time();
		$filter->Editor = $editors[$key];
		$filter->Edited = time();
		$filter->IP = $ips[$key];
		$filter->Expiredate = $expiredates[$key];
		$filter->Userid = $userids[$key];
		$filter->Reason = $reasons[$key];
		$filter->Filtername = $filternames[$key];
		$filter->Activestate = $activestates[$key];
		$filter->Exertion = $exertions[$key];
		$filter->Languages = $languages[$key];
		$filter->Activeipaddress = $activeipaddresses[$key];
		$filter->Activeuserid = $activeuserids[$key];
		$filter->Activelanguage = $activelanguages[$key];
		
		if($actiontypes[$key] == POST_ACTION_ADD || $actiontypes[$key] == POST_ACTION_EDIT)
			$filter->Save();
		else if($actiontypes[$key] == POST_ACTION_REMOVE)
			$filter->Destroy();
	}
}

function appendClosures()
{
	global $INTERNAL,$CONFIG;
	$users = explode(POST_ACTION_VALUE_SPLITTER,utf8_decode($_POST[POST_INTERN_PROCESS_CLOSURES . "_va"]));
	$types = explode(POST_ACTION_VALUE_SPLITTER,utf8_decode($_POST[POST_INTERN_PROCESS_CLOSURES . "_vb"]));
	$browsers = explode(POST_ACTION_VALUE_SPLITTER,utf8_decode($_POST[POST_INTERN_PROCESS_CLOSURES . "_vc"]));
	foreach($users as $key => $userid)
	{
		$chat = new Chat(new ExternalChat($userid,$browsers[$key]),$INTERNAL[CALLER_SYSTEM_ID]);
		if($types[$key] == CHAT_CLOSED)
			$chat->InternalClose();
		else if($types[$key] == CHAT_DECLINED)
			$chat->InternalDecline();
	}
}

function appendPosts()
{
	global $INTERNAL,$GROUPS,$VISITOR,$CONFIG;
	$posts = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_POSTS . "_va"]));
	$receivers = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_POSTS . "_vb"]));
	$postids = explode(POST_ACTION_VALUE_SPLITTER,slashesStrip($_POST[POST_INTERN_PROCESS_POSTS . "_vc"]));
	$time = time();
	foreach($posts as $key => $post)
	{
		if($receivers[$key] == GROUP_EVERYONE_INTERN || isset($GROUPS[$receivers[$key]]))
		{
			foreach($INTERNAL as $internal)
				if($internal->SystemId != CALLER_SYSTEM_ID)
					if($receivers[$key] == GROUP_EVERYONE_INTERN || in_array($receivers[$key],$internal->Groups))
						if($internal->Status != USER_STATUS_OFFLINE || !isnull($CONFIG["gl_ogcm"]))
						{
							$npost = new Post(getId(32),CALLER_SYSTEM_ID,$internal->SystemId,$posts[$key],$time);
							$npost->Persistent = true;
							if($receivers[$key] == GROUP_EVERYONE_INTERN || in_array($receivers[$key],$INTERNAL[CALLER_SYSTEM_ID]->Groups))
								$npost->ReceiverGroup = $receivers[$key];
							$npost->Save();
						}
		}
		else if($receivers[$key] == GROUP_EVERYONE_EXTERN)
		{
			foreach($INTERNAL[CALLER_SYSTEM_ID]->ExternalChats as $chat)
			{
				$npost = new Post(getId(32),CALLER_SYSTEM_ID,$chat->ExternalUser->SystemId,$posts[$key],$time);
				$npost->Save();
			}
		}
		else
		{
			$npost = new Post($postids[$key],CALLER_SYSTEM_ID,$receivers[$key],$posts[$key],$time);
			$npost->Persistent = isset($INTERNAL[$receivers[$key]]);
			$npost->Save();
			
			if(!$npost->Persistent)
				archiveExternalChat($npost);
		}
	}
}

function appendStatus()
{
	global $INTERNAL,$CONFIG;
	if(!LOGIN)
		$INTERNAL[CALLER_SYSTEM_ID]->Status = $_POST[POST_INTERN_USER_STATUS];
	else
		$INTERNAL[CALLER_SYSTEM_ID]->Status = USER_STATUS_OFFLINE;
}

function appendProfilePictures()
{
	global $INTERNAL;
	$pictures = explode(POST_ACTION_VALUE_SPLITTER,utf8_decode($_POST[POST_INTERN_PROCESS_PICTURES]));
	foreach($pictures as $key => $item)
	{
		$filename = PATH_INTERN_IMAGES . "/" . md5($INTERNAL[CALLER_SYSTEM_ID]->UserId) . FILE_EXTENSION_PROFILE_PICTURE;
		if(@file_exists($filename))
			@unlink($filename);
		if(strlen($item) > 0)
			base64ToFile($filename,$item);
	}
}

function appendWebcamPictures()
{
	global $INTERNAL;
	$pictures = explode(POST_ACTION_VALUE_SPLITTER,utf8_decode($_POST[POST_INTERN_PROCESS_PICTURES_WEBCAM]));
	foreach($pictures as $key => $item)
	{
		$filename = PATH_INTERN_IMAGES . "/" . md5($INTERNAL[CALLER_SYSTEM_ID]->UserId) . FILE_EXTENSION_WEBCAM_PICTURE;
		if(file_exists($filename))
			@unlink($filename);
		if(strlen($item) > 0)
			base64ToFile($filename,$item);
	}
}

function appendBannerPictures()
{
	if(file_exists(PATH_BANNER.$_POST[POST_INTERN_PROCESS_BANNERS . "_vb"]))
		unlink(PATH_BANNER.$_POST[POST_INTERN_PROCESS_BANNERS . "_vb"]);
	base64ToFile(PATH_BANNER.$_POST[POST_INTERN_PROCESS_BANNERS . "_vb"],$_POST[POST_INTERN_PROCESS_BANNERS . "_va"]);
	
	if(file_exists(PATH_BANNER.$_POST[POST_INTERN_PROCESS_BANNERS . "_vd"]))
		unlink(PATH_BANNER.$_POST[POST_INTERN_PROCESS_BANNERS . "_vd"]);
	base64ToFile(PATH_BANNER.$_POST[POST_INTERN_PROCESS_BANNERS . "_vd"],$_POST[POST_INTERN_PROCESS_BANNERS . "_vc"]);
}

function appendVisitcards()
{
	global $INTERNAL;
	$visitcards = explode(POST_ACTION_VALUE_SPLITTER,$_POST[POST_INTERN_PROCESS_VISITCARDS]);
	foreach($visitcards as $key => $item)
	{
		$filename = PATH_VISITCARDS . "/" . $INTERNAL[CALLER_SYSTEM_ID]->UserId . FILE_EXTENSION_VISITCARD;
		if(strlen($item) > 0)
			createFile($filename,base64_decode($item),true);
		else
			@unlink($filename);
	}
}

function appendAcceptedConversations()
{
	global $INTERNAL;
	$users = explode(POST_ACTION_VALUE_SPLITTER,utf8_decode($_POST[POST_INTERN_PROCESS_ACCEPTED_CHAT . "_va"]));
	$browsers = explode(POST_ACTION_VALUE_SPLITTER,utf8_decode($_POST[POST_INTERN_PROCESS_ACCEPTED_CHAT . "_vb"]));
	foreach($users as $key => $userid)
	{
		if(strlen($browsers[$key]) > 0 && strlen($userid) > 0)
		{
			$chat = new Chat(new ExternalChat($userid,$browsers[$key]),$INTERNAL[CALLER_SYSTEM_ID]);
			$chat->InternalActivate($INTERNAL[CALLER_SYSTEM_ID]);
		}
	}
}
?>