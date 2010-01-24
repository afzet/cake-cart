<?php

/****************************************************************************************
* LiveZilla objects.global.users.inc.php // VERSION 3.1.8.4
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
	
require(LIVEZILLA_PATH . "_lib/objects.global.inc.php");
class User
{
	var $IP;
	var $SessId;
	var $UserId;
	var $SystemId;
	var $Messages = array();
	var $Status = USER_STATUS_OFFLINE;
	var $Type;
	var $Folder;
	var $SessionFile;
	var $FirstActive;
	var $LastActive;
	var $Fullname;
	var $Company;
	var $Email;
	var $Typing = false;

	function User($_userid)
   	{
		$this->UserId = $_userid;
   	}
	
	function GetPosts()
	{
		$messageFileCount = 0;
		$rows = getPosts($this->SystemId);
		$posts = array();
		foreach($rows as $row)
		{
			array_push($posts,new Post($row));
			if(++$messageFileCount >= 10)
				break;
		}
		return $posts;
	}
	
	function AppendFromCookies()
	{
		if(defined("CALLER_TYPE") && CALLER_TYPE != CALLER_TYPE_INTERNAL)
		{
			if(!isnull(getCookieValue("login_email")))
				$this->Email = (getCookieValue("login_email"));
			if(!isnull(getCookieValue("login_name")))
				$this->Fullname = (getCookieValue("login_name"));
			if(!isnull(getCookieValue("login_company")))
				$this->Company = (getCookieValue("login_company"));
		}
	}
	
	function Save()
	{
		$dataProvider = new DataProvider($this->SessionFile);
		$dataProvider->Save($this->GetData());
	}
	
	function KeepAlive()
	{
		if(dataSetExists($this->SessionFile))
			touchDataSet($this->SessionFile);
		else
			$this->Save();
	}
	
	function Destroy()
	{
		unlinkDataSet($this->SessionFile);
	}
}

class Operator extends User
{
	var $Level = 0;
	var $Webspace = 0;
	var $LoginId;
	var $Password;
	var $PasswordFile;
	var $PasswordFileTXT;
	var $Description;
	var $WebcamFile;
	var $PictureFile;
	var $LCAFile;
	var $VisitcardFile;
	var $ServerSetup = false;
	var $Authenticated = false;
	var $VisitorFileSizes;
	var $VisitorStaticReload;
	var $ExternalChats;
	var $PermissionSet;
	var $Groups;
	var $GroupsArray;
	var $PredefinedMessages;
	var $InExternalGroup;

	function Operator($_sessid,$_userid)
   	{
		$this->SessId = $this->SystemId = $_sessid;
		$this->UserId = $_userid;
		$this->ExternalChats = array();
		$this->Folder = PATH_DATA_INTERNAL . $this->SessId . "/";
		$this->WebcamFile = PATH_INTERN_IMAGES . md5($this->UserId) . FILE_EXTENSION_WEBCAM_PICTURE;
		$this->PictureFile = PATH_INTERN_IMAGES . md5($this->UserId) . FILE_EXTENSION_PROFILE_PICTURE;
		$this->VisitcardFile = PATH_VISITCARDS . $this->UserId . FILE_EXTENSION_VISITCARD;
		$this->PasswordFile = PATH_USERS . $this->SessId . FILE_EXTENSION_PASSWORD;
		$this->PasswordFileTXT = PATH_USERS . $this->SessId . FILE_EXTENSION_PASSWORD_TXT;
		$this->ChangePasswordFile = PATH_USERS . $this->SessId . FILE_EXTENSION_CHANGE_PASSWORD;
		$this->Type = USER_TYPE_INTERN;
		$this->SessionFile = $this->Folder . $this->SessId . "." . EX_INTERN_SESSION;
		$this->LCAFile = PATH_DATA_INTERNAL . $this->SessId . FILE_EXTENSION_LAST_CHAT_ALLOCATION;
		$this->VisitorFileSizes = array();
		$this->VisitorStaticReload = array();
   	}
	
	function Load()
	{
		$dataProvider = new DataProvider($this->SessionFile);
		$dataProvider->Load();
		$this->LoginId = $dataProvider->Result["s_login_id"];
		$this->FirstActive = $dataProvider->Result["s_first_active"];
		$this->Password = $dataProvider->Result["s_password"];
		$this->Status = $dataProvider->Result["s_status"];
		$this->Level = $dataProvider->Result["s_level"];
		$this->IP = $dataProvider->Result["s_ip"];
		$this->Typing = $dataProvider->Result["s_typing"];
		$this->VisitorFileSizes = $dataProvider->Result["s_vi_file_sizes"];
		$this->LastActive = getDataSetTime($this->SessionFile);
	}
	
	function GetData()
	{
		$data = Array();
		$data["s_login_id"] = $this->LoginId;
		$data["s_first_active"] = $this->FirstActive;
		$data["s_password"] = $this->Password;
		$data["s_status"] = $this->Status;
		$data["s_level"] = $this->Level;
		$data["s_ip"] = $this->IP;
		$data["s_typing"] = $this->Typing;
		$data["s_vi_file_sizes"] = $this->VisitorFileSizes;
		return $data;
	}
	
	function GetLastChatAllocation()
	{
		$dataProvider = new DataProvider($this->LCAFile);
		$dataProvider->Load();
		if($dataProvider->Result != null && isset($dataProvider->Result["s_lca"]))
			return $dataProvider->Result["s_lca"];
		else
			return 0;
	}
	
	function SetLastChatAllocation()
	{
		$dataProvider = new DataProvider($this->LCAFile);
		$dataProvider->Save(Array("s_lca"=>time()),true);
	}

	function GetExternalObjects()
	{
		$actionfiles = getDirectory($this->Folder,false);
		sort($actionfiles);
		$chat_hash = "";
		foreach($actionfiles as $index => $file)
		{
			if(strpos($file, "." . EX_CHAT_OPEN) !== false)
			{
				$chat = new Chat($this->Folder . $file);
				$chat->IsActivated(null);
				$this->ExternalChats[$chat->ExternalUser->SystemId] = $chat;
			}
			else if(strpos($file, "." . EX_FILE_UPLOAD_REQUEST) !== false)
			{
				$request = new FileUploadRequest(str_replace(".".EX_FILE_UPLOAD_REQUEST,"",$file),$this->SessId);
				$request->Load();
				
				if(isset($chat) && isset($this->ExternalChats[$chat->ExternalUser->SystemId]))
				{
					if($this->ExternalChats[$chat->ExternalUser->SystemId]->Activated != CHAT_STATUS_ACTIVE)
					{
						$request->Destroy();
					}
					else
					{
						$this->ExternalChats[$chat->ExternalUser->SystemId]->FileUploadRequest = $request;
					}
				}
				else
				{
					$request->Destroy();
				}
			}
		}
	}
	
	function IsExternal($_groupList, $_exclude=null, $_include=null)
	{
		foreach($this->Groups as $groupid)
			if($_groupList[$groupid]["gr_extern"] && !(!isnull($_exclude) && in_array($groupid,$_exclude)) && !(!isnull($_include) && !in_array($groupid,$_include)))
				return $this->InExternalGroup=true;
		return $this->InExternalGroup=false;
	}
	
	function GetExternalChatAmount($amount=0)
	{
		$actionfiles = getDirectory($this->Folder,false);
		foreach($actionfiles as $index => $file)
		{
			if(strpos($file, "." . EX_CHAT_OPEN) !== false)
				$amount++;
		}
		return $amount;
	}
	
	function SetStaticCaller()
	{
		define("CALLER_SYSTEM_ID",$this->SystemId);
	}
	
	function LoadPassword()
	{
		$this->Password = null;
		if(@file_exists($this->PasswordFile))
		{
			require($this->PasswordFile);
			$this->Password = $passwd;
		}
		else if(@file_exists($this->PasswordFileTXT))
		{
			$data = getFile($this->PasswordFileTXT);
			$this->Password = $data;
		}
		return $this->Password;
	}
	
	function ChangePassword($_password)
	{
		createFile($this->PasswordFile,"<?php \$passwd=\"".md5($_password)."\"; ?>",true);
		if(@file_exists($this->ChangePasswordFile))
			@unlink($this->ChangePasswordFile);
		if(@file_exists($this->PasswordFileTXT))
			@unlink($this->PasswordFileTXT);
	}
	
	function IsPasswordChangeNeeded()
	{
		return @file_exists($this->ChangePasswordFile);
	}
	
	function SetPasswordChangeNeeded($_needed)
	{
		if($_needed)
			createFile($this->ChangePasswordFile,"",false);
		else if(@file_exists($this->ChangePasswordFile))
			@unlink($this->ChangePasswordFile);
	}
	
	function GetPermission($_type)
	{
		return substr($this->PermissionSet,$_type,1);
	}
	
	function GetOperatorPictureFile()
	{
		if(file_exists($this->PictureFile))
			return $this->PictureFile;
		else
			return PATH_INTERN_IMAGES . "nopic" . FILE_EXTENSION_PROFILE_PICTURE;
	}

	function GetLoginReply($_extern,$_time)
	{
		return "<login>\r\n<login_return group=\"".base64_encode($this->GroupsArray)."\" name=\"".base64_encode($this->Fullname)."\" loginid=\"".base64_encode($this->LoginId)."\" level=\"".base64_encode($this->Level)."\" sess=\"".base64_encode($this->SystemId)."\" extern=\"".base64_encode($_extern)."\" timediff=\"".base64_encode($_time)."\" perms=\"".base64_encode($this->PermissionSet)."\" sm=\"".base64_encode(SAFE_MODE)."\" /></login>";
	}
}

class UserExternal extends User
{
	var $Browsers;
	var $ExternalStatic;
	var $Response;
	var $IsChat = false;
	var $ActiveChatRequest;
	
	function UserExternal($_userid)
   	{
		$this->Browsers = Array();
		$this->UserId = $_userid;
		$this->Folder = PATH_DATA_EXTERNAL . $this->UserId . "/";
   	}
	
	function SaveTicket($_group)
	{
		$ticket = new UserTicket(getTicketId(),true);
		$ticket->IP = getIP();
		
		setCookieValue("login_name",AJAXDecode($_POST[POST_EXTERN_USER_NAME]));
		setCookieValue("login_email",AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]));
		setCookieValue("login_company",AJAXDecode($_POST[POST_EXTERN_USER_COMPANY]));

		if(!isTicketFlood())
		{
			$ticket->Fullname = AJAXDecode($_POST[POST_EXTERN_USER_NAME]);
			$ticket->UserId = $_POST[POST_EXTERN_USER_USERID];
			$ticket->Email = AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]);
			$ticket->Group = $_group;
			$ticket->Company = AJAXDecode($_POST[POST_EXTERN_USER_COMPANY]);
			$ticket->Text = AJAXDecode($_POST[POST_EXTERN_USER_MAIL]);
			saveTicket($ticket);
			$this->AddFunctionCall("lz_chat_mail_callback(true);",false);
			return true;
		}
		else
			$this->AddFunctionCall("lz_chat_mail_callback(false);",false);
		return false;
	}
	
	function SendCopyOfMail($_group,$_config,$_groups)
	{
		$message = getFile(TEMPLATE_EMAIL_MAIL);
		$message = str_replace("<!--date-->",date("r"),$message);
		$message = str_replace("<!--name-->",AJAXDecode($_POST[POST_EXTERN_USER_NAME]),$message);
		$message = str_replace("<!--email-->",AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]),$message);
		$message = str_replace("<!--company-->",AJAXDecode($_POST[POST_EXTERN_USER_COMPANY]),$message);
		$message = str_replace("<!--mail-->",AJAXDecode($_POST[POST_EXTERN_USER_MAIL]),$message);
		$message = str_replace("<!--group-->",$_groups[$_group]["gr_desc"],$message);
		$sender = (!isnull($_config["gl_usmasend"]) && isValidEmail(AJAXDecode($_POST[POST_EXTERN_USER_EMAIL])) && isnull($_config["gl_smtpauth"])) ? AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]) : $_config["gl_mail_sender"];
		if(!isnull($_config["gl_scom"]))
			sendMail($_config["gl_scom"],$sender,AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]),$message,$_config["gl_site_name"] . " - New Offline Message from " . AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]));
		if(!isnull($_config["gl_sgom"]))
			sendMail($_groups[$_group]["gr_email"],$sender,AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]),$message,$_config["gl_site_name"] . " - New Offline Message from " . AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]));
	}
	
	function StoreFile($_browserId,$_partner,$_fullname)
	{
		$filename = namebase($_FILES['userfile']['name']);

		if(!isValidUploadFile($filename))
			return false;

		$fileid = md5($filename . $this->UserId . $_browserId);
		$fileurid = EX_FILE_UPLOAD_REQUEST . "_" . $fileid;
		$filemask = $this->UserId . "_" . $fileid;
		$request = new FileUploadRequest($fileurid,$_partner);
		$request->Load();

		if($request->Permission == PERMISSION_FULL)
		{
			if(move_uploaded_file($_FILES["userfile"]["tmp_name"], PATH_UPLOADS . $request->FileMask))
			{
				createFileBaseFolders($_partner,false);
				processResource($_partner,$this->UserId,$_fullname,0,$_fullname,0,5,3);
				processResource($_partner,$fileid,$filemask,4,$_FILES["userfile"]["name"],0,$this->UserId,4,$_FILES["userfile"]["size"]);
				
				$request->Download = true;
				$request->Save();
				return true;
			}
			else
			{
				$request->Error = true;
				$request->Save();
			}
		}
		return false;
	}
	
	function SaveRate($_internalId)
	{
		$rate = new Rating(time() . "_" . getIP());
		if(!$rate->IsFlood())
		{
			$rate->RateComment = AJAXDecode($_POST[POST_EXTERN_RATE_COMMENT]);
			$rate->RatePoliteness = $_POST[POST_EXTERN_RATE_POLITENESS];
			$rate->RateQualification = $_POST[POST_EXTERN_RATE_QUALIFICATION];
			$rate->Fullname = AJAXDecode($_POST[POST_EXTERN_USER_NAME]);
			$rate->Email = AJAXDecode($_POST[POST_EXTERN_USER_EMAIL]);
			$rate->Company = AJAXDecode($_POST[POST_EXTERN_USER_COMPANY]);
			$rate->UserId = $_POST[POST_EXTERN_USER_USERID];
			$rate->InternId = $_internalId;
			saveRating($rate);
			$this->AddFunctionCall("lz_chat_send_rate_callback(true);",false);
		}
		else
			$this->AddFunctionCall("lz_chat_send_rate_callback(false);",false);
	}
	
	function AddFunctionCall($_call,$_overwrite)
	{
		if(isnull($this->Response))
			$this->Response = "";
		if($_overwrite)
			$this->Response = $_call;
		else
			$this->Response .= $_call;
	}
	
	function LoadStaticInformation()
	{
		$this->ExternalStatic = new ExternalStatic($this->UserId);
	}
}

class ExternalBrowser extends User
{
	var $BrowserId;
	var $Referrer;
	var $History;
	var $Request;
	var $Guide;
	var $Alerts;
	var $Type = BROWSER_TYPE_BROWSER;
	
	function ExternalBrowser($_browserid,$_userid)
   	{
		$this->BrowserId = $_browserid;
		$this->UserId = $_userid;
		$this->SystemId = $this->UserId . "~" . $this->BrowserId;
		$this->Folder = PATH_DATA_EXTERNAL . $this->UserId . "/b/" . $this->BrowserId . "/";
		$this->SessionFile = $this->Folder . $this->BrowserId . "." . EX_BROWSER_SESSION;
   	}
	
	function Load()
	{
		$this->AppendFromCookies();
		$dataProvider = new DataProvider($this->SessionFile);
		$dataProvider->Load();
		$this->FirstActive = $dataProvider->Result["s_first_active"];
		$this->History = $dataProvider->Result["s_history"];
		$this->Referrer = $dataProvider->Result["s_referrer"];
		
		if(isset($dataProvider->Result["s_fullname"]))
			$this->Fullname = $dataProvider->Result["s_fullname"];
			
		if(isset($dataProvider->Result["s_email"]))
			$this->Email = $dataProvider->Result["s_email"];
		
		if(isset($dataProvider->Result["s_company"]))
			$this->Company = $dataProvider->Result["s_company"];
	}

	function GetActions()
	{
		$actionfiles = getDirectory($this->Folder,false);
		$this->Request = null;
		$this->Guide = null;
		foreach($actionfiles as $key => $actionfile)
		{
			if(strpos($actionfile, "." . EX_CHAT_REQUEST) !== false || strpos($actionfile, "." . EX_CHAT_REQUEST_ACCEPTED) !== false || strpos($actionfile, "." . EX_CHAT_REQUEST_DECLINED) !== false)
			{
				if(isnull($this->Request))
				{
					if(strpos($actionfile, "." . EX_CHAT_REQUEST) !== false)
						$this->Request = new ChatRequest(str_replace("." . EX_CHAT_REQUEST,"",$actionfile),$this->UserId,$this->BrowserId,REQUEST_STATUS_ACTIVE);
					if(strpos($actionfile, "." . EX_CHAT_REQUEST_ACCEPTED) !== false)
						$this->Request = new ChatRequest(str_replace("." . EX_CHAT_REQUEST_ACCEPTED,"",$actionfile),$this->UserId,$this->BrowserId,REQUEST_STATUS_ACCEPTED);
					if(strpos($actionfile, "." . EX_CHAT_REQUEST_DECLINED) !== false)
						$this->Request = new ChatRequest(str_replace("." . EX_CHAT_REQUEST_DECLINED,"",$actionfile),$this->UserId,$this->BrowserId,REQUEST_STATUS_DECLINED);
					$this->Request->Load();
				}
				else
					unlinkDataSet($this->Folder . $actionfile);
			}
			else if(strpos($actionfile, "." . EX_BROWSER_GUIDE) !== false)
			{
				if(isnull($this->Guide) || (!isnull($this->Guide) && @filemtime($this->Folder . $actionfile) > $this->Guide->Created))
				{
					$this->Guide = new Guide(str_replace("." . EX_BROWSER_GUIDE,"",$actionfile),$this->UserId,$this->BrowserId,true);
					$this->Guide->Created = getDataSetTime($this->Folder . $actionfile);
					$this->Guide->Load();
				}
				else
					unlinkDataSet($this->Folder . $actionfile);
			}
			else if(strpos($actionfile, "." . EX_ALERT) !== false)
			{
				$this->Alerts[$key] = new Alert(str_replace("." . EX_ALERT,"",$actionfile),$this->UserId,$this->BrowserId);
				$this->Alerts[$key]->Load();
			}
		}
	}
	
	function GetData()
	{
		$data = Array();
		$data["s_first_active"] = $this->FirstActive;
		$data["s_history"] = $this->History;
		$data["s_referrer"] = $this->Referrer;
		if(!isnull($this->Fullname))
			$data["s_fullname"] = $this->Fullname;
		if(!isnull($this->Email))
			$data["s_email"] = $this->Email;
		if(!isnull($this->Company))
			$data["s_company"] = $this->Company;
		return $data;
	}
	
	function Destroy()
	{
		deleteDirectory($this->Folder);
	}
}

class ExternalChat extends ExternalBrowser
{
	var $DesiredChatGroup;
	var $DesiredChatPartner;
	var $DesiredChatPartnerTyping = false;
	var $Forward;
	var $Waiting;
	var $Chat;
	var $Code = "CHAT";
	var $Type = BROWSER_TYPE_CHAT;
	var $ConnectingMessageDisplayed = null;
	var $ChatRequestReceiptants;

	function ExternalChat($_userid,$_browserId)
   	{
		$this->UserId = $_userid;
		$this->BrowserId = $_browserId;
		$this->SystemId = $this->UserId . "~" . $this->BrowserId;
		$this->Folder = PATH_DATA_EXTERNAL . $this->UserId . "/b/" . $this->BrowserId . "/";
		$this->SessionFile = $this->Folder . $this->BrowserId . "." . EX_CHAT_SESSION;
   	}
	
	function GetData()
	{
		$data = Array();
		$data["s_typing"] = $this->Typing;
		
		if(!isnull($this->Fullname))
			$data["s_fullname"] = $this->Fullname;
			
		$data["s_email"] = $this->Email;
		$data["s_company"] = $this->Company;
		$data["s_waiting"] = $this->Waiting;
		$data["s_code"] = $this->Code;
		$data["s_first_active"] = (!isnull($this->FirstActive)) ? $this->FirstActive : time();
		$data["s_internal"] = $this->DesiredChatPartner;
		$data["s_group"] = $this->DesiredChatGroup;
		return $data;
	}
	
	function SetCookieGroup()
	{
		setCookieValue("login_group",$this->DesiredChatGroup);
	}
	
	function RequestFileUpload($_user,$_filename)
	{
		$fileid = md5(namebase($_filename) . $this->UserId . $this->BrowserId);
		$filemask = $this->UserId . "_" . $fileid;
		$fileurid = EX_FILE_UPLOAD_REQUEST . "_" . $fileid;
		$request = new FileUploadRequest($fileurid,$this->DesiredChatPartner);
		$request->SenderUserId = $this->UserId;
		$request->FileName = namebase($_filename);
		$request->FileMask = $filemask;
		$request->FileId = $fileid;
		$request->SenderBrowserId = $this->BrowserId;
		if(dataSetExists($request->TargetFile))
		{
			$request->Load();
			if($request->Permission == PERMISSION_FULL)
			{
				$_user->AddFunctionCall("top.lz_chat_file_start_upload('".$_filename."');",false);
			}
			else if($request->Permission == PERMISSION_NONE)
			{
				$_user->AddFunctionCall("top.lz_chat_file_stop();",false);
				$_user->AddFunctionCall("top.lz_chat_file_error(1);",false);
				$request->Destroy();
			}
		}
		else if(!dataSetExists($request->TargetFile))
		{
			if(!isValidUploadFile($_filename))
				$_user->AddFunctionCall("top.lz_chat_file_error(2);",false);
			else
				$request->Save();
		}
		return $_user;
	}
	
	function AbortFileUpload($_user,$_filename,$_error)
	{
		$fileid = substr(md5(namebase($_filename)),0,5);
		$request = new FileUploadRequest($this->BrowserId . "_" . $fileid,$this->DesiredChatPartner);
		if(dataSetExists($request->TargetFile))
		{
			$request->Load();
			$request->Error = $_error;
			$request->Save();
		}
	}
	
	function Load()
	{
		$this->AppendFromCookies();
		$dataProvider = new DataProvider($this->SessionFile);
		$dataProvider->Load();

		if(isset($dataProvider->Result["s_fullname"]))
			$this->Fullname = $dataProvider->Result["s_fullname"];
		
		$this->Email = $dataProvider->Result["s_email"];
		$this->Company = $dataProvider->Result["s_company"];
		$this->Waiting = $dataProvider->Result["s_waiting"];
		$this->FirstActive = $dataProvider->Result["s_first_active"];
		$this->Typing = $dataProvider->Result["s_typing"];
		$this->Code = $dataProvider->Result["s_code"];
		$this->DesiredChatPartner = $dataProvider->Result["s_internal"];
		$this->DesiredChatGroup = $dataProvider->Result["s_group"];
	
	}
	
	function LoadChat($_config,$_internal)
	{
		$declined = true;
		$this->Chat = null;
		$this->ChatRequestReceiptants = array();
		$chatfiles = getDirectory($this->Folder,false);
		foreach($chatfiles as $chatfile)
			if(strpos($chatfile, "." . EX_CHAT_OPEN) !== false)
			{
				if(strpos($chatfile, "." . EX_CHAT_OPEN) !== false  && ($_config["gl_alloc_mode"] == ALLOCATION_MODE_ALL || isnull($this->Chat)))
				{
					$partnerid = str_replace("." . EX_CHAT_OPEN,"",$chatfile);
					$chat = new Chat($this->Folder . $chatfile);
					$activated = $chat->IsActivated($partnerid);
					
					if(!$chat->Declined)
					{
						$declined = false;
						if(!$activated)
							$this->ChatRequestReceiptants[] = $partnerid;
					}
					if(($activated || isnull($this->Chat)) || (CALLER_TYPE != CALLER_TYPE_EXTERNAL && !isnull($this->Chat) && ($partnerid == $_internal->SystemId && !$chat->IsActivated(null))))
					{
						if(CALLER_TYPE != CALLER_TYPE_EXTERNAL && $chat->Declined)
							continue;
							
						$this->Chat = $chat;
						if(isnull($_internal))
						{
							$_internal = new Operator($partnerid,null);
							$_internal->Load();
							$this->DesiredChatPartnerTyping = ($_internal->Typing == $this->SystemId);
						}
						
						$dataSetTime = getDataSetTime($_internal->SessionFile);
						if($_internal->Status == CHAT_STATUS_ACTIVE || $dataSetTime < (time()-$_config["timeout_clients"]))
						{
							$this->Chat->Closed = true;
						}
					}
				}
				else
				{
					unlinkDataSet($this->Folder . $chatfile);
				}
			}
			
		if(!isnull($this->Chat))
			$this->Chat->Declined = $declined;
	}
	
	function GetForwards()
	{
		$this->Forward = null;
		$actionfiles = getDirectory($this->Folder,false);
		sort($actionfiles);
		foreach($actionfiles as $index => $file)
		{
			if(strpos($file,EX_CHAT_FORWARDING) !== false)
			{
				if(isnull($this->Forward))
				{
					$this->Forward = new Forward($this->UserId,$this->BrowserId,str_replace("." . EX_CHAT_FORWARDING,"",$file));
					$this->Forward->Load();
				}
			}
		}
	}
	
	function CreateChat($_internalUser,$_chatId)
	{
		$_internalUser->SetLastChatAllocation();
		$this->Chat = new Chat($this,$_internalUser,$_chatId);
		$this->Chat->InternalDestroy(false);
		$this->Chat->ExternalDestroy(false);
		$this->Chat->Save();
		updateRoom($_chatId,CHAT_STATUS_WAITING);
	}
	
	function DestroyChatFiles()
	{
		if(!isnull($this->Chat))
			unregisterChat($this->Chat->Id);
		$chatfiles = getDirectory($this->Folder,false);
		foreach($chatfiles as $chatfile)
			if(strpos($chatfile, "." . EX_CHAT_OPEN) !== false || strpos($chatfile, "." . EX_CHAT_ACTIVE) !== false || strpos($chatfile, "." . EX_CHAT_INTERN_CLOSED) != false || strpos($chatfile, "." . EX_CHAT_INTERN_DECLINED) != false)
				unlinkDataSet($this->Folder . $chatfile);
	}
	
	function Destroy()
	{
		deleteDirectory($this->Folder);
		if(!isnull($this->Chat))
			unregisterChat($this->Chat->Id);
	}
}

class ExternalStatic extends User
{
	var $SystemInfo;
	var $Language;
	var $Resolution;
	var $Host;
	var $Email;
	var $Company;
	var $Visits = 1;
	var $GeoCity;
	var $GeoCountryISO2;
	var $GeoRegion;
	var $GeoLongitude= -522;
	var $GeoLatitude= -522;
	var $GeoTimezoneOffset = "+00:00";
	var $GeoISP;
	var $GeoResultId = 0;
	
	function ExternalStatic($_userid)
   	{
		$this->UserId = $_userid;
		$this->Folder = PATH_DATA_EXTERNAL . $this->UserId . "/";
		$this->SessionFile = $this->Folder . $this->UserId . "." . EX_STATIC_INFO;
   	}
	
	function Load()
	{
		$dataProvider = new DataProvider($this->SessionFile);
		$dataProvider->Load();

		$this->IP = $dataProvider->Result["s_ip"];
		$this->SystemInfo = $dataProvider->Result["s_system"];
		$this->Language = $dataProvider->Result["s_language"];
		$this->Resolution = $dataProvider->Result["s_resolution"];
		$this->Host = $dataProvider->Result["s_host"];
		
		if(isset($dataProvider->Result["s_geotz"]))
			$this->GeoTimezoneOffset = $dataProvider->Result["s_geotz"];
		if(isset($dataProvider->Result["s_geolong"]))
			$this->GeoLongitude = $dataProvider->Result["s_geolong"];
		if(isset($dataProvider->Result["s_geolat"]))
			$this->GeoLatitude = $dataProvider->Result["s_geolat"];
		if(isset($dataProvider->Result["s_geocity"]))
			$this->GeoCity = $dataProvider->Result["s_geocity"];
		if(isset($dataProvider->Result["s_geocountry"]))
			$this->GeoCountryISO2 = $dataProvider->Result["s_geocountry"];
		if(isset($dataProvider->Result["s_georegion"]))
			$this->GeoRegion = $dataProvider->Result["s_georegion"];
		if(isset($dataProvider->Result["s_visits"]))
			$this->Visits =	$dataProvider->Result["s_visits"];
		if(isset($dataProvider->Result["s_georid"]))
			$this->GeoResultId = $dataProvider->Result["s_georid"];
		if(isset($dataProvider->Result["s_geoisp"]))
			$this->GeoISP = $dataProvider->Result["s_geoisp"];
		
	}
	
	function GetData()
	{
		$data = Array();
		$data["s_ip"] = $this->IP;
		$data["s_system"] = $this->SystemInfo;
		$data["s_language"] = $this->Language;
		$data["s_resolution"] = $this->Resolution;
		$data["s_geotz"] = $this->GeoTimezoneOffset;
		if(is_numeric($this->GeoLongitude))
			$data["s_geolong"] = $this->GeoLongitude;
		if(is_numeric($this->GeoLatitude))
			$data["s_geolat"] = $this->GeoLatitude;
		$data["s_geocity"] = $this->GeoCity;
		$data["s_geocountry"] = $this->GeoCountryISO2;
		$data["s_georegion"] = $this->GeoRegion;
		$data["s_visits"] = $this->Visits;
		$data["s_host"] = $this->Host;
		$data["s_georid"] = $this->GeoResultId;
		$data["s_geoisp"] = $this->GeoISP;
		return $data;
	}
}
?>