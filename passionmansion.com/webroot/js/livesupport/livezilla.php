<?php

/****************************************************************************************
* LiveZilla livezilla.php // VERSION 3.1.8.4
* 
* Copyright 2010 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors. It is strongly 
* recommended to desist from editing this file.
* 
***************************************************************************************/ 

define("ACCESSID",md5(microtime()));
define("IN_LIVEZILLA",true);
if(!defined("LIVEZILLA_PATH"))
	define("LIVEZILLA_PATH","./");
	
@ini_set('session.use_cookies', '0');
@error_reporting(E_ALL);

require(LIVEZILLA_PATH . "_config/config.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.files.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.protocol.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.dynamic.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.global.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.external.inc.php");
require(LIVEZILLA_PATH . "_lib/objects.external.inc.php");
require(LIVEZILLA_PATH . "_lib/objects.global.users.inc.php");

define("LIVEZILLA_URL",getScheme() . $_SERVER["HTTP_HOST"] . str_replace(FILE_CHAT,"",$_SERVER["PHP_SELF"]));

setDataProvider();
@set_time_limit($CONFIG["timeout_clients"]);
if(!isset($_GET["file"]))
	@set_error_handler("handleError");

$browserId = getId(USER_ID_LENGTH);
define("SESSION",getSessionId());
header("Content-Type: text/html; charset=utf-8");
languageSelect();
getData(false,false,false,true);

if((isset($_POST["company"]) && !isnull($_POST["company"])) || (isset($_POST["email"]) && !isnull($_POST["email"])) || (isset($_POST["name"]) && !isnull($_POST["name"])) || (isset($_POST["text"]) && !isnull($_POST["text"])))
	exit(createFloodFilter(getIP(),null));

