<?php

/****************************************************************************************
* LiveZilla functions.global.inc.php // VERSION 3.1.8.4
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
	
function handleError($_errno, $_errstr, $_errfile, $_errline)
{
	if(error_reporting()!=0)
		errorLog(date("d.m.y H:i") . " ERR# " . $_errno." ".$_errstr." ".$_errfile." IN LINE ".$_errline."\r");
}

function getAvailability()
{
	return (@file_exists(FILE_SERVER_DISABLED)) ? false : true;
}

function slashesStrip($_value)
{
	if (@get_magic_quotes_gpc() == 1)
        return stripslashes($_value);
    return $_value; 
}

function getIdle()
{
	if(file_exists(FILE_SERVER_IDLE) && @filemtime(FILE_SERVER_IDLE) < (time()-15))
		@unlink(FILE_SERVER_IDLE);
	return file_exists(FILE_SERVER_IDLE);
}

function getIP($_dontmask=false)
{
	global $CONFIG;
	$ip = getServerParam("REMOTE_ADDR");
	if(!$CONFIG["gl_maskip"] || $_dontmask)
		return $ip;
	else
	{
		$parts = explode(".",$ip);
		return $parts[0].".".$parts[1].".".$parts[2].".xxx";
	}
}

function getHost()
{
	global $CONFIG;
	$ip = getIP(true);
	$host = @gethostbyaddr($ip);
	if($CONFIG["gl_maskip"])
	{
		$parts = explode(".",$ip);
		return str_replace($parts[3],"xxx",$host);
	}
	else
		return $host;
}

function getTimeDifference($_time)
{
	$_time = (time() - $_time);
	if(abs($_time) <= 5)
		$_time = 0;
	return $_time;
}

function isnull($_var)
{
	return @($_var === null || $_var === "" || $_var === 0 || $_var === "0");
}

function parseBool($_value,$_toString=true)
{
	if($_toString)
		return ($_value) ? "true" : "false";
	else
		return ($_value) ? "1" : "0";
}

function namebase($_path)
{
	$file = basename($_path);
	if (strpos($file,'\\') !== false)
	{
		$tmp = preg_split("[\\\]",$file);
		$file = $tmp[count($tmp) - 1];
		return $file;
	}
	else
		return $file;
}

function getScheme()
{
	$scheme = SCHEME_HTTP;
	if(!isnull(getServerParam("HTTPS")) && getServerParam("HTTPS") == "on")
		$scheme = SCHEME_HTTP_SECURE;
	else if(!isnull(getServerParam("SERVER_PORT")) && getServerParam("SERVER_PORT") == 443)
		$scheme = SCHEME_HTTP_SECURE;
	return $scheme;
}

function doReplacements($_toReplace)
{
	global $CONFIG,$LZLANG;
	if(!isset($LZLANG))
		languageSelect();
	
	$to_replace_nam = Array("lang","config");
	$to_replace_con = Array("lang"=>$LZLANG,"config"=>$CONFIG);
	foreach ($to_replace_nam as $nam_e)
		foreach($to_replace_con[$nam_e] as $short => $value)
			if(!is_array($value))
				$_toReplace = str_replace("<!--".$nam_e."_".$short."-->",$value,$_toReplace);
			else
				foreach($value as $subKey => $subValue)
					$_toReplace = str_replace("<!--".$nam_e."_".$subKey."-->",$subValue,$_toReplace);
	return $_toReplace;
}

function geoReplacements($_toReplace, $jsa = "")
{
	global $CONFIG,$LZLANG;
	$_toReplace = str_replace("<!--geo_url-->",CONFIG_LIVEZILLA_GEO . "?aid=" . $CONFIG["wcl_geo_tracking"],$_toReplace);
	if(!isnull(trim($CONFIG["gl_pr_ngl"])))
	{
		$jsc = "var chars = new Array(";
		$jso = "var order = new Array(";
		$chars = str_split(sha1($CONFIG["gl_pr_ngl"] . date("d"),false));
		$keys = array_keys($chars);shuffle($keys);
		foreach($keys as $key)
		{
			$jsc .= "'" . $chars[$key] . "',";
			$jso .= $key . ",";
		}
		$jsa .= $jsc . "0);\r\n";$jsa .= $jso . "0);\r\n";
		$jsa .= "while(oak.length < (chars.length-1))for(var f in order)if(order[f] == oak.length)oak += chars[f];\r\n";
	}
	$_toReplace = str_replace("<!--calcoak-->",$jsa,$_toReplace);
	return $_toReplace;
}

function processHeaderValues()
{
	if(!isnull(getServerParam("HTTP_INTERN_AUTHENTICATION_USERID")))
	{
		$_POST[POST_INTERN_AUTHENTICATION_USERID] = base64_decode(getServerParam("HTTP_INTERN_AUTHENTICATION_USERID"));
		$_POST[POST_INTERN_AUTHENTICATION_PASSWORD] = base64_decode(getServerParam("HTTP_INTERN_AUTHENTICATION_PASSWORD"));
		$_POST[POST_INTERN_FILE_TYPE] = getServerParam("HTTP_INTERN_FILE_TYPE");
		$_POST[POST_SERVER_REQUEST_TYPE] = getServerParam("HTTP_SERVER_REQUEST_TYPE");
		$_POST[POST_INTERN_SERVER_ACTION] = getServerParam("HTTP_INTERN_SERVER_ACTION");
	}
	if(!isnull(getServerParam("HTTP_ADMINISTRATE")))
		$_POST[POST_INTERN_ADMINISTRATE] = getServerParam("HTTP_ADMINISTRATE");
}

function getServerAddLink($_scheme)
{
	global $CONFIG;
	return PROTOCOL . "://" . base64_encode($_scheme . getServerParam("HTTP_HOST") . "/" . str_replace("index.php","",getServerParam("PHP_SELF"))) . "|" . base64_encode($CONFIG["gl_site_name"] . " (" . getServerParam("HTTP_HOST") .")");
}

function getInternSessIdByUserId($_userId)
{
	global $INTERNAL;
	foreach($INTERNAL as $sysId => $intern)
	{
		if($intern->UserId == $_userId)
			return $sysId;
	}
	return null;
}

function md5file($_file)
{
	global $RESPONSE;
	$md5file = @md5_file($_file);
	if(gettype($md5file) != 'boolean' && $md5file != false)
	return $md5file;
}

function getFile($_file,$data="")
{
	if(@file_exists($_file))
	{
		$handle = @fopen($_file,"r");
		if($handle)
		{
		   	$data = @fread($handle,@filesize($_file));
			@fclose ($handle);
		}
		return $data;
	}
}

function getServerParam($_serverParam)
{
	if(isset($_SERVER[$_serverParam]))
		return secPrev($_SERVER[$_serverParam]);
	else
		return null;
}

function getParam($_getParam)
{
	if(isset($_GET[$_getParam]))
		return secPrev($_GET[$_getParam]);
	else
		return null;
}

function getParams($_getParams="")
{
	foreach($_GET as $key => $value)
		if($key != "template")
			$_getParams.=((strlen($_getParams) == 0) ? $_getParams : "&") . urlencode($key) ."=" . urlencode($value);
	return $_getParams;
}

function secPrev($_val)
{
	global $GLSECPREV;
	if(isnull($_val))
		return "";
	
	if(isnull($GLSECPREV))
		$GLSECPREV = array();

	if(!isset($GLSECPREV[$_val]))
		$GLSECPREV[$_val] = preg_replace('/[<>\'\"?&\[\]\/]/', '', $_val);
	
	return $GLSECPREV[$_val];
}

function cfgFileSizeToBytes($_configValue) 
{
   $_configValue = trim($_configValue);
   $last = strtolower($_configValue{strlen($_configValue)-1});
   switch($last) 
   {
       case 'g':
           $_configValue *= 1024;
       case 'm':
           $_configValue *= 1024;
       case 'k':
           $_configValue *= 1024;
   }
   return floor($_configValue);
}

function AJAXDecode($value)
{
    return slashesStrip(trim(preg_replace('/%([0-9a-f]{2})/ie', 'chr(hexdec($1))',utf8_decode($value))));
}

function createFolder($_folder)
{
	$folders = explode("/",$_folder);
	$path = ".";
	foreach($folders as $folder)
	{
		if($folder === "" || $folder === ".")
			continue;
			
		$path.="/".$folder;
		if(!@is_dir($path))
			@mkdir($path);
	}
}

function createFile($_filename,$_content,$_recreate)
{
	if(strpos($_filename,"..") === false)
	{
		if(file_exists($_filename))
		{
			if($_recreate)
				@unlink($_filename);
			else
				return 0;
		}
		$handle = @fopen($_filename,"w");
		if(strlen($_content)>0)
			@fputs($handle,$_content);
		@fclose($handle);
		return 1;
	}
	return 0;
}

function b64dcode(&$_a,$_b)
{
	$_a = base64_decode($_a);
}

function base64UrlDecode($_input)
{
    return base64_decode(str_replace(array('_','-',','),array('=','+','/'),$_input));
}

function base64UrlEncode($_input)
{
    return str_replace(array('=','+','/'),array('_','-',','),base64_encode($_input));
}

function base64ToFile($_filename,$_content)
{
	if(file_exists($_filename))
		@unlink($_filename);
	$handle = @fopen($_filename,"wb");
	@fputs($handle,base64_decode($_content));
	@fclose($handle);
}

function fileToBase64($_filename)
{
	if(@filesize($_filename) == 0)
		return "";
	$handle = @fopen($_filename,"rb");
	$content = @fread($handle,@filesize($_filename));
	@fclose($handle);
	return base64_encode($content);
}

function getData($_int,$_gr,$_vi,$_ft)
{
	if($_int)buildInternalUsers();
	if($_gr)buildGroups();
	if($_vi)buildTracking();
	if($_ft)buildFilter();
}

function buildFilter()
{
	global $FILTERS;
	$FILTERS = new FilterSystem();
	$FILTERS->Populate();
}

function buildInternalUsers()
{
	global $CONFIG,$INTERNAL;
	require(PATH_USERS . "internal.inc.php");
	foreach($INTERN as $sysId => $internaluser)
	{
		$INTERNAL[$sysId] = new Operator($sysId,$internaluser["in_id"]);
		$INTERNAL[$sysId]->Email = $internaluser["in_email"];
		$INTERNAL[$sysId]->Webspace = $internaluser["in_websp"];
		$INTERNAL[$sysId]->Level = $internaluser["in_level"];
		$INTERNAL[$sysId]->Description = $internaluser["in_desc"];
		$INTERNAL[$sysId]->Fullname = $internaluser["in_name"];
		$INTERNAL[$sysId]->Groups = unserialize(base64_decode($internaluser["in_groups"]));
		
		array_walk($INTERNAL[$sysId]->Groups,"b64dcode");

		$INTERNAL[$sysId]->GroupsArray = $internaluser["in_groups"];
		$INTERNAL[$sysId]->PermissionSet = $internaluser["in_perms"];
		$INTERNAL[$sysId]->Status = USER_STATUS_OFFLINE;

		if(defined("SERVERSETUP") && SERVERSETUP)
			$INTERNAL[$sysId]->PredefinedMessages = loadPredefinedMessages($sysId,false);
		else
			$INTERNAL[$sysId]->PredefinedMessages = array();
		
		if(getDataSetTime($INTERNAL[$sysId]->SessionFile) > time()-$CONFIG["timeout_clients"])
		{
			$INTERNAL[$sysId]->LastActive = getDataSetTime($INTERNAL[$sysId]->SessionFile);
			$INTERNAL[$sysId]->Load();
		}

		if(file_exists($INTERNAL[$sysId]->WebcamFile) && (@filemtime($INTERNAL[$sysId]->WebcamFile) < time() - ($CONFIG["poll_frequency_clients"]+5)))
			@unlink($INTERNAL[$sysId]->WebcamFile);
	}
}

function buildGroups()
{
	global $GROUPS,$CONFIG;
	require(PATH_GROUPS . "groups.inc.php");
	foreach($GROUPS as $id => $group)
	{
		$GROUPS[$id]["gr_desc_array"] = $GROUPS[$id]["gr_desc"];
		$descriptions = unserialize(base64_decode($group["gr_desc"]));
		if(defined("DEFAULT_BROWSER_LANGUAGE") && isset($descriptions[strtoupper(DEFAULT_BROWSER_LANGUAGE)]))
			$GROUPS[$id]["gr_desc"] = base64_decode($descriptions[strtoupper(DEFAULT_BROWSER_LANGUAGE)]);
		else if(isset($descriptions[strtoupper($CONFIG["gl_default_language"])]))
			$GROUPS[$id]["gr_desc"] = base64_decode($descriptions[strtoupper($CONFIG["gl_default_language"])]);
		else if(isset($descriptions["EN"]))
			$GROUPS[$id]["gr_desc"] = base64_decode($descriptions["EN"]);
		else
			$GROUPS[$id]["gr_desc"] =  base64_decode(current($descriptions));
		$GROUPS[$id]["gr_extern"] = ($GROUPS[$id]["gr_extern"] == 1);
		$GROUPS[$id]["gr_predefined"] = loadPredefinedMessages($id,true);
	}
}

function getTargetParameters()
{
	$parameters = array("exclude"=>null,"include_group"=>null,"include_user"=>null);
	if(isset($_GET[GET_EXTERN_HIDDEN_GROUPS]))
	{
		$groups = base64UrlDecode($_GET[GET_EXTERN_HIDDEN_GROUPS]);
		if(strlen($groups) > 1)
			$parameters["exclude"] = explode("?",$groups);
		else if(isset($_GET[GET_EXTERN_GROUP]))
			$parameters["include_group"] = array(base64UrlDecode($_GET[GET_EXTERN_GROUP]));
		else if(isset($_GET[GET_EXTERN_INTERN_USER_ID]))
			$parameters["include_user"] = base64UrlDecode($_GET[GET_EXTERN_INTERN_USER_ID]);
	}
	return $parameters;
}

function operatorsAvailable($_amount = 0, $_exclude=null, $include_group=null, $include_user=null)
{
	global $CONFIG,$INTERNAL,$GROUPS;
	
	if(!DB_CONNECTION)
		return 0;
	
	getData(true,true,false,false);
	
	if(!isnull($include_user))
		$include_group = $INTERNAL[getInternSessIdByUserId($include_user)]->Groups;
		
	foreach($INTERNAL as $sysId => $internaluser)
		if($internaluser->IsExternal($GROUPS, $_exclude, $include_group) && $internaluser->Status < USER_STATUS_OFFLINE)
			$_amount++;
	return $_amount;
}

function getOperatorList()
{
	global $INTERNAL,$GROUPS;
	$array = array();
	getData(true,true,false,false);
	foreach($INTERNAL as $sysId => $internaluser)
		if($internaluser->IsExternal($GROUPS))
			$array[utf8_decode($internaluser->Fullname)] = $internaluser->Status;
	return $array;
}

function getOperators()
{
	global $INTERNAL,$GROUPS;
	$array = array();
	getData(true,true,false,false);
	foreach($INTERNAL as $sysId => $internaluser)
	{
		$internaluser->IsExternal($GROUPS);
		$array[$sysId] = $internaluser;
	}
	return $array;
}

function isValidUploadFile($_filename)
{
	global $CONFIG;
	$extensions = explode(",",str_replace("*.","",$CONFIG["wcl_upload_blocked_ext"]));
	foreach($extensions as $ext)
	{
		if(strlen($_filename) > strlen($ext) && substr($_filename,strlen($_filename)-strlen($ext),strlen($ext)) == $ext)
			return false;
	}
	return true;
}

function languageSelect()
{
	global $LZLANG,$CONFIG;
	if(!isnull($CONFIG["gl_on_def_lang"]) && file_exists(LIVEZILLA_PATH . "_language/lang".$CONFIG["gl_default_language"].".php"))
	{
		define("DEFAULT_BROWSER_LANGUAGE",$CONFIG["gl_default_language"]);
		require(LIVEZILLA_PATH . "_language/lang".$CONFIG["gl_default_language"].".php");
	}
	else if(isnull(getServerParam("HTTP_ACCEPT_LANGUAGE")) || (!isnull(getServerParam("HTTP_ACCEPT_LANGUAGE")) && strpos(getServerParam("HTTP_ACCEPT_LANGUAGE"),"..") === false))
	{
		define("DEFAULT_BROWSER_LANGUAGE",$s_browser_language = ((!isnull(getServerParam("HTTP_ACCEPT_LANGUAGE"))) ? strtolower(substr(getServerParam("HTTP_ACCEPT_LANGUAGE"),0,2)) : $CONFIG["gl_default_language"]));
		if(!isnull(getServerParam("HTTP_ACCEPT_LANGUAGE")) && strlen(getServerParam("HTTP_ACCEPT_LANGUAGE")) >= 5 && substr(getServerParam("HTTP_ACCEPT_LANGUAGE"),2,1) == "-" && file_exists(LIVEZILLA_PATH . "_language/lang". strtolower(substr(getServerParam("HTTP_ACCEPT_LANGUAGE"),0,5)) .".php"))
			require(LIVEZILLA_PATH . "_language/lang".strtolower(substr(getServerParam("HTTP_ACCEPT_LANGUAGE"),0,5)).".php");
		else if(file_exists(LIVEZILLA_PATH . "_language/lang".$s_browser_language.".php"))
			require(LIVEZILLA_PATH . "_language/lang".$s_browser_language.".php");
		else if(file_exists(LIVEZILLA_PATH . "_language/lang".$CONFIG["gl_default_language"].".php"))
			require(LIVEZILLA_PATH . "_language/lang".$CONFIG["gl_default_language"].".php");
	}
	if(!defined("DEFAULT_BROWSER_LANGUAGE"))
	{
		define("DEFAULT_BROWSER_LANGUAGE","en");
		require(LIVEZILLA_PATH . "_language/langen.php");
	}
}

function getLongPollRuntime()
{
	global $CONFIG;
	if(SAFE_MODE)
		$value = 10;
	else
	{
		$value = $CONFIG["timeout_clients"] - $CONFIG["poll_frequency_clients"] - 55;
		if(!isnull($ini = @ini_get('max_execution_time')) && $ini > $CONFIG["poll_frequency_clients"] && $ini < $value)
			$value = $ini-$CONFIG["poll_frequency_clients"];
		if($value > 20)
			$value = 20;
		if($value < 1)
			$value = 1;
	}
	return $value;
}

function checkPhpVersion($_ist,$_ond,$_ird)
{
	$array = explode(".",phpversion());
	if($array[0] >= $_ist)
	{
		if($array[0] == 5)
			return true;
		else
		{
			if($array[1] > $_ond || ($array[1] == $_ond && $array[2] >= $_ird))
				return true;
		}
		return false;
	}
	return false;
}

function formLanguages($_lang)
{
	if(strlen($_lang) == 0)
		return "";
	$array_lang = explode(",",$_lang);
	foreach($array_lang as $key => $lang)
		if($key == 0)
		{
			$_lang = strtoupper(substr(trim($lang),0,2));
			break;
		}
	return (strlen($_lang) > 0) ? $_lang : "";
}

function logit($_id,$_file=null)
{
	if(isnull($_file))
		$_file = LIVEZILLA_PATH . "_log/debug.txt";
		
	$handle = @fopen ($_file,"a+");
	@fputs($handle,$_id."\r\n");
	@fclose($handle);
}

function errorLog($_message)
{
	global $RESPONSE;
	if(defined("FILE_ERROR_LOG"))
	{
		if(file_exists(FILE_ERROR_LOG) && @filesize(FILE_ERROR_LOG) > 100000)
			@unlink(FILE_ERROR_LOG);
		$handle = @fopen (FILE_ERROR_LOG,"a+");
		if($handle)
		{
			@fputs($handle,$_message . "\r");
			@fclose($handle);
		}
		if(!isnull($RESPONSE))
		{
			if(!isset($RESPONSE->Exceptions))
				$RESPONSE->Exceptions = "";
			$RESPONSE->Exceptions .= "<val err=\"".base64_encode(trim($_message))."\" />";
		}
	}
	else
		$RESPONSE->Exceptions = "";
}

function getId($_length,$start=0)
{
	$id = md5(uniqid(rand(),1));
	if($_length != 32)
		$start = rand(0,(31-$_length));
	$id = substr($id,$start,$_length);
	return $id;
}

function createFloodFilter($_ip,$_userId)
{
	global $FILTERS;
	foreach($FILTERS->Filters as $currentFilter)
		if($currentFilter->IP == $_ip && $currentFilter->Activeipaddress == 1 && $currentFilter->Activestate == 1)
			return;
	
	$filter = new Filter(md5(uniqid(rand())));
	$filter->Creator = "SYSTEM";
	$filter->Created = time();
	$filter->Editor = "SYSTEM";
	$filter->Edited = time();
	$filter->IP = $_ip;
	$filter->Expiredate = 172800;
	$filter->Userid = $_userId;
	$filter->Reason = "Anti Flood Protection active. Your IP-Address will be saved: " . $_ip;
	$filter->Filtername = "AUTO FLOOD FILTER";
	$filter->Activestate = 1;
	$filter->Exertion = 0;
	$filter->Languages = "";
	$filter->Activeipaddress = 1;
	$filter->Activeuserid = 0;
	$filter->Activelanguage = 0;
	$filter->Save();
}

function isFlood()
{
	global $VISITOR,$FILTERS,$CONFIG;
	if(isnull($CONFIG["gl_atflt"]))
		return false;
	if(!isset($VISITOR))
		getData(false,false,true,false);

	$myurls="";
	$count = 0;
	$files = getDirectory(PATH_DATA_EXTERNAL,".");
	foreach($VISITOR as $visitor)
	{
		if(isnull($visitor->ExternalStatic))
		{
			$visitor->LoadStaticInformation();
			$visitor->ExternalStatic->Load();
			if($visitor->ExternalStatic->IP == getIP())
			{
				foreach($visitor->Browsers as $browser)
				{
					$browser->Load();
					if(!is_array($browser->History))
						continue;
					foreach($browser->History as $key => $value)
						$myurls.="\r\n(" .$browser->FirstActive. ") " . $value[1]."\r";
					if($browser->FirstActive > (time() - FLOOD_PROTECTION_TIME))
					{
						if(++$count >= FLOOD_PROTECTION_SESSIONS)
						{
							createFloodFilter($visitor->ExternalStatic->IP,$visitor->UserId);
							return true;
						}
					}
				}
			}
		}
	}
	return false;
}

function createStaticFile($_externalUser,$_resolution,$_color,$_timezone,$_lat,$_long,$_countryiso2,$_city,$_region,$_geotimezone,$_isp,$_geosspan,$_grid,$_fromCookie=false)
{
	global $CONFIG;
	if(isnull(getCookieValue("userid")))
		setCookieValue("visits",$_externalUser->ExternalStatic->Visits = 1);
	else if(!isnull(getCookieValue("userid")))
		setCookieValue("visits",$_externalUser->ExternalStatic->Visits = getCookieValue("visits")+1);

	$_externalUser->ExternalStatic->IP = getIP();
	$_externalUser->ExternalStatic->Host = getHost();
	$_externalUser->ExternalStatic->SystemInfo = ((!isnull($userAgent = getServerParam("HTTP_USER_AGENT"))) ? $userAgent : "");
	$_externalUser->ExternalStatic->Language = ((!isnull($acceptLanguage = getServerParam("HTTP_ACCEPT_LANGUAGE"))) ? $acceptLanguage : "");

	if(strlen($_externalUser->ExternalStatic->Language) > 8 || strpos($_externalUser->ExternalStatic->Language,";") !== false)
	{
		$parts = explode(";",$_externalUser->ExternalStatic->Language);
		if(count($parts) > 0)
			$_externalUser->ExternalStatic->Language = $parts[0];
		else
			$_externalUser->ExternalStatic->Language = substr($_externalUser->ExternalStatic->Language,0,8);
	}

	$_externalUser->ExternalStatic->Resolution = (is_array($_resolution) && count($_resolution) == 2) ? $_resolution[0] . " x " . $_resolution[1] : "";
	$_externalUser->ExternalStatic->Resolution .= (!isnull($_color)) ? " (" . $_color . " Bit)" : "";
	$_externalUser->ExternalStatic->GeoTimezoneOffset = getLocalTimezone($_timezone);

	if(!isnull($_geosspan))
		createSSpanFile($_geosspan);
	
	if(!isnull($CONFIG["wcl_geo_tracking"]))
	{
		if(!isnull($_lat) && base64_decode($_lat) > -180)
		{
			setCookieValue(GEO_LATITUDE,$_externalUser->ExternalStatic->GeoLatitude = base64_decode($_lat));
			setCookieValue(GEO_LONGITUDE,$_externalUser->ExternalStatic->GeoLongitude = base64_decode($_long));
			setCookieValue(GEO_COUNTRY_ISO_2,$_externalUser->ExternalStatic->GeoCountryISO2 = base64_decode($_countryiso2));
			setCookieValue(GEO_CITY,$_externalUser->ExternalStatic->GeoCity = base64_decode($_city));
			setCookieValue(GEO_REGION,$_externalUser->ExternalStatic->GeoRegion = base64_decode($_region));
			setCookieValue(GEO_TIMEZONE,$_externalUser->ExternalStatic->GeoTimezoneOffset = base64_decode($_geotimezone));
			setCookieValue(GEO_ISP,$_externalUser->ExternalStatic->GeoISP = base64_decode($_isp));
			setCookieValue("geo_data",time());
		}
		else if(isset($_lat))
		{
			$_externalUser->ExternalStatic->GeoLatitude = base64_decode($_lat);
			$_externalUser->ExternalStatic->GeoLongitude = base64_decode($_long);
		}
		else if(!isnull(getCookieValue("geo_data")))
		{
			$_externalUser->ExternalStatic->GeoLatitude = getCookieValue(GEO_LATITUDE);
			$_externalUser->ExternalStatic->GeoLongitude = getCookieValue(GEO_LONGITUDE);
			$_externalUser->ExternalStatic->GeoCountryISO2 = getCookieValue(GEO_COUNTRY_ISO_2);
			$_externalUser->ExternalStatic->GeoCity = getCookieValue(GEO_CITY);
			$_externalUser->ExternalStatic->GeoRegion = getCookieValue(GEO_REGION);
			$_externalUser->ExternalStatic->GeoTimezoneOffset = getCookieValue(GEO_TIMEZONE);
			$_externalUser->ExternalStatic->GeoISP = getCookieValue(GEO_ISP);
			$_fromCookie = true;
		}
		
		removeSSpanFile(false);
		if($_fromCookie)
			$_externalUser->ExternalStatic->GeoResultId = 6;
		else if(isSSpanFile())
		{
			if(@filemtime(FILE_SERVER_GEO_SSPAN) > (time()+CONNECTION_ERROR_SPAN))
				$_externalUser->ExternalStatic->GeoResultId = 5;
			else
				$_externalUser->ExternalStatic->GeoResultId = 4;
		}
		else
		{
			if(base64_decode($_lat) == -777)
				$_externalUser->ExternalStatic->GeoResultId = 5;
			else if(base64_decode($_lat) == -522)
				$_externalUser->ExternalStatic->GeoResultId = 2;
			else if($_grid != 4)
				$_externalUser->ExternalStatic->GeoResultId = 3;
			else
				$_externalUser->ExternalStatic->GeoResultId = $_grid;
		}

		if(strlen($_externalUser->ExternalStatic->Language) == 2 && !isnull(GEO_COUNTRY_ISO_2))
			$_externalUser->ExternalStatic->Language .= "-" . $_externalUser->ExternalStatic->GeoCountryISO2;
	}
	$_externalUser->ExternalStatic->Save();
}

function removeSSpanFile($_all)
{
	if($_all || (@file_exists(FILE_SERVER_GEO_SSPAN) && @filemtime(FILE_SERVER_GEO_SSPAN) < time()))
		@unlink(FILE_SERVER_GEO_SSPAN);
}

function isSSpanFile()
{
	return @file_exists(FILE_SERVER_GEO_SSPAN);
}

function createSSpanFile($_sspan)
{
	global $CONFIG;
	if($_sspan >= CONNECTION_ERROR_SPAN && !@file_exists(FILE_SERVER_GEO_SSPAN))
	{
		if(!isnull($CONFIG["gl_ogcm"]) && $_sspan == CONNECTION_ERROR_SPAN)
			return;
		createFile(FILE_SERVER_GEO_SSPAN,"",false);
		@touch(FILE_SERVER_GEO_SSPAN,(time()+$_sspan));
	}
}

function getLocalTimezone($_timezone,$ltz=0)
{
	$template = "%s%s%s:%s%s";
	if(isset($_timezone) && !isnull($_timezone))
	{
		$ltz = $_timezone;
		if($ltz == ceil($ltz))
		{
			if($ltz >= 0 && $ltz < 10)
				$ltz = sprintf($template,"+","0",$ltz,"0","0");
			else if($ltz < 0 && $ltz > -10)
				$ltz = sprintf($template,"-","0",$ltz*-1,"0","0");
			else if($ltz >= 10)
				$ltz = sprintf($template,"+",$ltz,"","0","0");
			else if($ltz <= -10)
				$ltz = sprintf($template,"",$ltz,"","0","0");
		}
		else
		{
			$split = explode(".",$ltz);
			$split[1] = (60 * $split[1]) / 100;
			if($ltz >= 0 && $ltz < 10)
				$ltz = sprintf($template,"+","0",$split[0],$split[1],"0");
			else if($ltz < 0 && $ltz > -10)
				$ltz = sprintf($template,"","0",$split[0],$split[1],"0");
				
			else if($ltz >= 10)
				$ltz = sprintf($template,"+",$split[0],"",$split[1],"0");
			
			else if($ltz <= -10)
				$ltz = sprintf($template,"",$split[0],"",$split[1],"0");
		}
	}
	return $ltz;
}

function isValidEmail($_email)
{
	return preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $_email);
}

function setCookieValue($_key,$_value)
{
	if(!isset($_COOKIE["livezilla"]))
		$c_array = Array();
	else
		$c_array = @unserialize(@base64_decode($_COOKIE["livezilla"]));
	if(!isset($c_array[$_key]) || (isset($c_array[$_key]) && $c_array[$_key] != $_value))
	{	
		$c_array[$_key] = $_value;
		setcookie("livezilla",($_COOKIE["livezilla"] = base64_encode(serialize($c_array))),time()+6307200);
	}
}

function getCookieValue($_key)
{
	if(isset($_COOKIE["livezilla"]))
		$c_array = @unserialize(@base64_decode($_COOKIE["livezilla"]));
	
	if(isset($c_array[$_key]))
		return $c_array[$_key];
	else
		return null;
}

function getCookies()
{
	if(isset($_COOKIE["livezilla"]))
		return @unserialize(base64_decode($_COOKIE["livezilla"]));
	else
		return Array();
}

function hashFile($_file)
{
	$enfile = md5(base64_encode(file_get_contents($_file)));
	return $enfile;
}

function mTime()
{
	$time = str_replace(".","",microtime());
	$time = explode(" " , $time);
	return $time[0];
}

function microtimeFloat($_microtime)
{
   list($usec, $sec) = explode(" ", $_microtime);
   return ((float)$usec + (float)$sec);
}

function testDirectory($_dir)
{	
	global $LZLANG,$ERRORS;
	if(!@is_dir($_dir))
		@mkdir($_dir);
	
	if(@is_dir($_dir))
	{
		$fileid = md5(uniqid(rand()));
		$handle = @fopen ($_dir . $fileid ,"a");
		@fputs($handle,$_id."\r\n");
		@fclose($handle);
		
		if(!file_exists($_dir . $fileid))
			return false;
			
		@unlink($_dir . $fileid);
		if(file_exists($_dir . $fileid))
			return false;
			
		return true;
	}
	else
		return false;
}

function sendMail($_receiver,$_sender,$_replyto,$_text,$_subject="")
{
	global $CONFIG;
	$return = "";
	if(strpos($_receiver,",") === false)
	{
		$EOL = (!isnull($CONFIG["gl_smtpauth"])) ? "\r\n" : "\n";
		$message  = $_text;
		$headers  = "From: ".$_sender.$EOL;
	    $headers .= "Reply-To: ".$_replyto.$EOL;
		$headers .= "MIME-Version: 1.0".$EOL;
		$headers .= "Content-Type: text/plain; charset=UTF-8; format=flowed".$EOL;
		$headers .= "Content-Transfer-Encoding: 8bit".$EOL;
    	$headers .= "X-Mailer: LiveZilla.net/" . VERSION.$EOL;
			
		if(!isnull($CONFIG["gl_smtpauth"]))
			$return = authMail($CONFIG["gl_smtphost"], $CONFIG["gl_smtpport"], $_receiver, $_subject, $_text, $headers, $_sender, $CONFIG["gl_smtppass"], $CONFIG["gl_smtpuser"], !isnull($CONFIG["gl_smtpssl"]));
		else
		{
			if(@mail($_receiver, $_subject, $_text, $headers))
				$return = null;
			else
				$return = "The email could not be sent using PHP mail(). Please try another Return Email Address or use SMTP.";
		}
	}
	else
	{
		$emails = explode(",",$_receiver);
		foreach($emails as $mail)
			if(!isnull($mail))
				sendMail($mail, $_sender, $_text, $_subject);
	}
	return $return;
}

function authMail($_server, $_port, $_receiver, $_subject, $_text, $_header, $_from, $_password, $_account, $_secure)
{
	$return = "\r\n\r\n";
	$break = "\r\n";
	$_text = preg_replace("/^\./","..",explode($break,$_text));
	$smtp = array(array("EHLO localhost".$break,"220,250"),array("AUTH LOGIN".$break,"334"),array(base64_encode($_account).$break,"334"),array(base64_encode($_password).$break,"235"));
	
	$smtp[] = array("MAIL FROM: <".$_from.">".$break,"250");
	$smtp[] = array("RCPT TO: <".$_receiver.">".$break,"250");
	$smtp[] = array("DATA".$break,"354");
	$smtp[] = array("Subject: ".$_subject.$break,"");
	$smtp[] = array("To: ".$_receiver.$break,"");
	
	$_header = explode($break,$_header);
	foreach($_header as $value) 
		$smtp[] = array($value.$break,"");

	$smtp[] = array($break,"");
	
	foreach($_text as $line) 
		$smtp[] = array($line.$break,"");

	$smtp[] = array(".".$break,"250");
	$smtp[] = array("QUIT".$break,"221");
	
	$secure = ($_secure) ? "ssl://" : "";

	$fp = @fsockopen($secure . $_server, $_port);
	if($fp)
	{
		$result = @fgets($fp, 1024);
		$return .= $result;
		foreach($smtp as $req)
		{
			@fputs($fp, $req[0]);
			if($req[1])
				while($result = @fgets($fp, 1024))
				{
					$return .= $result;
					if(substr($result,3,1) == " ") 
						break;
				}
		}
		@fclose($fp);
		if(substr($result,0,1) == "2")
			$return = null;
	}
	else 
		return "Cannot connect to " . $secure . $_server;
		
	return $return;
}

function setDataProvider()
{
	global $CONFIG,$DATASETS;
	$DATASETS = array();
	define("DB_PREFIX",$CONFIG["gl_db_prefix"]);
	return createDBConnector();
}

function createDBConnector()
{
	global $CONFIG,$DB_CONNECTOR,$DATASETS;
	if(!isnull($CONFIG["gl_datprov"]))
	{
		$DB_CONNECTOR = @mysql_connect($CONFIG["gl_db_host"], $CONFIG["gl_db_user"], $CONFIG["gl_db_pass"]);
		if($DB_CONNECTOR)
		{
			if(@mysql_select_db($CONFIG["gl_db_name"], $DB_CONNECTOR))
			{
				define("DB_CONNECTION",true);
				getDataSets();
				return DB_CONNECTION;
			}
		}
	}
	define("DB_CONNECTION",false);
	return DB_CONNECTION;
}

function queryDB($_log,$_sql)
{
	global $CONFIG,$DB_CONNECTOR;
	$res = @mysql_query($_sql, $DB_CONNECTOR);
	return $res;
}

function unloadDataProvider()
{
	global $DB_CONNECTOR;
	if($DB_CONNECTOR)
		@mysql_close($DB_CONNECTOR);
}

function getDataSets()
{
	global $DATASETS;
	$DATASETS = array();
	$result = queryDB(true,"SELECT `file`,`time`,`size` FROM `".DB_PREFIX.DATABASE_DATA."`;");
	while($row = @mysql_fetch_array($result, MYSQL_BOTH))
	{
		$DATASETS[$row["file"]] = new DataSet();
		$DATASETS[$row["file"]]->LastActive = $row["time"];
		$DATASETS[$row["file"]]->Size = $row["size"];
	}
}

function runPeriodicJobs($_config)
{
	if(rand(0,45) == 1)
	{
		$timeouts = array($_config["poll_frequency_clients"] * 10,86400,DATA_LIFETIME);
		queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_DATA."`  WHERE `time` < " . mysql_real_escape_string(time()-$timeouts[2]));
		queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_CHATS."` WHERE `html`=0 AND `time` < " . mysql_real_escape_string(time()-$timeouts[2]));
		queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_POSTS."` WHERE `time` < " . mysql_real_escape_string(time()-$timeouts[2]));
		queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_POSTS."` WHERE `persistent` = '0' AND `time` < " . mysql_real_escape_string(time()-$timeouts[1]));
		queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_ROOMS."` WHERE `last_active` < " . mysql_real_escape_string(time()-$timeouts[0]));
		queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_POSTS."` WHERE `received` = '1' AND `time` < " . mysql_real_escape_string(time()-$timeouts[0]));

		if(!isnull($_config["gl_log"]))
			queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_CHATS."` WHERE `time` < " . mysql_real_escape_string(time()-$_config["gl_rm_logs_time"]));
			
		if($result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_RESOURCES."` WHERE `discarded`=1 AND `type` > 2 AND `edited` < " . mysql_real_escape_string(time()-$timeouts[2])));
			while($row = mysql_fetch_array($result, MYSQL_BOTH))
			{
				$resultb = queryDB(true,"SELECT count(value) as linked FROM `".DB_PREFIX.DATABASE_RESOURCES."` WHERE `value`='". mysql_real_escape_string($row["value"])."';");
				$rowb = mysql_fetch_array($resultb, MYSQL_BOTH);
				if($rowb["linked"] == 1)
					@unlink(PATH_UPLOADS . $row["value"]);
			}
		queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_RESOURCES."` WHERE `discarded`=1 AND `edited` < " . mysql_real_escape_string(time()-$timeouts[2]));
	}
	
	if(rand(0,5) == 1)
	{
		$result = queryDB(true,"SELECT `plain`,`email`,`chat_id` FROM `".DB_PREFIX.DATABASE_CHATS."` WHERE `endtime` > '0' AND `closed` > '0' AND `transcript_sent` = '0';");
		while($row = mysql_fetch_array($result, MYSQL_BOTH))
		{
			if(!isnull($_config["gl_soct"]))
				sendMail($row["email"],$_config["gl_mail_sender"],$_config["gl_mail_sender"],$row["plain"],$_config["gl_site_name"] . " Chat Transcript");
			if(!isnull($_config["gl_scct"]))
				sendMail($_config["gl_scct"],$_config["gl_mail_sender"],$_config["gl_mail_sender"],$row["plain"],$_config["gl_site_name"] . " Chat Transcript");
			queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_CHATS."` SET `transcript_sent` = '1' WHERE `chat_id`='". mysql_real_escape_string($row["chat_id"])."'");
		}
	}
}

function getDataSetTime($_file,$_read=false)
{
	global $DATASETS;
	if(LIVEZILLA_PATH != "./")
		$_file = str_replace(LIVEZILLA_PATH, "./", $_file);
	if(!$_read)
	{
		$files = array();
		if(!isset($DATASETS[$_file]))
			return -1;
		return $DATASETS[$_file]->LastActive;
	}
	else
	{
		if($result = queryDB(true,"SELECT `time` FROM `".DB_PREFIX.DATABASE_DATA."` WHERE `file` = '".mysql_real_escape_string($_file)."' LIMIT 1"))
		{
			$row = mysql_fetch_array($result, MYSQL_BOTH);
			return $row[0];
		}
		return -1;
	}
}

function getDataSet($_file)
{
	if(LIVEZILLA_PATH != "./")
		$_file = str_replace(LIVEZILLA_PATH, "./", $_file);
	if($result = queryDB(true,"SELECT `data` FROM `".DB_PREFIX.DATABASE_DATA."` WHERE `file` = '".mysql_real_escape_string($_file)."' LIMIT 1"))
	{
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		return @unserialize($row[0]);
	}
}

function unlinkDataSet($_file)
{
	queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_DATA."` WHERE `file` = '".mysql_real_escape_string($_file)."'");
}

function dataSetExists($_file)
{
	global $DATASETS;
	return isset($DATASETS[$_file]);
}

function touchDataSet($_file)
{
	queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_DATA."` SET `time` = '".mysql_real_escape_string(time())."' WHERE `file` = '".mysql_real_escape_string($_file)."'");
}

function getResource($_id)
{
	if($result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_RESOURCES."` WHERE `id`='".mysql_real_escape_string($_id)."' LIMIT 1;"))
		if($row = mysql_fetch_array($result, MYSQL_BOTH))
			return $row;
	return null;
}

function writeDataSet($_file,$_data)
{
	$serdat = serialize($_data);
	$result = queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_DATA."` SET `data`='".mysql_real_escape_string($serdat)."',`time`='".mysql_real_escape_string(time())."',`size`='".mysql_real_escape_string(strlen($serdat))."' WHERE `file`='".mysql_real_escape_string($_file)."' LIMIT 1;");
	if(mysql_affected_rows() == 0)
		queryDB(false,"INSERT INTO `".DB_PREFIX.DATABASE_DATA."` (`file`,`time`,`data`,`size`) VALUES ('".mysql_real_escape_string($_file)."',".mysql_real_escape_string(time()).",'".mysql_real_escape_string(serialize($_data))."','".mysql_real_escape_string(strlen($serdat))."');");
}

function writePost($_post)
{
	queryDB(false,"INSERT INTO `".DB_PREFIX.DATABASE_POSTS."` (`id`,`time`,`micro`,`sender`,`receiver`,`receiver_group`,`text`,`received`,`persistent`) VALUES ('".mysql_real_escape_string($_post->Id)."',".mysql_real_escape_string($_post->Created).",".mysql_real_escape_string(mTime()).",'".mysql_real_escape_string($_post->Sender)."','".mysql_real_escape_string($_post->Receiver)."','".mysql_real_escape_string($_post->ReceiverGroup)."','".mysql_real_escape_string($_post->Text)."','0','".mysql_real_escape_string(parseBool($_post->Persistent,false))."');");
}

function markPostReceived($_id)
{
	queryDB(false,"UPDATE `".DB_PREFIX.DATABASE_POSTS."` SET `received`='1',`persistent`='0' WHERE `id`='".mysql_real_escape_string($_id)."';");
}

function getPosts($_receiver)
{
	$posts = array();
	if($result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_POSTS."` WHERE `receiver`='".mysql_real_escape_string($_receiver)."' AND `received`='0' ORDER BY `time` ASC, `micro` ASC;"))
		while($row = mysql_fetch_array($result, MYSQL_BOTH))
			$posts[] = $row;
	return $posts;
}

function getDataSetSize($_file)
{
	global $DATASETS;
	return $DATASETS[$_file]->Size;
}

function renameDataSet($_file,$_new)
{
	queryDB(true,"UPDATE `".DB_PREFIX.DATABASE_DATA."` SET `file`='".mysql_real_escape_string($_new)."' WHERE `file`='".mysql_real_escape_string($_file)."';");
}

function deleteDirectory($_dir)
{
	queryDB(true,"DELETE FROM `".DB_PREFIX.DATABASE_DATA."` WHERE `file` LIKE '".mysql_real_escape_string($_dir)."%';");
	return false;
}

function getDirectory($_dir,$_oddout,$_ignoreSource=false)
{
	global $DATASETS;
	$files = array();
	if($_ignoreSource)
	{
		if(!@is_dir($_dir))
			return $files;
		$handle=@opendir($_dir);
		
		while ($filename = @readdir ($handle)) 
		   	if ($filename != "." && $filename != ".." && ($_oddout == false || !stristr($filename,$_oddout)))
				if($_oddout != "." || ($_oddout == "." && @is_dir($_dir . "/" . $filename)))
		       		$files[]=$filename;
					
		@closedir($handle);
	}
	else
	{
		foreach($DATASETS as $file => $ds)
		{
			if(strpos($file,$_dir) !== false && substr($file,0,strlen($_dir)) == $_dir)
			{
				$parts = explode("/",str_replace($_dir,"",$file));
				if(count($parts) > 0)
					if(!in_array($parts[0],$files))
						$files[]=$parts[0];
			}
		}
	}
	return $files;
}

function processResource($_userId,$_resId,$_value,$_type,$_title,$_disc,$_parentId,$_rank,$_size=0)
{
	if($_size == 0)
		$_size = strlen($_title);
	$result = queryDB(true,"SELECT `id` FROM `".DB_PREFIX.DATABASE_RESOURCES."` WHERE `id`='".mysql_real_escape_string($_resId)."'");
	if(mysql_num_rows($result) == 0)
		queryDB(true,$result = "INSERT INTO `".DB_PREFIX.DATABASE_RESOURCES."` (`id`,`owner`,`editor`,`value`,`edited`,`title`,`created`,`type`,`discarded`,`parentid`,`rank`,`size`) VALUES ('".mysql_real_escape_string($_resId)."','".mysql_real_escape_string($_userId)."','".mysql_real_escape_string($_userId)."','".mysql_real_escape_string($_value)."','".mysql_real_escape_string(time())."','".mysql_real_escape_string($_title)."','".mysql_real_escape_string(time())."','".mysql_real_escape_string($_type)."','0','".mysql_real_escape_string($_parentId)."','".mysql_real_escape_string($_rank)."','".mysql_real_escape_string($_size)."')");
	else
		queryDB(true,$result = "UPDATE `".DB_PREFIX.DATABASE_RESOURCES."` SET `value`='".mysql_real_escape_string($_value)."',`editor`='".mysql_real_escape_string($_userId)."',`title`='".mysql_real_escape_string($_title)."',`edited`='".mysql_real_escape_string(time())."',`discarded`='".mysql_real_escape_string(parseBool($_disc,false))."',`parentid`='".mysql_real_escape_string($_parentId)."',`rank`='".mysql_real_escape_string($_rank)."',`size`='".mysql_real_escape_string($_size)."' WHERE id='".mysql_real_escape_string($_resId)."' LIMIT 1");
}

function buildTracking()
{
	global $VISITOR,$CONFIG,$DATASETS;
	$VISITOR = array();
	$outdatedVisitors = array();
	$itarray = array_keys($DATASETS);
	foreach($itarray as $file)
	{
		$dataset = $DATASETS[$file];
		if(strpos($file,PATH_DATA_EXTERNAL) !== false && substr($file,0,strlen(PATH_DATA_EXTERNAL)) == PATH_DATA_EXTERNAL)
		{
			$userid = substr(str_replace(PATH_DATA_EXTERNAL,"",$file),0,USER_ID_LENGTH);
			$browsers = getDirectory(PATH_DATA_EXTERNAL . $userid . "/b/",".");
			if(count($browsers) > 0)
			{
				foreach($browsers as $browserid)
				{
					$browser = new ExternalBrowser($browserid,$userid);
					$chat = new ExternalChat($userid,$browserid);
					
					if(!isset($VISITOR[$userid]))
						$VISITOR[$userid] = new UserExternal($userid);
					
					if(($bStime = getDataSetTime($browser->SessionFile)) != -1)
					{
						if($bStime < time()-$CONFIG["timeout_track"])
						{
							$browser->Destroy();
							continue;
						}
						$VISITOR[$userid]->Browsers[$browserid] = $browser;
					}
					else if(($cStime = getDataSetTime($chat->SessionFile)) != -1)
					{
						$chat->Load();
						if($cStime < time()-$CONFIG["timeout_clients"])
						{
							$chat->Destroy();
							continue;
						}
						
						if(isnull($chat->FirstActive))
							$chat->FirstActive = time();
						$chat->History[0] = array($chat->FirstActive,LIVEZILLA_URL . FILE_CHAT,$chat->Code,true);
						$VISITOR[$userid]->Browsers[$browserid] = $chat;
					}
					else
					{
						$browser->Destroy();
						$chat->Destroy();
					}
				}
			}
			else
			{
				$outdatedVisitors[] = $userid;
			}
		}
	}
	foreach($outdatedVisitors as $folder)
		deleteDirectory(PATH_DATA_EXTERNAL . $folder);
}

function createFileBaseFolders($_owner,$_internal)
{
	if($_internal)
	{
		processResource($_owner,3,"%%_Files_%%",0,"%%_Files_%%",0,1,1);
		processResource($_owner,4,"%%_Internal_%%",0,"%%_Internal_%%",0,3,2);
	}
	else
	{
		processResource($_owner,3,"%%_Files_%%",0,"%%_Files_%%",0,1,1);
		processResource($_owner,5,"%%_External_%%",0,"%%_External_%%",0,3,2);
	}
}

function loadPredefinedMessages($_id,$_group)
{
	$predefined = array();
	if(DB_CONNECTION)
	{
		$wStatement = ($_group) ? "group_id" : "internal_id";
		$result = queryDB(true,"SELECT * FROM `".DB_PREFIX.DATABASE_PREDEFINED."` WHERE `".$wStatement."`='".mysql_real_escape_string($_id)."'");
		if($result)
			while($row = mysql_fetch_array($result, MYSQL_BOTH))
				$predefined[] = new PredefinedMessage($row);
	}
	return $predefined;
}
?>
