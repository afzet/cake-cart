<?php

/****************************************************************************************
* LiveZilla functions.internal.man.inc.php // VERSION 3.1.8.4
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

function setAvailability($_available)
{
	global $INTERNAL,$RESPONSE;
	if($INTERNAL[CALLER_SYSTEM_ID]->Level==USER_LEVEL_ADMIN)
	{
		if($_available=="1" && file_exists(FILE_SERVER_DISABLED))
			@unlink(FILE_SERVER_DISABLED);
		else if($_available=="0")
			createFile(FILE_SERVER_DISABLED,time(),true);
		$RESPONSE->SetStandardResponse(1,"");
	}
}

function setIdle($_idle)
{
	global $INTERNAL,$RESPONSE;
	if($INTERNAL[CALLER_SYSTEM_ID]->Level==USER_LEVEL_ADMIN)
	{
		if($_idle=="0" && file_exists(FILE_SERVER_IDLE))
			@unlink(FILE_SERVER_IDLE);
		else if($_idle=="1")
			createFile(FILE_SERVER_IDLE,time(),true);
		$RESPONSE->SetStandardResponse(1,"");
	}
}

function getBannerList($list = "")
{
	global $VISITOR,$CONFIG,$RESPONSE;
	$banners = getDirectory(PATH_BANNER,".php",true);
	foreach($banners as $banner)
	{
		if(@is_dir(PATH_BANNER . $banner) || (strpos($banner,"_0.png") === false && strpos($banner,"_1.png") === false))
			continue;
		$list .= "<banner name=\"".base64_encode($banner)."\" hash=\"".base64_encode(hashFile(PATH_BANNER . $banner))."\"/>\r\n";
	}
	$RESPONSE->SetStandardResponse(1,"<banner_list>".$list."</banner_list>");
}

function updatePredefinedMessages($_counter = 0)
{
	global $GROUPS,$INTERNAL;
	clearPredefinedMessages();

	$tpm_types = array("g"=>$GROUPS,"u"=>$INTERNAL);
	$pms = array();
	foreach($tpm_types as $type => $objectlist)
		foreach($objectlist as $id => $object)
		{
			$pms[$type.$id] = array();
			foreach($_POST as $key => $value)
				if(strpos($key,"p_db_pm_".$type."_" . $id . "_")===0)
				{
					$parts = explode("_",$key);
					if(!isset($pms[$type.$id][$parts[5]]))
					{
						$pms[$type.$id][$parts[5]] = new PredefinedMessage();
						$pms[$type.$id][$parts[5]]->GroupId = ($type=="g") ? $id : "";
						$pms[$type.$id][$parts[5]]->UserId = ($type=="u") ? $id : "";
						$pms[$type.$id][$parts[5]]->LangISO = $parts[5];
					}
					$pms[$type.$id][$parts[5]]->XMLParamAlloc($parts[6],$value);
				}
		}
	
	foreach($pms as $oid => $messages)
		foreach($messages as $iso => $message)
		{
			$message->Id = $_counter++;
			savePredefinedMessage($message);
		}
}

function setManagement()
{
	global $INTERNAL,$RESPONSE,$GROUPS;
	
	if(!DB_CONNECTION)
	{
		$res = testDataBase($CONFIG["gl_db_host"],$CONFIG["gl_db_user"],$CONFIG["gl_db_pass"],$CONFIG["gl_db_name"],$CONFIG["gl_db_prefix"]);
			if(!isnull($res))
				$RESPONSE->SetValidationError(LOGIN_REPLY_DB,$res);
		return;
	}
	
	if($INTERNAL[CALLER_SYSTEM_ID]->Level == USER_LEVEL_ADMIN)
	{
		createFile(PATH_USERS . "internal.inc.php",base64_decode($_POST[POST_INTERN_FILE_INTERN]),true);
		createFile(PATH_GROUPS . "groups.inc.php",base64_decode($_POST[POST_INTERN_FILE_GROUPS]),true);
		getData(true,true,true,false);
		updatePredefinedMessages();
			
		if(isset($_POST[POST_INTERN_EDIT_USER]))
		{
			$combos = explode(";",$_POST[POST_INTERN_EDIT_USER]);
			for($i=0;$i<count($combos);$i++)
				if(strpos($combos[$i],",") !== false)
				{
					$vals = explode(",",$combos[$i]);
					if(strlen($vals[1])>0)
						$INTERNAL[$vals[0]]->ChangePassword($vals[1]);
					$INTERNAL[$vals[0]]->SetPasswordChangeNeeded(($vals[2] == 1));
				}
		}
		$userdirs = getDirectory(PATH_DATA_INTERNAL,".htm",true);
		foreach($userdirs as $userdir)
			if(!isset($INTERNAL[$userdir]))
				deleteDirectory(PATH_DATA_INTERNAL . $userdir);
				
		$datafiles = getDirectory(PATH_USERS,".htm",true);
		foreach($datafiles as $datafile)
			if(strpos($datafile, FILE_EXTENSION_PASSWORD) !== false || strpos($datafile, FILE_EXTENSION_CHANGE_PASSWORD) !== false)
			{
				$parts = explode(".",$datafile);
				if(!isset($INTERNAL[$parts[0]]))
					@unlink(PATH_USERS . $datafile);
			}
		setIdle(0);
		$RESPONSE->SetStandardResponse(1,"");
	}
}

function setConfig($id = 0)
{
	global $INTERNAL,$RESPONSE;
	if(SERVERSETUP)
	{
		$id = createFile(FILE_CONFIG,base64_decode($_POST[POST_INTERN_UPLOAD_VALUE]),true);
		if(isset($_POST[POST_INTERN_SERVER_AVAILABILITY]))
			setAvailability($_POST[POST_INTERN_SERVER_AVAILABILITY]);
		
		if(isset($_POST[POST_INTERN_FILE_CARRIER_LOGO]) && strlen($_POST[POST_INTERN_FILE_CARRIER_LOGO]) > 0)
			base64ToFile(FILE_CARRIERLOGO,$_POST[POST_INTERN_FILE_CARRIER_LOGO]);
		else if(isset($_POST[POST_INTERN_FILE_CARRIER_LOGO]) && file_exists(FILE_CARRIERLOGO))
			@unlink(FILE_CARRIERLOGO);
			
		if(isset($_POST[POST_INTERN_FILE_INVITATION_LOGO]) && strlen($_POST[POST_INTERN_FILE_INVITATION_LOGO]) > 0)
			base64ToFile(FILE_INVITATIONLOGO,$_POST[POST_INTERN_FILE_INVITATION_LOGO]);
		else if(isset($_POST[POST_INTERN_FILE_INVITATION_LOGO]) && file_exists(FILE_INVITATIONLOGO))
			@unlink(FILE_INVITATIONLOGO);
	}
	removeSSpanFile(true);
	setIdle(0);
	$RESPONSE->SetStandardResponse($id,"");
}

function scriptTest($id=0)
{
	global $RESPONSE;
	if(checkPhpVersion(PHP_NEEDED_MAJOR,PHP_NEEDED_MINOR,PHP_NEEDED_BUILD))
		$id = 1;
	$RESPONSE->SetStandardResponse($id,"");
}

function dataBaseTest($id=0)
{
	global $RESPONSE;
	$res = testDataBase($_POST[POST_INTERN_DATABASE_HOST],$_POST[POST_INTERN_DATABASE_USER],$_POST[POST_INTERN_DATABASE_PASS],$_POST[POST_INTERN_DATABASE_NAME],$_POST[POST_INTERN_DATABASE_PREFIX]);
	if(isnull($res))
		$RESPONSE->SetStandardResponse(1,base64_encode(""));
	else
		$RESPONSE->SetStandardResponse(2,base64_encode($res));
	
}

function sendTestMail()
{
	global $RESPONSE,$CONFIG;
	$return = sendMail($CONFIG["gl_mail_sender"],$CONFIG["gl_mail_sender"],$CONFIG["gl_mail_sender"],"LiveZilla Test Mail","LiveZilla Test Mail");
	if(isnull($return))
		$RESPONSE->SetStandardResponse(1,base64_encode(""));
	else
		$RESPONSE->SetStandardResponse(2,base64_encode($return));
}

function createTables($id=0)
{
	global $RESPONSE,$GROUPS;
	$connection = @mysql_connect($_POST[POST_INTERN_DATABASE_HOST],$_POST[POST_INTERN_DATABASE_USER],$_POST[POST_INTERN_DATABASE_PASS]);
	if(!$connection)
	{
		$error = mysql_error();
		$RESPONSE->SetStandardResponse($id,base64_encode("Can't connect to database. Invalid host or login! (" . mysql_errno() . ((!isnull($error)) ? ": " . $error : "") . ")"));
	}
	else
	{
		$db_selected = mysql_select_db(mysql_real_escape_string($_POST[POST_INTERN_DATABASE_NAME]),$connection);
		if (!$db_selected) 
    		$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error()));
		else
		{
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_CHATS."` (`id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`time` int(11) unsigned NOT NULL,`endtime` int(11) unsigned NOT NULL,`closed` int(11) unsigned NOT NULL,`chat_id` varchar(64) character set utf8 collate utf8_bin NOT NULL,`external_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`fullname` varchar(32) character set utf8 collate utf8_bin NOT NULL,`internal_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`html` longtext character set utf8 collate utf8_bin NOT NULL,`plain` longtext character set utf8 collate utf8_bin NOT NULL,`email` varchar(50) character set utf8 collate utf8_bin NOT NULL,`company` varchar(50) character set utf8 collate utf8_bin NOT NULL,`iso_language` varchar(8) character set utf8 collate utf8_bin NOT NULL,`host` varchar(64) character set utf8 collate utf8_bin NOT NULL,`ip` varchar(15) character set utf8 collate utf8_bin NOT NULL,`gzip` tinyint(1) unsigned NOT NULL,`transcript_sent` tinyint(1) unsigned NOT NULL, PRIMARY KEY(`id`)) ENGINE=MyISAM;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_DATA."` (`file` varchar(254) character set utf8 collate utf8_bin NOT NULL,`time` int(11) unsigned NOT NULL,`data` text character set utf8 collate utf8_bin NOT NULL,`size` mediumint(9) unsigned NOT NULL, UNIQUE KEY `file` (`file`)) ENGINE=MyISAM";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_INFO."` (`version` varchar(15) character set utf8 collate utf8_bin NOT NULL,`chat_id` int(11) unsigned NOT NULL default '11700',`ticket_id` int(11) unsigned NOT NULL default '11700', PRIMARY KEY  (`version`)) ENGINE=MyISAM;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "INSERT INTO `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_INFO."` (`version`,`chat_id`,`ticket_id`) VALUES ('".VERSION."',11700,11700);";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1062)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_INTERNAL."` (`id` bigint(20) unsigned NOT NULL auto_increment,`time` int(11) unsigned NOT NULL,`time_confirmed` int(11) unsigned NOT NULL,`internal_id` varchar(15) character set utf8 collate utf8_bin NOT NULL, `status` tinyint(1) unsigned NOT NULL,  PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_RESOURCES."`  (`id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`owner` varchar(15) character set utf8 collate utf8_bin NOT NULL,`editor` varchar(15) character set utf8 collate utf8_bin NOT NULL,`value` longtext character set utf8 collate utf8_bin NOT NULL,`edited` int(11) unsigned NOT NULL,`title` varchar(255) character set utf8 collate utf8_bin NOT NULL,`created` int(11) unsigned NOT NULL,`type` tinyint(1) unsigned NOT NULL,`discarded` tinyint(1) unsigned NOT NULL,`parentid` varchar(32) character set utf8 collate utf8_bin NOT NULL,`rank` int(11) unsigned NOT NULL,`size` bigint(20) unsigned NOT NULL, UNIQUE KEY `id` (`id`)) ENGINE=MyISAM;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_PREDEFINED."` (`id` int(11) unsigned NOT NULL,`internal_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`group_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`lang_iso` varchar(2) character set utf8 collate utf8_bin NOT NULL,`invitation` mediumtext character set utf8 collate utf8_bin NOT NULL,`welcome` mediumtext character set utf8 collate utf8_bin NOT NULL,`website_push` mediumtext character set utf8 collate utf8_bin NOT NULL,`browser_ident` tinyint(1) unsigned NOT NULL default '0',`is_default` tinyint(1) unsigned NOT NULL default '0', `auto_welcome` tinyint(1) unsigned NOT NULL default '0',PRIMARY KEY  (`id`)) ENGINE=MyISAM;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			else if($result)
			{
				$counter=0;
				foreach($GROUPS as $gid => $group)
				{
					mysql_query("INSERT INTO `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_PREDEFINED."` (`id` ,`internal_id` ,`group_id` ,`lang_iso` ,`invitation` ,`welcome` ,`website_push` ,`browser_ident` ,`is_default` ,`auto_welcome`)VALUES ('".mysql_real_escape_string($counter++)."', '','".mysql_real_escape_string($gid)."', 'EN', 'Hello, my name is %name%. Do you need help? Start Live-Chat now to get assistance.', 'Hello %external_name%, my name is %name%, how may I help you?', 'Website Operator %name% would like to redirect you to this URL:\r\n\r\n%url%', '1', '1', '1');",$connection);
					mysql_query("INSERT INTO `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_PREDEFINED."` (`id` ,`internal_id` ,`group_id` ,`lang_iso` ,`invitation` ,`welcome` ,`website_push` ,`browser_ident` ,`is_default` ,`auto_welcome`)VALUES ('".mysql_real_escape_string($counter++)."', '','".mysql_real_escape_string($gid)."', 'DE', '".utf8_encode("Guten Tag, meine Name ist %name%. Benötigen Sie Hilfe? Gerne berate ich Sie in einem Live Chat.")."', 'Guten Tag %external_name%, mein Name ist %name% wie kann ich Ihnen helfen?', '".utf8_encode("Ein Betreuer dieser Webseite (%name%) möchte Sie auf einen anderen Bereich weiterleiten:\\r\\n\\r\\n%url%")."', '1', '0', '1');",$connection);
				}
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_RATINGS."` (`id` varchar(32) character set utf8 collate utf8_bin NOT NULL, `time` int(11) unsigned NOT NULL, `user_id` varchar(32) character set utf8 collate utf8_bin NOT NULL, `internal_id` varchar(32) character set utf8 collate utf8_bin NOT NULL, `fullname` varchar(32) character set utf8 collate utf8_bin NOT NULL, `email` varchar(50) character set utf8 collate utf8_bin NOT NULL, `company` varchar(50) character set utf8 collate utf8_bin NOT NULL, `qualification` tinyint(1) unsigned NOT NULL, `politeness` tinyint(1) unsigned NOT NULL, `comment` varchar(400) character set utf8 collate utf8_bin NOT NULL, `ip` varchar(15) character set utf8 collate utf8_bin NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_ROOMS."` (`id` int(11) NOT NULL,`time` int(11) NOT NULL,`last_active` int(11) NOT NULL,`status` tinyint(1) NOT NULL default '0',`target_group` varchar(64) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_TICKETS."` (`id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`user_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`target_group_id` varchar(32) character set utf8 collate utf8_bin NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}

			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_TICKET_EDITORS."` (`ticket_id` int(10) unsigned NOT NULL,`internal_fullname` varchar(32) character set utf8 collate utf8_bin NOT NULL,`status` tinyint(1) unsigned NOT NULL default '1',`time` int(10) unsigned NOT NULL,PRIMARY KEY  (`ticket_id`)) ENGINE=MyISAM;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			
			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_TICKET_MESSAGES."` (`id` int(11) unsigned NOT NULL auto_increment,`time` int(11) unsigned NOT NULL,`ticket_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`text` mediumtext character set utf8 collate utf8_bin NOT NULL,`fullname` varchar(32) character set utf8 collate utf8_bin NOT NULL,`email` varchar(50) character set utf8 collate utf8_bin NOT NULL,`company` varchar(50) character set utf8 collate utf8_bin NOT NULL,`ip` varchar(15) character set utf8 collate utf8_bin NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1;";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			$RESPONSE->SetStandardResponse(1,base64_encode(""));
			

			$sql = "CREATE TABLE `".mysql_real_escape_string($_POST[POST_INTERN_DATABASE_PREFIX]).DATABASE_POSTS."` (`id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`time` int(11) unsigned NOT NULL default '0',`micro` int(11) unsigned NOT NULL default '0',`sender` varchar(32) character set utf8 collate utf8_bin NOT NULL,`receiver` varchar(32) character set utf8 collate utf8_bin NOT NULL,`receiver_group` varchar(32) character set utf8 collate utf8_bin NOT NULL,`text` mediumtext character set utf8 collate utf8_bin NOT NULL,`received` tinyint(1) unsigned NOT NULL default '0',`persistent` tinyint(1) unsigned NOT NULL default '0', PRIMARY KEY  (`id`)) ENGINE=MyISAM";
			$result = mysql_query($sql,$connection);
			if(!$result && mysql_errno() != 1050)
			{
				$RESPONSE->SetStandardResponse($id,base64_encode(mysql_errno() . ": " . mysql_error() . "\r\n\r\nSQL: " . $sql));
				return;
			}
			$RESPONSE->SetStandardResponse(1,base64_encode(""));
		}
	}
}

function testDataBase($_host,$_user,$_pass,$_dbname,$_prefix)
{
	if(!function_exists("mysql_connect"))
		return "PHP/MySQL extension is missing (php_mysql.dll)";
		
	$connection = @mysql_connect($_host,$_user,$_pass);
	if(!$connection)
	{
		$error = mysql_error();
		return "Can't connect to database. Invalid host or login! (" . mysql_errno() . ((!isnull($error)) ? ": " . $error : "") . ")";
	}
	else
	{
		$db_selected = @mysql_select_db(mysql_real_escape_string($_dbname),$connection);
		if (!$db_selected) 
    		return mysql_errno() . ": " . mysql_error();
		else
		{
			$rand = substr(rand(10000,1000000),0,15);
			$tables = array(DATABASE_DATA=>array("`file`","`time`","`data`","`size`"),DATABASE_CHATS=>array("`id`","`time`","`endtime`","`closed`","`chat_id`","`external_id`","`fullname`","`internal_id`","`html`","`plain`","`email`","`company`","`iso_language`","`host`","`ip`","`gzip`","`transcript_sent`"),DATABASE_INFO=>array("`version`","`chat_id`","`ticket_id`"),DATABASE_INTERNAL=>array("`id`","`time`","`time_confirmed`","`internal_id`","`status`"),DATABASE_RESOURCES=>array("`id`","`owner`","`editor`","`value`","`edited`","`title`","`created`","`type`","`discarded`","`parentid`","`rank`","`size`"),DATABASE_PREDEFINED=>array("`id`","`internal_id`","`group_id`","`lang_iso`","`invitation`","`welcome`","`website_push`","`browser_ident`","`is_default`","`auto_welcome`"),DATABASE_ROOMS=>array("`id`","`time`","`last_active`","`status`","`target_group`"),DATABASE_TICKETS=>array("`id`","`user_id`","`target_group_id`"),DATABASE_TICKET_MESSAGES=>array("`id`","`time`","`ticket_id`","`text`","`fullname`","`email`","`company`","`ip`"),DATABASE_TICKET_EDITORS=>array("`ticket_id`","`internal_fullname`","`status`","`time`"),DATABASE_POSTS=>array("`id`","`time`","`micro`","`sender`","`receiver`","`receiver_group`","`text`","`received`","`persistent`"));
			
			$result = @mysql_query("SELECT version FROM `".mysql_real_escape_string($_prefix).DATABASE_INFO."`",$connection);
			$row = @mysql_fetch_array($result, MYSQL_BOTH);
			$version = $row["version"];
			if(!$result || isnull($version))
				return "Cannot read the LiveZilla Database version. Please try to recreate the table structure.";
			
			if($version != VERSION)
			{
				require_once("./_lib/functions.data.db.update.inc.php");
				$upres = updateDatabase($version,$connection,$_prefix);
				if($upres !== true)
					return "Cannot update database structure from [".$version."] to [".VERSION."]. Please make sure that the user " . $_user . " has the MySQL permission to ALTER tables in " . $_dbname .".\r\n\r\nError: " . $upres;
			}
			
			foreach($tables as $tblName => $fieldlist)
			{
				$result = @mysql_query("SHOW COLUMNS FROM `".mysql_real_escape_string($_prefix.$tblName)."`",$connection);
				if(!$result)
					return mysql_errno() . ": " . mysql_error();
				else
				{
					if(@mysql_num_rows($result) == count($fieldlist))
					{
						$queryFields = "INSERT INTO `".mysql_real_escape_string($_prefix.$tblName)."` (";
						$queryValues = ") VALUES (";

						for($i = 0;$i<count($fieldlist);$i++)
						{
							$queryFields .= $fieldlist[$i] . (($i < count($fieldlist)-1) ? "," : "");
							$queryValues .= (($i == 0) ? $rand : "0") . (($i < count($fieldlist)-1) ? "," : "");
						}
	    				$result = @mysql_query($queryFields . $queryValues . ")",$connection);
	    				if(!$result)
							return mysql_errno() . ": " . mysql_error();
						else
						{
							$result = @mysql_query("DELETE FROM ".mysql_real_escape_string($_prefix.$tblName)." WHERE ".mysql_real_escape_string(substr($fieldlist[0],1,strlen($fieldlist[0])-2))."=".mysql_real_escape_string($rand),$connection);
	    					if(!($result && @mysql_affected_rows() > 0))
								return mysql_errno() . ": " . mysql_error();
						}
					}
					else
						return "Invalid field count for " . $_prefix.$tblName . ". Delete " . $_prefix.$tblName. " manually and try to recreate the tables.";
				}
			}
			return null;
		}
	}
}


?>
