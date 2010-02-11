<?php

/****************************************************************************************
* LiveZilla functions.data.db.update.inc.php // VERSION 3.1.8.4
* 
* Copyright 2010 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors. It is strongly 
* recommended to desist from editing this file.
* 
***************************************************************************************/ 

function updateDatabase($_version,$_link,$_prefix)
{
	global $GROUPS;
	$versions = array("3.1.8.1","3.1.8.2","3.1.8.3","3.1.8.4");
	if(!in_array($_version,$versions))
		return "Invalid version! (".$_version.")";
	
	while($_version != VERSION)
	{
		if($_version == $versions[0])
			$_version = $versions[1];
		if($_version == $versions[1])
			$_version = $versions[2];
		if($_version == $versions[2])
		{
			$result = up_3183_3184($_prefix,$_link);
			if($result === TRUE)
				$_version = VERSION;
			else
				return $result;
		}
	}
	@mysql_query("UPDATE `".mysql_real_escape_string($_prefix).DATABASE_INFO."` SET `version`='" . VERSION . "'",$_link);
	return true;
}

function up_3183_3184($_prefix,$_link)
{
	global $INTERNAL,$GROUPS;
	$result = @mysql_query("ALTER TABLE `".mysql_real_escape_string($_prefix).DATABASE_INFO."` ADD `chat_id` INT NOT NULL DEFAULT '11700'",$_link);
	if(!$result && mysql_errno() != 1060)
		return mysql_errno() . ": " . mysql_error();
		
	$result = @mysql_query("ALTER TABLE `".mysql_real_escape_string($_prefix).DATABASE_INFO."` ADD `ticket_id` INT NOT NULL DEFAULT '11700'",$_link);
	if(!$result && mysql_errno() != 1060)
		return mysql_errno() . ": " . mysql_error();
		
	$result = @mysql_query("ALTER TABLE `".mysql_real_escape_string($_prefix).DATABASE_CHATS."` ADD `transcript_sent` tinyint(1) unsigned NOT NULL default '0'",$_link);
	if(!$result && mysql_errno() != 1060)
		return mysql_errno() . ": " . mysql_error();

	$result = @mysql_query("ALTER TABLE `".mysql_real_escape_string($_prefix).DATABASE_RESOURCES."` CHANGE `html` `value` longtext character set utf8 collate utf8_bin NOT NULL",$_link);
	if(!$result && mysql_errno() != 1054)
		return mysql_errno() . ": " . mysql_error();

	$result = @mysql_query("ALTER TABLE `".mysql_real_escape_string($_prefix).DATABASE_RESOURCES."` ADD `size` bigint(20) unsigned NOT NULL default '0'",$_link);
	if(!$result && mysql_errno() != 1060)
		return mysql_errno() . ": " . mysql_error();

	$dirs = array(PATH_UPLOADS_INTERNAL,PATH_UPLOADS_EXTERNAL);
	$baseFolderInternal = $baseFolderExternal = false;
	foreach($dirs as $tdir)
	{
		$subdirs = getDirectory($tdir,false,true);
		foreach($subdirs as $dir)
		{
			if(@is_dir($tdir.$dir."/"))
			{
				if($tdir == PATH_UPLOADS_INTERNAL)
					$owner = getInternSessIdByUserId($dir);
				else
					$owner = CALLER_SYSTEM_ID;
				
				if(!isset($INTERNAL[$owner]))
					continue;

				$files = getDirectory($tdir.$dir."/",false,true);
				foreach($files as $file)
				{
					if($file != FILE_INDEX && $file != FILE_INDEX_OLD)
					{
						if($tdir == PATH_UPLOADS_INTERNAL)
						{
							$parentId = $owner;
							$type = 3;
							if(!$baseFolderInternal)
							{
								createFileBaseFolders($owner,true);
								$baseFolderInternal = true;
							}
							processResource($owner,$owner,$INTERNAL[$owner]->Fullname,0,$INTERNAL[$owner]->Fullname,0,4,3);
						}
						else
						{
							$parentId = 5;
							$owner = CALLER_SYSTEM_ID;
							$type = 4;
							if(!$baseFolderExternal)
							{
								createFileBaseFolders($owner,false);
								$baseFolderExternal = true;
							}
						}
						$cfile = ($tdir != PATH_UPLOADS_INTERNAL) ? base64_decode($file) : $file;
						$size = filesize($tdir.$dir."/".$file);
						$fid = md5($file . $owner . $size);
						$filename = $owner . "_" . $fid;
						copy($tdir.$dir."/".$file,PATH_UPLOADS . $filename);
						processResource($owner,$fid,$filename,$type,$cfile,0,$parentId,4,$size);
					}
				}
			}
		}
	}
	
	$sql = "CREATE TABLE `".mysql_real_escape_string($_prefix).DATABASE_PREDEFINED."` (`id` int(11) unsigned NOT NULL,`internal_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`group_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`lang_iso` varchar(2) character set utf8 collate utf8_bin NOT NULL,`invitation` mediumtext character set utf8 collate utf8_bin NOT NULL,`welcome` mediumtext character set utf8 collate utf8_bin NOT NULL,`website_push` mediumtext character set utf8 collate utf8_bin NOT NULL,`browser_ident` tinyint(1) unsigned NOT NULL default '0',`is_default` tinyint(1) unsigned NOT NULL default '0', `auto_welcome` tinyint(1) unsigned NOT NULL default '0',PRIMARY KEY  (`id`)) ENGINE=MyISAM;";
	$result = mysql_query($sql,$_link);
	if(!$result && mysql_errno() != 1050)
		return mysql_errno() . ": " . mysql_error();
	else if($result)
	{
		$counter = 0;
		foreach($GROUPS as $gid => $group)
		{
			@mysql_query("INSERT INTO `".mysql_real_escape_string($_prefix).DATABASE_PREDEFINED."` (`id` ,`internal_id`, `group_id` ,`lang_iso` ,`invitation` ,`welcome` ,`website_push` ,`browser_ident` ,`is_default` ,`auto_welcome`) VALUES ('".mysql_real_escape_string($counter++)."', '', '".mysql_real_escape_string($gid)."', 'EN', 'Hello, my name is %name%. Do you need help? Start Live-Chat now to get assistance.', 'Hello %external_name%, my name is %name%, how may I help you?', 'Website Operator %name% would like to redirect you to this URL:\r\n\r\n%url%', '1', '1', '1');",$_link);
			@mysql_query("INSERT INTO `".mysql_real_escape_string($_prefix).DATABASE_PREDEFINED."` (`id` ,`internal_id`, `group_id` ,`lang_iso` ,`invitation` ,`welcome` ,`website_push` ,`browser_ident` ,`is_default` ,`auto_welcome`) VALUES ('".mysql_real_escape_string($counter++)."', '', '".mysql_real_escape_string($gid)."', 'DE', '".utf8_encode("Guten Tag, meine Name ist %name%. Benötigen Sie Hilfe? Gerne berate ich Sie in einem Live Chat")."', 'Guten Tag %external_name%, mein Name ist %name% wie kann ich Ihnen helfen?', '".utf8_encode("Ein Betreuer dieser Webseite (%name%) möchte Sie auf einen anderen Bereich weiterleiten:\\r\\n\\r\\n%url%")."', '1', '0', '1');",$_link);
		}
	}
	
	$sql = "CREATE TABLE `".mysql_real_escape_string($_prefix).DATABASE_ROOMS."` (`id` int(11) NOT NULL,`time` int(11) NOT NULL,`last_active` int(11) NOT NULL,`status` tinyint(1) NOT NULL default '0',`target_group` varchar(64) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM;";
	$result = mysql_query($sql,$_link);
	if(!$result && mysql_errno() != 1050)
		return mysql_errno() . ": " . mysql_error();
		
	$sql = "CREATE TABLE `".mysql_real_escape_string($_prefix).DATABASE_POSTS."` (`id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`time` int(10) unsigned NOT NULL default '0',`micro` int(10) unsigned NOT NULL default '0',`sender` varchar(32) character set utf8 collate utf8_bin NOT NULL,`receiver` varchar(32) character set utf8 collate utf8_bin NOT NULL,`receiver_group` varchar(32) character set utf8 collate utf8_bin NOT NULL,`text` mediumtext character set utf8 collate utf8_bin NOT NULL,`received` tinyint(1) unsigned NOT NULL default '0',`persistent` tinyint(1) unsigned NOT NULL default '0', PRIMARY KEY  (`id`)) ENGINE=MyISAM";
	$result = mysql_query($sql,$_link);
	if(!$result && mysql_errno() != 1050)
		return mysql_errno() . ": " . mysql_error();
		
	$sql = "CREATE TABLE `".mysql_real_escape_string($_prefix).DATABASE_TICKETS."` (`id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`user_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`target_group_id` varchar(32) character set utf8 collate utf8_bin NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM;";
	$result = mysql_query($sql,$_link);
	if(!$result && mysql_errno() != 1050)
		return mysql_errno() . ": " . mysql_error();

	$sql = "CREATE TABLE `".mysql_real_escape_string($_prefix).DATABASE_TICKET_EDITORS."` (`ticket_id` int(10) unsigned NOT NULL,`internal_fullname` varchar(32) character set utf8 collate utf8_bin NOT NULL,`status` tinyint(1) unsigned NOT NULL default '1',`time` int(10) unsigned NOT NULL,PRIMARY KEY  (`ticket_id`)) ENGINE=MyISAM;";
	$result = mysql_query($sql,$_link);
	if(!$result && mysql_errno() != 1050)
		return mysql_errno() . ": " . mysql_error();
			
	$sql = "CREATE TABLE `".mysql_real_escape_string($_prefix).DATABASE_TICKET_MESSAGES."` (`id` int(11) unsigned NOT NULL auto_increment,`time` int(11) unsigned NOT NULL,`ticket_id` varchar(32) character set utf8 collate utf8_bin NOT NULL,`text` mediumtext character set utf8 collate utf8_bin NOT NULL,`fullname` varchar(32) character set utf8 collate utf8_bin NOT NULL,`email` varchar(50) character set utf8 collate utf8_bin NOT NULL,`company` varchar(50) character set utf8 collate utf8_bin NOT NULL,`ip` varchar(15) character set utf8 collate utf8_bin NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1;";
	$result = mysql_query($sql,$_link);
	if(!$result && mysql_errno() != 1050)
		return mysql_errno() . ": " . mysql_error();
	
	$sql = "CREATE TABLE `".mysql_real_escape_string($_prefix).DATABASE_RATINGS."` (`id` varchar(32) character set utf8 collate utf8_bin NOT NULL, `time` int(11) unsigned NOT NULL, `user_id` varchar(32) character set utf8 collate utf8_bin NOT NULL, `internal_id` varchar(32) character set utf8 collate utf8_bin NOT NULL, `fullname` varchar(32) character set utf8 collate utf8_bin NOT NULL, `email` varchar(50) character set utf8 collate utf8_bin NOT NULL, `company` varchar(50) character set utf8 collate utf8_bin NOT NULL, `qualification` tinyint(1) unsigned NOT NULL, `politeness` tinyint(1) unsigned NOT NULL, `comment` varchar(400) character set utf8 collate utf8_bin NOT NULL, `ip` varchar(15) character set utf8 collate utf8_bin NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM;";
	$result = mysql_query($sql,$_link);
	if(!$result && mysql_errno() != 1050)
		return mysql_errno() . ": " . mysql_error();

	return TRUE;
}
?>