if(!isset($_GET[GET_EXTERN_TEMPLATE]))
{
	define("IS_FILTERED",$FILTERS->Match(getIP(),formLanguages(((getServerParam("HTTP_ACCEPT_LANGUAGE") != null) ? getServerParam("HTTP_ACCEPT_LANGUAGE") : "")),SESSION));
	$html = getFile(TEMPLATE_HTML_EXTERN);
	$html = str_replace("<!--extern_script-->",getFile(TEMPLATE_SCRIPT_EXTERN).getFile(TEMPLATE_SCRIPT_DATA).getFile(TEMPLATE_SCRIPT_CHAT).getFile(TEMPLATE_SCRIPT_FRAME),$html);
	$html = str_replace("<!--connector_script-->",getFile(TEMPLATE_SCRIPT_CONNECTOR),$html);
	$html = str_replace("<!--group_script-->",getFile(TEMPLATE_SCRIPT_GROUPS),$html);
	$html = str_replace("<!--global_script-->",getFile(TEMPLATE_SCRIPT_GLOBAL),$html);
	$html = str_replace("<!--browser_id-->",$browserId,$html);
	$html = str_replace("<!--extern_timeout-->",$CONFIG["timeout_clients"],$html);
	$html = str_replace("<!--extern_frequency-->",$CONFIG["poll_frequency_clients"],$html);
	$html = str_replace("<!--cbcd-->",parseBool($CONFIG["gl_cbcd"]),$html);
	$html = str_replace("<!--bookmark_name-->",base64_encode($CONFIG["gl_site_name"]),$html);
	$html = str_replace("<!--user_id-->",SESSION,$html);
	$html = str_replace("<!--connection_error_span-->",CONNECTION_ERROR_SPAN,$html);
	$html = replaceLoginDetails($html);
	$html = geoReplacements($html);
	$html = str_replace("<!--requested_intern_userid-->",((isset($_GET[GET_EXTERN_INTERN_USER_ID]) && !isnull($_GET[GET_EXTERN_INTERN_USER_ID])) ? (base64UrlDecode($_GET[GET_EXTERN_INTERN_USER_ID])):""),$html);
	$html = str_replace("<!--geo_url-->",CONFIG_LIVEZILLA_GEO . "?aid=" . $CONFIG["wcl_geo_tracking"],$html);
	$html = str_replace("<!--geo_resolute-->",parseBool(!isSSpanFile() && !isnull($CONFIG["wcl_geo_tracking"]) && !(getCookieValue("geo_data") != null && getCookieValue("geo_data") > (time()-2592000))),$html);
	$html = str_replace("<!--area_code-->",((isset($_GET[GET_TRACK_SPECIAL_AREA_CODE])) ? "&code=" . $_GET[GET_TRACK_SPECIAL_AREA_CODE] : ""),$html);
	$html = str_replace("<!--template_message_intern-->",base64_encode(getFile(TEMPLATE_HTML_MESSAGE_INTERN)),$html);
	$html = str_replace("<!--template_message_extern-->",base64_encode(getFile(TEMPLATE_HTML_MESSAGE_EXTERN)),$html);
	$html = str_replace("<!--template_message_add-->",base64_encode(getFile(TEMPLATE_HTML_MESSAGE_ADD)),$html);
	$html = str_replace("<!--template_message_add_alt-->",base64_encode(getFile(TEMPLATE_HTML_MESSAGE_ADD_ALTERNATE)),$html);
	$html = str_replace("<!--direct_login-->",parseBool((isset($_GET[GET_EXTERN_USER_NAME]) && !isset($_GET[GET_EXTERN_RESET]) && !isnull($_GET[GET_EXTERN_USER_NAME]))),$html);
	$html = str_replace("<!--is_ie-->",parseBool((!isnull(getServerParam('HTTP_USER_AGENT')) && (strpos(getServerParam('HTTP_USER_AGENT'), 'MSIE') !== false))),$html);
	$html = str_replace("<!--setup_error-->",base64_encode(buildLoginErrorField()),$html);
	$html = str_replace("<!--offline_message_mode-->",$CONFIG["gl_om_mode"],$html);
	$html = str_replace("<!--offline_message_http-->",$CONFIG["gl_om_http"],$html);
	$html = str_replace("<!--offline_message_pre_chat-->",parseBool($CONFIG["gl_no_om_sp"]==1),$html);
	$html = str_replace("<!--lang_client_queue_message-->",(($CONFIG["gl_sho_qu_inf"]==1)?$LZLANG["client_queue_message"]:$LZLANG["client_ints_are_busy"]),$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.3.2.login.1.0")
{
	getData(true,false,false,false);
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	$html = (isset($CONFIG["gl_site_name"])) ? str_replace("<!--config_name-->",$CONFIG["gl_site_name"],$html) : str_replace("<!--config_name-->","LiveZilla",$html);
	$html = replaceLoginDetails($html);
	$html = str_replace("<!--alert-->",str_replace("<!--server-->",LIVEZILLA_URL,getFile(TEMPLATE_SCRIPT_ALERT)),$html);
	$html = str_replace("<!--info_text-->",$CONFIG["gl_info"],$html);
	$html = str_replace("<!--login_trap-->",getFile(TEMPLATE_LOGIN_TRAP),$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.3.2.login.0.0")
{
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	$html = str_replace("<!--button_message-->",($CONFIG["gl_no_om_sp"]) ? "" : getFile(TEMPLATE_HTML_BUTTON_MESSAGE),$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.3.2.mail")
{
	getData(false,true,false,false);
	$groupbuilder = new GroupBuilder(NULL,$GROUPS,NULL);
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	
	if(isset($_POST["login_name"]) && $_POST["login_name"] != null)
		setCookieValue("login_name",$_POST["login_name"]);
	if(isset($_POST["login_email"]) && $_POST["login_email"] != null)
		setCookieValue("login_email",$_POST["login_email"]);
	if(isset($_POST["login_company"]) && $_POST["login_company"] != null)
		setCookieValue("login_company",$_POST["login_company"]);
	
	$html = str_replace("<!--alert-->",str_replace("<!--server-->",LIVEZILLA_URL,getFile(TEMPLATE_SCRIPT_ALERT)),$html);
	$html = replaceLoginDetails($html);
	$html = str_replace("<!--groups-->",$groupbuilder->GetHTML(),$html);
	$html = str_replace("<!--login_trap-->",getFile(TEMPLATE_LOGIN_TRAP),$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.1.1")
{
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	if(isset($_GET[GET_EXTERN_USER_HEADER]) && !isnull($_GET[GET_EXTERN_USER_HEADER]))
		$html = str_replace("<!--logo-->","<img src=\"".base64UrlDecode($_GET[GET_EXTERN_USER_HEADER])."\" alt=\"\" border=\"0\"><br>",$html);
	else
		$html = str_replace("<!--logo-->",((file_exists(FILE_CARRIERLOGO)) ? "<img src=\"".FILE_CARRIERLOGO."\" alt=\"\" border=\"0\"><br>" : ""),$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.3.2.chat.0.0")
{
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	$html = str_replace("<!--FU_HIDDEN-->",((!$CONFIG["gl_ex_fu"]) ? " style=\"display:none;\"" : ""),$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.3.2.chat.1.0")
{
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	if(isset($_POST[POST_EXTERN_USER_USERID]))
	{
		$externalUser = new UserExternal($_POST[POST_EXTERN_USER_USERID]);
		$externalChat = new ExternalChat($externalUser->UserId,$_POST[POST_EXTERN_USER_BROWSERID]);
		$externalChat->Load();

		if(isset($_FILES["userfile"]) && $externalUser->StoreFile($_POST[POST_EXTERN_USER_BROWSERID],$externalChat->DesiredChatPartner,$externalChat->Fullname))
			$html = str_replace("<!--response-->","top.lz_chat_file_ready();",$html);
		else if(isset($_FILES['userfile']))
			$html = str_replace("<!--response-->","top.lz_chat_file_error(2);",$html);
		else
			$html = str_replace("<!--response-->","",$html);
	}
	else if(isset($_GET["file"]))
		$html = str_replace("<!--response-->","top.lz_chat_file_error(2);",$html);
	else
		$html = str_replace("<!--response-->","",$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.3.2.chat.2.0")
{
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	$rate = new RatingGenerator();
	$html = str_replace("<!--rate_1-->",$rate->Fields[0],$html);
	$html = str_replace("<!--rate_2-->",$rate->Fields[1],$html);
	$html = str_replace("<!--rate_3-->",$rate->Fields[2],$html);
	$html = str_replace("<!--rate_4-->",$rate->Fields[3],$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.3.2.chat.4.0")
{
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	$html = str_replace("<!--alert-->",str_replace("<!--server-->",LIVEZILLA_URL,getFile(TEMPLATE_SCRIPT_ALERT)),$html);
}
else if(isset($_GET[GET_EXTERN_TEMPLATE]) && $_GET[GET_EXTERN_TEMPLATE] == "lz_chat_frame.4.1")
{
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");
	$html = str_replace("<!--param-->",$CONFIG["gl_c_param"],$html);
}
else
	$html = getFile(PATH_FRAMES.$_GET[GET_EXTERN_TEMPLATE].".tpl");

$html = str_replace("<!--server-->",".",$html);
$html = str_replace("<!--url_get_params-->",getParams(),$html);
unloadDataProvider();
exit(doReplacements($html));
?>
