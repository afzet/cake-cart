<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<META NAME="robots" CONTENT="index,follow">
	<title><!--config_gl_site_name--></title>
	<link rel="stylesheet" type="text/css" href="./templates/style.css">
	<script language="JavaScript">
	var lz_chat_rate_file_0 = "./images/chat_rating_star0.gif";
	var lz_chat_rate_file_1 = "./images/chat_rating_star1.gif";
	var lz_chat_rate_settings = new Array(0,0);
	var lz_chat_rate_comment_started = false;
	var lz_chat_rate_comment_sent = false;
	
	function SwitchStar(_id,_number,_value)
	{
		if(lz_chat_rate_comment_sent)
			return;
			
		if(_value)
			for(var i = 1;i<=_number;i++)
				document.getElementById("lz_chat_star_" + _id + "_" + i).src = lz_chat_rate_file_1;
		else
			for(var i = 1;i<=5;i++)
				if(i > lz_chat_rate_settings[_id])
					document.getElementById("lz_chat_star_" + _id + "_" + i).src = lz_chat_rate_file_0;
	}
	
	function SetRate(_id,_number)
	{
		if(lz_chat_rate_comment_sent)
			return;
			
		if(_number < lz_chat_rate_settings[_id])
			for(var i = 5;i>_number;i--)
				document.getElementById("lz_chat_star_" + _id + "_" + i).src = lz_chat_rate_file_0;
	
		lz_chat_rate_settings[_id] = _number;
	}
	
	function PrepareRateComment()
	{
		if(lz_chat_rate_comment_sent)
			return;
			
		if(!lz_chat_rate_comment_started)
			document.getElementById("lz_chat_rate_comment").value="";
		lz_chat_rate_comment_started=true;
	}
	
	function ValidateRating()
	{
		if(lz_chat_rate_settings[0] == 0 || lz_chat_rate_settings[1] == 0)
		{
			top.lz_chat_chat_alert(top.lz_chat_data.Language.SelectRating,top.frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.chat.4.0']);
			return;
		}
			
		if(!lz_chat_rate_comment_started)
			document.getElementById("lz_chat_rate_comment").value = "";
			
		if(document.getElementById("lz_chat_rate_comment").value.length > 400)
		{
			top.lz_chat_chat_alert(top.lz_chat_data.Language.MessageTooLong + " (-" + (document.getElementById("lz_chat_rate_comment").value.length-400).toString() + ")",top.frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.chat.4.0']);
			return;
		}
		
		lz_chat_rate_comment_sent = 
		document.getElementById("lz_chat_rate_comment").disabled = 
		document.getElementById("lz_chat_rate_button").disabled = true;
		top.lz_chat_send_rate(lz_chat_rate_settings[0],lz_chat_rate_settings[1],document.getElementById("lz_chat_rate_comment").value);
	}
	
	function RatingCallback(_message)
	{
		top.lz_chat_switch_rating();
		top.lz_chat_chat_alert(_message,top.frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.chat.4.0']);
	}
	</script>
</head>
<body id="lz_chat_body_chat_function">
	<table cellspacing="5" cellpadding="0" width="100%">
		<tr>
			<td></td>
			<td width="200">
				<table cellpadding="3">
					<tr>
						<td><span class="lz_lb_title3"><!--lang_client_rate_qualification-->:</span></td>
						<td><!--rate_1--></td>
						<td rowspan="2"></td>
					</tr>
					<tr>
						<td><span class="lz_lb_title3"><!--lang_client_rate_politeness-->:</span></td>
						<td><!--rate_2--></td>
					</tr>
				</table>
			</td>
			<td><textarea id="lz_chat_rate_comment" onclick="PrepareRateComment();"><!--lang_client_rate_reason--></textarea></td>
			<td align="center" width="70"><input type="button" id="lz_chat_rate_button" onclick="ValidateRating();" value="<!--lang_client_send-->"></td>
			<td></td>	
		</tr>
	</table>
</body>
</html>
