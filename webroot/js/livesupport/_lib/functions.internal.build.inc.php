<?php

/****************************************************************************************
* LiveZilla intern.build.inc.php // VERSION 3.1.8.4
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

function buildFilters()
{
	global $FILTERS,$GROUPS,$INTERNAL,$RESPONSE;
	$RESPONSE->Filter = "";
	if(!$INTERNAL[CALLER_SYSTEM_ID]->IsExternal($GROUPS))
		return;
	foreach($FILTERS->Filters as $id => $filter)
	{
		if($filter->Expiredate != -1 && ($filter->Expiredate + $filter->Created) < time())
			$filter->Destroy();
		else
			$RESPONSE->Filter .= $filter->GetXML();
	}
}

function buildResources($xml="",$count=0,$last=0)
{
	global $RESPONSE,$INTERNAL;
	$resources = array();
	if($_POST[POST_INTERN_XMLCLIP_RESSOURCES_END_TIME] == XML_CLIP_NULL)
		$_POST[POST_INTERN_XMLCLIP_RESSOURCES_END_TIME] = 0;

	if($result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_RESOURCES."` WHERE `edited` > ".mysql_real_escape_string($_POST[POST_INTERN_XMLCLIP_RESSOURCES_END_TIME])." AND `edited`<".mysql_real_escape_string(time())." ORDER BY `edited` ASC"))
	{
		while($row = mysql_fetch_array($result, MYSQL_BOTH))
			$resources[] = $row;
	}
	
	foreach($resources as $res)
	{
		if(++$count <= DATA_ITEM_LOADS || $res["edited"] == $last)
			$xml .= "<r rid=\"".base64_encode($res["id"])."\" si=\"".base64_encode($res["size"])."\" di=\"".base64_encode($res["discarded"])."\" oid=\"".base64_encode($res["owner"])."\" eid=\"".base64_encode($res["editor"])."\" ty=\"".base64_encode($res["type"])."\" ti=\"".base64_encode($res["title"])."\" ed=\"".base64_encode($last = $res["edited"])."\" pid=\"".base64_encode($res["parentid"])."\" ra=\"".base64_encode($res["rank"])."\">".base64_encode($res["value"])."</r>\r\n";
		else
			break;
	}
	$RESPONSE->Resources = (strlen($xml) > 0) ? $xml : null;
}

function buildArchive($_external,$xml="",$count=0,$last=0)
{
	global $RESPONSE,$INTERNAL;
	$permission = ($INTERNAL[CALLER_SYSTEM_ID]->GetPermission(PERMISSION_CHATS) != PERMISSION_NONE);
	$chats = array();
	if($_POST[POST_INTERN_XMLCLIP_ARCHIVE_END_TIME] == XML_CLIP_NULL)
		$_POST[POST_INTERN_XMLCLIP_ARCHIVE_END_TIME] = 0;
	
	if($result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_CHATS."` WHERE `html`!='0' AND `closed` > ".mysql_real_escape_string($_POST[POST_INTERN_XMLCLIP_ARCHIVE_END_TIME])." AND `closed` < ".mysql_real_escape_string(time())." AND `internal_id` !='0' ORDER BY `time` ASC"))
	{
		while($row = mysql_fetch_array($result, MYSQL_BOTH))
			$chats[] = $row;
	}

	foreach($chats as $chat)
	{
		if(++$count < DATA_ITEM_LOADS || $chat["closed"] == $last)
		{
			if($_external && $permission)
				$xml .= "<c cid=\"".base64_encode($chat["chat_id"])."\" iid=\"".base64_encode($chat["internal_id"])."\" eid=\"".base64_encode($chat["external_id"])."\" en=\"".base64_encode($chat["fullname"])."\" ts=\"".base64_encode($chat["time"])."\" cl=\"".base64_encode($last = $chat["closed"])."\" te=\"".base64_encode($chat["endtime"])."\" em=\"".base64_encode($chat["email"])."\" co=\"".base64_encode($chat["company"])."\" il=\"".base64_encode($chat["iso_language"])."\" ho=\"".base64_encode($chat["host"])."\" ip=\"".base64_encode($chat["ip"])."\" gzip=\"".base64_encode($chat["gzip"])."\">".base64_encode($chat["html"])."</c>\r\n";
			else
				$xml .= "<c cl=\"".base64_encode($last = $chat["closed"])."\" />\r\n";
		}
		else
			break;
	}
	$RESPONSE->Archive = (strlen($xml) > 0) ? $xml : null;
}

function buildRatings($xml="")
{
	global $RESPONSE,$INTERNAL;
	$permission = $INTERNAL[CALLER_SYSTEM_ID]->GetPermission(PERMISSION_RATINGS);
	if($_POST[POST_INTERN_XMLCLIP_RATING_END_TIME] == XML_CLIP_NULL)
		$_POST[POST_INTERN_XMLCLIP_RATING_END_TIME] = 0;

	$result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_RATINGS."` WHERE time>".mysql_real_escape_string($_POST[POST_INTERN_XMLCLIP_RATING_END_TIME])." ORDER BY `time` ASC LIMIT ".mysql_real_escape_string(DATA_ITEM_LOADS).";");
	if($result)
		while($row = mysql_fetch_array($result, MYSQL_BOTH))
		{
			$rating = new Rating($row["id"],$row);
			$xml .= $rating->GetXML($INTERNAL,(($rating->InternId == $INTERNAL[CALLER_SYSTEM_ID]->UserId && $permission != PERMISSION_NONE) || $permission == PERMISSION_FULL));
		}
	queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_RATINGS."` WHERE time<".mysql_real_escape_string(DATA_LIFETIME).";");
	$RESPONSE->Ratings = $xml;
}

function buildMessages($xml="")
{
	global $RESPONSE,$INTERNAL,$GROUPS;
	$permission = $INTERNAL[CALLER_SYSTEM_ID]->GetPermission(PERMISSION_MESSAGES);
	if($_POST[POST_INTERN_XMLCLIP_MESSAGES_END_TIME] == XML_CLIP_NULL)
		$_POST[POST_INTERN_XMLCLIP_MESSAGES_END_TIME] = 0;
		
	$result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_TICKETS."` INNER JOIN `".DB_PREFIX.DATABASE_TICKET_MESSAGES."` ON `".DB_PREFIX.DATABASE_TICKETS."`.`id`=`".DB_PREFIX.DATABASE_TICKET_MESSAGES."`.`ticket_id` WHERE time >".mysql_real_escape_string($_POST[POST_INTERN_XMLCLIP_MESSAGES_END_TIME])." ORDER BY `time` ASC LIMIT ".mysql_real_escape_string(DATA_ITEM_LOADS).";");
	if($result)
		while($row = mysql_fetch_array($result, MYSQL_BOTH))
		{
			$ticket = new UserTicket($row);
			$xml .= $ticket->GetXML($GROUPS,((in_array($ticket->Group,$INTERNAL[CALLER_SYSTEM_ID]->Groups) && $permission != PERMISSION_NONE) || $permission == PERMISSION_FULL));
		}

	$result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_TICKET_EDITORS."` WHERE time >".mysql_real_escape_string($_POST[POST_INTERN_XMLCLIP_MESSAGES_END_TIME])." ORDER BY `time` ASC LIMIT ".mysql_real_escape_string(DATA_ITEM_LOADS).";");
	if($result)
		while($row = mysql_fetch_array($result, MYSQL_BOTH))
		{
			$ticket = new ClosedTicket($row["ticket_id"],$row);
			$xml .= $ticket->GetXML($row["time"],$row["status"]);
		}
	$RESPONSE->Messages = $xml;
}

function buildNewPosts()
{
	global $INTERNAL,$CONFIG,$RESPONSE;
	foreach($INTERNAL[CALLER_SYSTEM_ID]->GetPosts() as $post)
	{
		$RESPONSE->Posts .= $post->GetXml();
		archiveExternalChat($post);
	}
}

function buildIntern()
{
	global $CONFIG,$INTERNAL,$GROUPS,$RESPONSE;
	$builder = new InternalXMLBuilder($CONFIG,$INTERNAL[CALLER_SYSTEM_ID],$INTERNAL,$GROUPS);
	$builder->Generate();
	$RESPONSE->Internals = $builder->XMLInternal;
	$RESPONSE->Typing .= $builder->XMLTyping;
	$RESPONSE->InternalProfilePictures = $builder->XMLProfilePictures;
	$RESPONSE->InternalWebcamPictures = $builder->XMLWebcamPictures;
	$RESPONSE->Groups = $builder->XMLGroups;
	$RESPONSE->InternalVcards = $builder->XMLVisitcards;
}

function buildExtern($objectCount=0)
{
	global $CONFIG,$VISITOR,$INTERNAL,$GROUPS,$RESPONSE;
	$RESPONSE->Tracking = "";
	if(count($VISITOR) > 0)
	{
		$builder = new ExternalXMLBuilder($CONFIG,$INTERNAL[CALLER_SYSTEM_ID],$VISITOR,(isset($_POST[POST_INTERN_RESYNC]) || LOGIN || $_POST[POST_INTERN_XMLCLIP_HASH_EXTERN] == XML_CLIP_NULL),($GROUPS[$INTERNAL[CALLER_SYSTEM_ID]->Groups[0]]["gr_extern"]));
		$builder->SessionFileSizes = $INTERNAL[CALLER_SYSTEM_ID]->VisitorFileSizes;
		$builder->StaticReload = $INTERNAL[CALLER_SYSTEM_ID]->VisitorStaticReload;
		$builder->Generate();
		$RESPONSE->Tracking = $builder->XMLCurrent;
		foreach($builder->DiscardedObjects as $uid => $list)
		{
			$RESPONSE->Tracking .= "<cd id=\"".base64_encode($uid)."\">\r\n";
			if($list != null)
				foreach($builder->DiscardedObjects[$uid] as $list => $bid)
					$RESPONSE->Tracking .= " <bd id=\"".base64_encode($bid)."\" />\r\n";
			$RESPONSE->Tracking .= "</cd>\r\n";
		}
		$RESPONSE->Typing .= $builder->XMLTyping;
		$INTERNAL[CALLER_SYSTEM_ID]->VisitorFileSizes = $builder->SessionFileSizes;
		$INTERNAL[CALLER_SYSTEM_ID]->VisitorStaticReload = $builder->StaticReload;
		if($builder->GetAll && !LOGIN)
			$RESPONSE->Tracking .= "<resync />\r\n";
		$objectCount = $builder->ObjectCounter;
	}
	else
		$INTERNAL[CALLER_SYSTEM_ID]->VisitorFileSizes = array();
		
	$RESPONSE->Tracking .= "<sync>".base64_encode($objectCount)."</sync>\r\n";
}
?>
