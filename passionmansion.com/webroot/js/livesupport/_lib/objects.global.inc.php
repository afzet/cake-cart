<?php

/****************************************************************************************
* LiveZilla objects.global.inc.php // VERSION 3.1.8.4
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
	
class Post
{
	var $Receiver;
	var $ReceiverGroup;
	var $Text;
	var $Id;
	var $Created;
	var $Sender;
	var $Persistent = false;
	
	function Post()
   	{
		if(func_num_args() == 1)
		{
			$row = func_get_arg(0);
			$this->Id = $row["id"];
			$this->Sender = $row["sender"];
			$this->Receiver = $row["receiver"];
			$this->ReceiverGroup = $row["receiver_group"];
			$this->Text = $row["text"];
			$this->Created = $row["time"];
			
		}
		else
		{
			$this->Id = func_get_arg(0);
			$this->Sender = func_get_arg(1);
			$this->Receiver = func_get_arg(2);
			$this->Text = func_get_arg(3);
			$this->Created = func_get_arg(4);
		}
   	}
	
	function GetXml()
	{
		$receiver = (!isnull($this->ReceiverGroup)) ? $this->ReceiverGroup : $this->Receiver;
		return "<val id=\"".base64_encode($this->Id)."\" sen=\"".base64_encode($this->Sender)."\" rec=\"".base64_encode($receiver)."\" date=\"".base64_encode($this->Created)."\">".base64_encode($this->Text)."</val>\r\n";
	}
	
	function GetCommand()
	{
		return "lz_chat_add_internal_text(\"".base64_encode($this->Text)."\" ,\"".base64_encode($this->Id)."\");";
	}
	
	function Save()
	{
		return writePost($this);
	}
}

class FilterSystem
{
	var $Filters;
	var $Message;
	
	function FilterSystem()
   	{
		$this->Filters = Array();
   	}
	
	function Populate()
	{
		$filters = getDirectory(PATH_FILTER,false,true);
		foreach($filters as $file)
		{
			if(stristr($file,FILE_EXTENSION_FILTER))
			{
				$filter = new Filter(str_replace(FILE_EXTENSION_FILTER,"",$file));
				$filter->Load();
				$this->Filters[$filter->FilterId] = $filter;
			}
		}
	}
	
	function Match($_ip,$_languages,$_userid)
	{
		foreach($this->Filters as $filterid => $filter)
		{
			if($filter->Activestate == FILTER_TYPE_INACTIVE)
				continue;
			
			$this->Message = $filter->Reason;
			$compare["match_ip"] = $this->IpCompare($_ip,$filter->IP);
			$compare["match_lang"] = $this->LangCompare($_languages,$filter->Languages);
			$compare["match_id"] = ($filter->Userid == $_userid);
			if($compare["match_ip"] && $filter->Exertion == FILTER_EXERTION_BLACK && $filter->Activeipaddress == FILTER_TYPE_ACTIVE)
				define("ACTIVE_FILTER_ID",$filter->FilterId);
			else if(!$compare["match_ip"] && $filter->Exertion == FILTER_EXERTION_WHITE && $filter->Activeipaddress == FILTER_TYPE_ACTIVE)
				define("ACTIVE_FILTER_ID",$filter->FilterId);
			else if($compare["match_lang"] && $filter->Exertion == FILTER_EXERTION_BLACK && $filter->Activelanguage == FILTER_TYPE_ACTIVE)
				define("ACTIVE_FILTER_ID",$filter->FilterId);
			else if(!$compare["match_lang"] && $filter->Exertion == FILTER_EXERTION_WHITE && $filter->Activelanguage == FILTER_TYPE_ACTIVE)
				define("ACTIVE_FILTER_ID",$filter->FilterId);
			else if($compare["match_id"] && $filter->Exertion == FILTER_EXERTION_BLACK && $filter->Activeuserid == FILTER_TYPE_ACTIVE)
				define("ACTIVE_FILTER_ID",$filter->FilterId);
			else if(!$compare["match_id"] && $filter->Exertion == FILTER_EXERTION_WHITE && $filter->Activeuserid == FILTER_TYPE_ACTIVE)
				define("ACTIVE_FILTER_ID",$filter->FilterId);
			if(defined("ACTIVE_FILTER_ID"))
				return true;
		}
		return false;
	}
	
	function IpCompare($_ip, $_comparer)
	{
		$array_ip = explode(".",$_ip);
		$array_comparer = explode(".",$_comparer);
		if(count($array_ip) == 4 && count($array_comparer) == 4)
		{
			foreach($array_ip as $key => $octet)
			{
				if($array_ip[$key] != $array_comparer[$key])
				{
					if($array_comparer[$key] == -1)
						return true;
					return false;
				}
			}
			return true;
		}
		else
			return false;
	}
	
	function LangCompare($_lang, $_comparer)
	{
		$array_lang = explode(",",$_lang);
		$array_comparer = explode(",",$_comparer);
		foreach($array_lang as $key => $lang)
			foreach($array_comparer as $keyc => $langc)
				if(strtoupper($array_lang[$key]) == strtoupper($langc))
					return true;
		return false;
	}
}

class Filter
{
	var $Creator;
	var $Created;
	var $Editor;
	var $Edited;
	var $IP;
	var $Expiredate;
	var $Userid;
	var $Reason;
	var $Filtername;
	var $FilterId;
	var $Activestate;
	var $Exertion;
	var $Languages;
	var $Activeipaddress;
	var $Activeuserid;
	var $Activelanguage;
	
	function GetData()
	{
		$data = Array();
		$data["s_creator"] = $this->Creator;
		$data["s_created"] = $this->Created;
		$data["s_editor"] = $this->Editor;
		$data["s_edited"] = $this->Edited;
		$data["s_ip"] = $this->IP;
		$data["s_expiredate"] = $this->Expiredate;
		$data["s_userid"] = $this->Userid;
		$data["s_reason"] = $this->Reason;
		$data["s_filtername"] = $this->Filtername;
		$data["s_filterid"] = $this->FilterId;
		$data["s_activestate"] = $this->Activestate;
		$data["s_exertion"] = $this->Exertion;
		$data["s_languages"] = $this->Languages;
		$data["s_activeipaddress"] = $this->Activeipaddress;
		$data["s_activeuserid"] = $this->Activeuserid;
		$data["s_activelanguage"] = $this->Activelanguage;
		return $data;
	}
	
	function Filter($_id)
   	{
		$this->FilterId = $_id;
		$this->Edited = time();
   	}
	
	function GetXML()
	{
		return "<val active=\"".base64_encode($this->Activestate)."\" edited=\"".base64_encode($this->Edited)."\" editor=\"".base64_encode($this->Editor)."\" activeipaddresses=\"".base64_encode($this->Activeipaddress)."\" activeuserids=\"".base64_encode($this->Activeuserid)."\" activelanguages=\"".base64_encode($this->Activelanguage)."\" expires=\"".base64_encode($this->Expiredate)."\" creator=\"".base64_encode($this->Creator)."\" created=\"".base64_encode($this->Created)."\" userid=\"".base64_encode($this->Userid)."\" ip=\"".base64_encode($this->IP)."\" filtername=\"".base64_encode($this->Filtername)."\" filterid=\"".base64_encode($this->FilterId)."\" reason=\"".base64_encode($this->Reason)."\" exertion=\"".base64_encode($this->Exertion)."\" languages=\"".base64_encode($this->Languages)."\" />\r\n";
	}
	
	function Load()
	{
		$dataProvider = new FileEditor(PATH_FILTER . $this->FilterId . FILE_EXTENSION_FILTER);
		$dataProvider->Load();
		$this->Creator = $dataProvider->Result["s_creator"];
		$this->Created = $dataProvider->Result["s_created"];
		$this->Editor = $dataProvider->Result["s_editor"];
		$this->Edited = $dataProvider->Result["s_edited"];
		$this->IP = $dataProvider->Result["s_ip"];
		$this->Expiredate = $dataProvider->Result["s_expiredate"];
		$this->Userid = $dataProvider->Result["s_userid"];
		$this->Reason = $dataProvider->Result["s_reason"];
		$this->Filtername = $dataProvider->Result["s_filtername"];
		$this->FilterId = $dataProvider->Result["s_filterid"];
		$this->Activestate = $dataProvider->Result["s_activestate"];
		$this->Exertion = $dataProvider->Result["s_exertion"];
		$this->Languages = $dataProvider->Result["s_languages"];
		$this->Activeipaddress = $dataProvider->Result["s_activeipaddress"];
		$this->Activeuserid = $dataProvider->Result["s_activeuserid"];
		$this->Activelanguage = $dataProvider->Result["s_activelanguage"];
	}
	
	function Save()
	{
		$dataProvider = new FileEditor(PATH_FILTER . $this->FilterId . FILE_EXTENSION_FILTER);
		$dataProvider->Save($this->GetData());
	}
	
	function Destroy()
	{
		if(file_exists(PATH_FILTER . $this->FilterId . FILE_EXTENSION_FILTER))
			@unlink(PATH_FILTER . $this->FilterId . FILE_EXTENSION_FILTER);
	}
}

class Rating extends Action
{
	var $Fullname = "";
	var $Email="";
	var $Company="";
	var $InternId="";
	var $UserId="";
	var $RateQualification=0;
	var $RatePoliteness=0;
	var $RateComment=0;

	function Rating()
	{
		$this->Id = func_get_arg(0);
		if(func_num_args() == 2)
		{
			$row = func_get_arg(1);
			$this->RateComment = $row["comment"];
			$this->RatePoliteness = $row["politeness"];
			$this->RateQualification = $row["qualification"];
			$this->Fullname = $row["fullname"];
			$this->Email = $row["email"];
			$this->Company = $row["company"];
			$this->InternId = $row["internal_id"];
			$this->UserId = $row["user_id"];
			$this->Created = $row["time"];
		}
	}
	
	function GetData()
	{
		$data = Array();
		$data["s_rate_c"] = $this->RateComment;
		$data["s_rate_p"] = $this->RatePoliteness;
		$data["s_rate_q"] = $this->RateQualification;
		$data["s_fullname"] = $this->Fullname;
		$data["s_email"] = $this->Email;
		$data["s_company"] = $this->Company;
		$data["s_internid"] = $this->InternId;
		$data["s_userid"] = $this->UserId;
		return $data;
	}
	
	function IsFlood()
	{
		return isRatingFlood();
	}
	
	function Load()
	{
		$dataProvider = new FileEditor($this->TargetFile);
		$dataProvider->Load();
		$this->RateComment = $dataProvider->Result["s_rate_c"];
		$this->RatePoliteness = $dataProvider->Result["s_rate_p"];
		$this->RateQualification = $dataProvider->Result["s_rate_q"];
		$this->Fullname = $dataProvider->Result["s_fullname"];
		$this->Email = $dataProvider->Result["s_email"];
		$this->Company = $dataProvider->Result["s_company"];
		$this->InternId = $dataProvider->Result["s_internid"];
		$this->UserId = $dataProvider->Result["s_userid"];
		$this->Created = @filemtime($this->TargetFile);
	}
	
	function GetXML($_internal,$_full)
	{
		if($_full)
		{
			$intern = (isset($_internal[getInternSessIdByUserId($this->InternId)])) ? $_internal[getInternSessIdByUserId($this->InternId)]->Fullname : $this->InternId;
			return "<val id=\"".base64_encode($this->Id)."\" cr=\"".base64_encode($this->Created)."\" rc=\"".base64_encode($this->RateComment)."\" rp=\"".base64_encode($this->RatePoliteness)."\" rq=\"".base64_encode($this->RateQualification)."\" fn=\"".base64_encode($this->Fullname)."\" em=\"".base64_encode($this->Email)."\" co=\"".base64_encode($this->Company)."\" ii=\"".base64_encode($intern)."\" ui=\"".base64_encode($this->UserId)."\" />\r\n";
		}
		else
			return "<val id=\"".base64_encode($this->Id)."\" />\r\n";
	}
}

class ClosedTicket extends Action
{
	function ClosedTicket()
	{
		$this->Id = func_get_arg(0);
		if(func_num_args() == 2)
		{
			$row = func_get_arg(1);
			$this->Sender = $row["internal_fullname"];
		}
	}

	function GetXML($_time,$_status)
	{
		return "<cl id=\"".base64_encode($this->Id)."\" st=\"".base64_encode($_status)."\" ed=\"".base64_encode($this->Sender)."\" ti=\"".base64_encode($_time)."\"/>\r\n";
	}
}

class UserTicket extends Action
{
	var $Fullname = "";
	var $Email="";
	var $Group="";
	var $Company="";
	var $IP="";
	var $UserId="";
	
	function UserTicket()
	{
		if(func_num_args() == 2)
		{
			$this->Id = func_get_arg(0);
		}
		else
		{
			$row = func_get_arg(0);
			$this->Text = $row["text"];
			$this->Fullname = $row["fullname"];
		 	$this->Email = $row["email"];
			$this->Company = $row["company"];
			$this->Group = $row["target_group_id"];
			$this->IP = $row["ip"];
			$this->Id = $row["ticket_id"];
			$this->UserId = $row["user_id"];
			$this->Created = $row["time"];
		}
	}

	function GetXML($_groups,$_full)
	{
		if($_full)
			return "<val id=\"".base64_encode($this->Id)."\" ct=\"".base64_encode($this->Created)."\" gr=\"".base64_encode($this->Group)."\" mt=\"".base64_encode($this->Text)."\" fn=\"".base64_encode($this->Fullname)."\" em=\"".base64_encode($this->Email)."\" co=\"".base64_encode($this->Company)."\" ui=\"".base64_encode($this->UserId)."\" />\r\n";
		else
			return "<val id=\"".base64_encode($this->Id)."\" ct=\"".base64_encode($this->Created)."\" />\r\n";
	}
}

class Response
{
	var $XML = "";
	var $Internals="";
	var $Groups="";
	var $InternalProfilePictures="";
	var $InternalWebcamPictures="";
	var $InternalVcards="";
	var $Typing="";
	var $Exceptions="";
	var $Filter="";
	var $Authentications="";
	var $Posts="";
	var $Login;
	var $Ratings="";
	var $Messages="";
	var $Archive="";
	var $Resources="";
	var $GlobalHash;
	
	function SetStandardResponse($_code,$_sub)
	{
		$this->XML = "<response><value id=\"".base64_encode($_code)."\" />" . $_sub . "</response>";
	}
	
	function SetValidationError($_code,$_addition="")
	{
		if(!isnull($_addition))
			$this->XML = "<validation_error value=\"".base64_encode($_code)."\" error=\"".base64_encode($_addition)."\" />";
		else
			$this->XML = "<validation_error value=\"".base64_encode($_code)."\" />";
	}
	
	function GetXML()
	{
		return "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><livezilla_xml><livezilla_version>".base64_encode(VERSION)."</livezilla_version>" . $this->XML . "</livezilla_xml>";
	}
}

class FileEditor
{
	var $Result;
	var $TargetFile;
	
	function FileEditor($_file)
	{
		$this->TargetFile = $_file;
	}
	
	function Load()
	{
		if(file_exists($this->TargetFile))
		{
			$handle = @fopen ($this->TargetFile, "r");
			while (!@feof($handle))
	   			$this->Result .= @fgets($handle, 4096);
			
			$length = strlen($this->Result);
			$this->Result = @unserialize($this->Result);
			@fclose($handle);
		}
	}

	function Save($_data)
	{
		$handle = @fopen($this->TargetFile, "w");
		if(!isnull($_data))
			$length = @fputs($handle,serialize($_data));
		@fclose($handle);
	}
}

class DataProvider
{
	var $TargetFile;
	var $Result;
	
	function DataProvider($_file)
	{
		$this->TargetFile = $_file;
	}
	
	function Load()
	{
		$this->Result = getDataSet($this->TargetFile);
	}
	
	function Save($_data)
	{
		writeDataSet($this->TargetFile,$_data);
	}
}

class Action
{
	var $Id;
	var $Folder;
	var $ReceiverUserId;
	var $ReceiverBrowserId;
	var $SenderSessId;
	var $SenderUserId;
	var $SenderFullname;
	var $Text;
	var $BrowserId;
	var $Status;
	var $TargetFile;
	var $Extension;
	var $Created;
	
	function Save()
	{
		$dataProvider = new DataProvider($this->TargetFile);
		$dataProvider->Save($this->GetData());
		return true;
	}
	
	function Destroy()
	{
		unlinkDataSet($this->TargetFile);
	}
}

class Alert extends Action
{
	var $Alert;
	function Alert($_id,$_receiver,$_browserId)
	{
		$this->Id = $_id;
		$this->ReceiverUserId = $_receiver;
		$this->BrowserId = $_browserId;
		$this->Extension = EX_ALERT;
		$this->Folder = PATH_DATA_EXTERNAL . $this->ReceiverUserId . "/b/" . $this->BrowserId . "/";
		$this->TargetFile = $this->Folder . $_id . "." . $this->Extension;
	}
	
	function GetData()
	{
		$data = Array();
		$data["s_alert"] = $this->Alert;
		return $data;
	}
	
	function Load()
	{
		$dataProvider = new DataProvider($this->TargetFile);
		$dataProvider->Load();
		$this->Alert = $dataProvider->Result["s_alert"];
		$this->Created = @filemtime($this->TargetFile);
	}
	
	function GetCommand()
	{
		return "lz_tracking_send_alert('".base64_encode($this->Alert)."');";
	}
}

class FileUploadRequest extends Action
{
	var $Error = false;
	var $Download = false;
	var $FileName;
	var $FileMask;
	var $FileId;
	var $Permission = PERMISSION_VOID;
	function FileUploadRequest($_fileId,$_receiverId)
	{
		$this->Id = $_fileId;
		$this->ReceiverUserId = $_receiverId;
		$this->Extension = EX_FILE_UPLOAD_REQUEST;
		$this->Folder = PATH_DATA_INTERNAL . $this->ReceiverUserId . "/";
		$this->TargetFile = $this->Folder . $this->Id . "." . $this->Extension;
	}
	
	function GetData()
	{
		$data = Array();
		$data["s_id"] = $this->Id;
		$data["s_filename"] = $this->FileName;
		$data["s_filemask"] = $this->FileMask;
		$data["s_fileid"] = $this->FileId;
		$data["s_senderUserId"] = $this->SenderUserId;
		$data["s_senderBrowserId"] = $this->SenderBrowserId;
		$data["s_error"] = $this->Error;
		$data["s_permission"] = $this->Permission;
		$data["s_download"] = $this->Download;
		return $data;
	}
	
	function Load()
	{
		$dataProvider = new DataProvider($this->TargetFile);
		$dataProvider->Load();
		$this->Id = $dataProvider->Result["s_id"];
		$this->FileName = $dataProvider->Result["s_filename"];
		$this->FileMask = $dataProvider->Result["s_filemask"];
		$this->FileId = $dataProvider->Result["s_fileid"];
		$this->SenderUserId = $dataProvider->Result["s_senderUserId"];
		$this->SenderBrowserId = $dataProvider->Result["s_senderBrowserId"];
		$this->Error = $dataProvider->Result["s_error"];
		$this->Permission = $dataProvider->Result["s_permission"];
		$this->Download = $dataProvider->Result["s_download"];
	}
	
	function GetFile()
	{
		return PATH_UPLOADS . $this->FileMask;
	}
}

class Guide extends Action
{
	var $TargetURL;
	var $Ask;
	
	function Guide($_sender,$_receiver,$_browserId,$_ask)
	{
		$this->Id = getId(USER_ID_LENGTH);
		$this->Ask = $_ask;
		$this->SenderSessId = $_sender;
		$this->ReceiverUserId = $_receiver;
		$this->BrowserId = $_browserId;
		$this->Extension = EX_BROWSER_GUIDE;
		$this->Folder = PATH_DATA_EXTERNAL . $this->ReceiverUserId . "/b/" . $this->BrowserId . "/";
		$this->TargetFile = $this->Folder . $this->SenderSessId . "." . $this->Extension;
	}
	
	function GetData()
	{
		$data = Array();
		$data["s_text"] = $this->Text;
		$data["s_ask"] = $this->Ask;
		$data["s_url"] = $this->TargetURL;
		$data["s_sendername"] = $this->SenderFullname;
		return $data;
	}
	
	function Load()
	{
		$dataProvider = new DataProvider($this->TargetFile);
		$dataProvider->Load();
		$this->Text = $dataProvider->Result["s_text"];
		$this->Ask = $dataProvider->Result["s_ask"];
		$this->TargetURL = $dataProvider->Result["s_url"];
		$this->SenderFullname = $dataProvider->Result["s_sendername"];
		$this->Created = @filemtime($this->TargetFile);
	}
	
	function GetCommand()
	{
		return "lz_tracking_guide('".base64_encode($this->TargetURL)."','".base64_encode($this->Text)."','".$this->Ask."','".time()."');";
	}
}

class Forward extends Action
{
	var $Conversation;
	var $TargetSessId;
	var $TargetGroupId;
	var $Processed = false;
	
	function Forward($_receiverUserId,$_receiverBrowserId,$_senderSessId)
	{
		$this->Id = getId(5);
		$this->ReceiverUserId = $_receiverUserId;
		$this->BrowserId = $_receiverBrowserId;
		$this->SenderSessId = $_senderSessId;
		$this->Extension = EX_CHAT_FORWARDING;
		$this->Folder = PATH_DATA_EXTERNAL . $this->ReceiverUserId . "/b/" . $this->BrowserId . "/";
		$this->TargetFile = $this->Folder . $this->SenderSessId . "." . $this->Extension;
	}
	
	function GetData()
	{
		$data = Array();
		$data["p_id"] = $this->Id;
		$data["p_sendersessid"] = $this->SenderSessId;
		$data["p_targetsessid"] = $this->TargetSessId;
		$data["p_targetgroupid"] = $this->TargetGroupId;
		$data["p_browserid"] = $this->BrowserId;
		$data["p_receiveruserid"] = $this->ReceiverUserId;
		$data["p_conversation"] = $this->Conversation;
		$data["p_text"] = $this->Text;
		$data["p_processed"] = $this->Processed;
		return $data;
	}
	
	function Load()
	{
		$dataProvider = new DataProvider($this->TargetFile);
		$dataProvider->Load();
		$this->Id = $dataProvider->Result["p_id"];
		$this->SenderSessId = $dataProvider->Result["p_sendersessid"];
		$this->TargetSessId = $dataProvider->Result["p_targetsessid"];
		$this->TargetGroupId = $dataProvider->Result["p_targetgroupid"];
		$this->BrowserId = $dataProvider->Result["p_browserid"];
		$this->ReceiverUserId = $dataProvider->Result["p_receiveruserid"];
		$this->Conversation = $dataProvider->Result["p_conversation"];
		$this->Text = $dataProvider->Result["p_text"];
		$this->Processed = $dataProvider->Result["p_processed"];
	}
}

class ChatRequest extends Action
{
	var $SenderGroupId;
	function ChatRequest($_sender,$_receiver,$_browserId,$_status)
   	{
		$this->Id = getId(USER_ID_LENGTH);
		$this->SenderSessId = $_sender;
		$this->ReceiverUserId = $_receiver;
		$this->BrowserId = $_browserId;
		$this->Folder = PATH_DATA_EXTERNAL . $this->ReceiverUserId . "/b/" . $this->BrowserId . "/";
		$this->SetStatus($_status);
   	}
	
	function SetStatus($_status)
	{
		$this->Status = $_status;
		if($this->Status == REQUEST_STATUS_ACTIVE)
			$this->Extension = EX_CHAT_REQUEST;
		else if($this->Status == REQUEST_STATUS_ACCEPTED)
			$this->Extension = EX_CHAT_REQUEST_ACCEPTED;
		else if($this->Status == REQUEST_STATUS_DECLINED)
			$this->Extension = EX_CHAT_REQUEST_DECLINED;
		$this->TargetFile =  $this->Folder . $this->SenderSessId . "." . $this->Extension;
	}
	
	function GetData()
	{
		$data = Array();
		$data["p_id"] = $this->Id;
		$data["p_text"] = $this->Text;
		$data["p_senderuserid"] = $this->SenderUserId;
		$data["p_sendername"] = $this->SenderFullname;
		$data["p_sendergroupid"] = $this->SenderGroupId;
		return $data;
	}
	
	function Load()
	{
		$dataProvider = new DataProvider($this->TargetFile);
		$dataProvider->Load();
		$this->Id = $dataProvider->Result["p_id"];
		$this->Text = $dataProvider->Result["p_text"];
		$this->SenderFullname = $dataProvider->Result["p_sendername"];
		$this->SenderGroupId = $dataProvider->Result["p_sendergroupid"];
		$this->SenderUserId = $dataProvider->Result["p_senderuserid"];
		$this->Text = $dataProvider->Result["p_text"];
		$this->Created = @filemtime($this->TargetFile);
	}
	
	function Save()
	{
		$actionfiles = getDirectory($this->Folder,true);
		foreach($actionfiles as $actionfile)
		{
			if(strpos($actionfile, "." . EX_CHAT_REQUEST_DECLINED) !== false)
				unlinkDataSet($this->Folder . $actionfile);
				
			if(strpos($actionfile, "." . EX_CHAT_REQUEST_ACCEPTED) !== false)
				unlinkDataSet($this->Folder . $actionfile);
				
			if(strpos($actionfile, "." . EX_CHAT_REQUEST) !== false)
				return false;
		}
		$dataProvider = new DataProvider($this->TargetFile);
		$dataProvider->Save($this->GetData(),true);
		return true;
	}
	
	function Decline()
	{
		renameDataSet($this->TargetFile,str_replace(".".$this->Extension,".".EX_CHAT_REQUEST_DECLINED,$this->TargetFile));
		$this->SetStatus(REQUEST_STATUS_DECLINED);
	}
	
	function Accept()
	{
		if(dataSetExists($this->TargetFile))
		{
			@touchDataSet($this->TargetFile);
			renameDataSet($this->TargetFile,str_replace(".".$this->Extension,".".EX_CHAT_REQUEST_ACCEPTED,$this->TargetFile));
		}
		$this->SetStatus(REQUEST_STATUS_ACCEPTED);
	}
	
	function GetCommand($_template,$_text,$_width,$_height)
	{
		return "lz_tracking_request_chat('" . base64_encode($this->Id) . "','".base64_encode($_template)."','" . base64_encode($_text) . "',".$_width.",".$_height.");";
	}
}

class PredefinedMessage
{
	var $Id = 0;
	var $LangISO = "";
	var $Invitation = "";
	var $Welcome = "";
	var $WebsitePush = "";
	var $BrowserIdentification = "";
	var $IsDefault = "";
	var $AutoWelcome = "";
	var	$GroupId = "";
	var	$UserId = "";
	function PredefinedMessage()
	{
		if(func_num_args() == 1)
		{
			$_row = func_get_arg(0);
			$this->LangISO = $_row["lang_iso"];
			$this->Invitation = $_row["invitation"];
			$this->Welcome = $_row["welcome"];
			$this->WebsitePush = $_row["website_push"];
			$this->BrowserIdentification = $_row["browser_ident"];
			$this->IsDefault = $_row["is_default"];
			$this->AutoWelcome = $_row["auto_welcome"];
		}
	}
	
	function XMLParamAlloc($_param,$_value)
	{
		if($_param =="inv")
			$this->Invitation = $_value;
		if($_param =="wp")
			$this->WebsitePush = $_value;
		if($_param =="bi")
			$this->BrowserIdentification = $_value;
		if($_param =="wel")
			$this->Welcome = $_value;
		if($_param =="def")
			$this->IsDefault = $_value;
		if($_param =="aw")
			$this->AutoWelcome = $_value;
	}
	
	function GetSQL()
	{
		return "INSERT INTO `".DB_PREFIX.DATABASE_PREDEFINED."` (`id` ,`internal_id` ,`group_id` ,`lang_iso` ,`invitation` ,`welcome` ,`website_push` ,`browser_ident` ,`is_default` ,`auto_welcome`)VALUES ('".mysql_real_escape_string($this->Id)."', '".mysql_real_escape_string($this->UserId)."','".mysql_real_escape_string($this->GroupId)."', '".mysql_real_escape_string($this->LangISO)."', '".mysql_real_escape_string($this->Invitation)."', '".mysql_real_escape_string($this->Welcome)."', '".mysql_real_escape_string($this->WebsitePush)."', '".mysql_real_escape_string($this->BrowserIdentification)."', '".mysql_real_escape_string($this->IsDefault)."', '".mysql_real_escape_string($this->AutoWelcome)."');";
	}
		
	function GetXML()
	{
		return "<pm lang=\"".base64_encode($this->LangISO)."\" inv=\"".base64_encode($this->Invitation)."\" wel=\"".base64_encode($this->Welcome)."\" wp=\"".base64_encode($this->WebsitePush)."\" bi=\"".base64_encode($this->BrowserIdentification)."\" def=\"".base64_encode($this->IsDefault)."\" aw=\"".base64_encode($this->AutoWelcome)."\" />\r\n";
	}
}

class Chat
{
	var $Activated;
	var $Closed;
	var $Declined;
	var $MemberCount;
	var $TargetFileExternal;
	var $TargetFileInternal;
	var $TargetFileInternalActivation;
	var $TargetFileExternalActivation;
	var $TargetFileInternalClosed;
	var $TargetFileInternalDeclined;
	var $InternalUser;
	var $ExternalUser;
	var $FileUploadRequest = null;
	
	function Chat()
	{
		if(func_num_args() == 3)
		{
			$this->ExternalUser = func_get_arg(0);
			$this->InternalUser = func_get_arg(1);
			$this->Id = func_get_arg(2);
			$this->SetDirectories();
		}
		else if(func_num_args() == 2)
		{
			$this->ExternalUser = func_get_arg(0);
			$this->InternalUser = func_get_arg(1);
			$this->SetDirectories();
		}
		else
		{
			$this->Load(func_get_arg(0));
		}
	}
	
	function SetDirectories()
	{
		$this->TargetFileExternal = $this->ExternalUser->Folder . $this->InternalUser->SessId . "." . EX_CHAT_OPEN;
		$this->TargetFileInternal = $this->InternalUser->Folder . $this->ExternalUser->BrowserId . "." . EX_CHAT_OPEN;
		$this->TargetFileExternalActivation = $this->InternalUser->Folder . $this->ExternalUser->BrowserId . "." . EX_CHAT_ACTIVE;
		$this->TargetFileInternalClosed = $this->ExternalUser->Folder . $this->InternalUser->SessId . "." . EX_CHAT_INTERN_CLOSED;
		$this->TargetFileInternalDeclined = $this->ExternalUser->Folder . $this->InternalUser->SessId . "." . EX_CHAT_INTERN_DECLINED;
		$this->TargetFileInternalActivation = $this->ExternalUser->Folder . $this->InternalUser->SessId . "." . EX_CHAT_ACTIVE;
		
		$this->Declined = (dataSetExists($this->TargetFileInternalDeclined));
		$this->Closed = (dataSetExists($this->TargetFileInternalClosed));
	}
	
	function IsActivated($_systemId)
	{
		$activated = false;
		$files = getDirectory($this->ExternalUser->Folder,false);
		foreach($files as $file)
			if(strpos($file,EX_CHAT_ACTIVE) !== false)
				if(isnull($_systemId) || (!isnull($_systemId) && strpos(trim($file),trim($_systemId)) !== false))
					$activated = true;
		
		$existance = array(dataSetExists($this->TargetFileExternalActivation),dataSetExists($this->TargetFileInternalActivation),dataSetExists($this->TargetFileInternal),dataSetExists($this->TargetFileExternal));
		$this->Activated = (($existance[0] && $existance[1]) ? CHAT_STATUS_ACTIVE : (($existance[0] || $existance[1]) ? CHAT_STATUS_WAITING : CHAT_STATUS_OPEN));
		if(!$this->Closed)
			$this->Closed = ($existance[0] && !$existance[1]);
		return $activated;
	}
	
	function GetData()
	{
		$data = Array();
		$data["s_internal_userid"] = $this->InternalUser->UserId;
		$data["s_internal_sessid"] = $this->InternalUser->SessId;
		$data["s_internal_fullname"] = $this->InternalUser->Fullname;
		$data["s_external_userid"] = $this->ExternalUser->UserId;
		$data["s_external_browserid"] = $this->ExternalUser->BrowserId;
		$data["s_id"] = $this->Id;
		return $data;
	}

	function InternalDecline()
	{
		$dataProvider = new DataProvider($this->TargetFileInternalDeclined);
		$dataProvider->Save(Array(),true);
	}
	
	function InternalClose()
	{
		$dataProvider = new DataProvider($this->TargetFileInternalClosed);
		$dataProvider->Save(Array(),true);
	}
	
	function InternalActivate($_internal)
	{
		$this->InternalUser = $_internal;
		if(!$this->IsActivated(null))
		{
			$this->TargetFileInternalActivation = $this->ExternalUser->Folder . $this->InternalUser->SessId . "." . EX_CHAT_ACTIVE;
			$dataProvider = new DataProvider($this->TargetFileInternalActivation);
			$dataProvider->Save(Array(),true);
		}
		else
		{
			unlinkDataSet($this->TargetFileInternal);
			unlinkDataSet($this->TargetFileExternalActivation);
		}
	}
	
	function ExternalActivate()
	{
		$dataProvider = new DataProvider($this->TargetFileExternalActivation);
		$dataProvider->Save(Array(),true);
	}
	
	function ExternalDestroy()
	{
		unlinkDataSet($this->TargetFileExternal);
		unlinkDataSet($this->TargetFileInternalActivation);
		unlinkDataSet($this->TargetFileInternalClosed);
		unlinkDataSet($this->TargetFileInternalDeclined);
	}
	
	function InternalDestroy()
	{
		unlinkDataSet($this->TargetFileExternalActivation);
		unlinkDataSet($this->TargetFileInternal);
	}
	
	function Load($_chatfile)
	{
		if(isnull($_chatfile))
			$dataProvider = new DataProvider($this->TargetFileExternal);
		else
			$dataProvider = new DataProvider($_chatfile);
		$dataProvider->Load();

		$this->Id = $dataProvider->Result["s_id"];
		$this->InternalUser = new Operator($dataProvider->Result["s_internal_sessid"],$dataProvider->Result["s_internal_userid"]);
		$this->ExternalUser = new ExternalChat($dataProvider->Result["s_external_userid"],$dataProvider->Result["s_external_browserid"]);
		
		$this->InternalUser->Fullname = $dataProvider->Result["s_internal_fullname"];
		$this->SetDirectories();
	}
	
	function Save()
	{
		$dataProvider = new DataProvider($this->TargetFileExternal);
		$dataProvider->Save($this->GetData(),true);
		
		$dataProvider = new DataProvider($this->TargetFileInternal);
		$dataProvider->Save($this->GetData(),true);
	}
}

class DataSet
{
	var $LastActive;
	var $Data;
	var $Name;
	var $Size;
}
?>