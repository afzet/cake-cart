<?php

/****************************************************************************************
* LiveZilla functions.index.inc.php // VERSION 3.1.8.4
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

function getFolderPermissions()
{
	global $LZLANG,$CONFIG;
	$message = null;
	$directories = Array(PATH_UPLOADS,PATH_INTERN_IMAGES,PATH_IMAGES,PATH_BANNER,PATH_VISITCARDS,PATH_CONFIG,PATH_USERS,PATH_GROUPS,PATH_LOG);
	foreach($directories as $key => $dir)
	{
		$result = testDirectory($dir);
			if(!$result)
				$message .= $LZLANG["index_no_write_access"] . " (" . $dir . ")<br>";
	}
	
	if(!isnull($message))
	{
		$message = "<span class=\"lz_index_error_cat\">" . $LZLANG["index_write_access"] . ":<br></span> <span class=\"lz_index_red\">" . $message . "</span><a href=\"".CONFIG_LIVEZILLA_FAQ."?lang=en&id=17#17\" class=\"lz_index_helplink\" target=\"_blank\">".$LZLANG["index_solve"]."</a>";
	}
	return str_replace($CONFIG["gl_lzid"],"*****",$message);
}

function getPhpVersion()
{
	global $LZLANG;
	$message = null;
	if(!checkPhpVersion(PHP_NEEDED_MAJOR,PHP_NEEDED_MINOR,PHP_NEEDED_BUILD))
		$message = "<span class=\"lz_index_error_cat\">PHP-Version:<br></span> <span class=\"lz_index_red\">" . str_replace("<!--version-->",PHP_NEEDED_MAJOR . "." . PHP_NEEDED_MINOR . "." . PHP_NEEDED_BUILD,$LZLANG["index_phpversion_needed"]) . "</span>";
	return $message;
}
?>
