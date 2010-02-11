<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<META NAME="robots" CONTENT="index,follow">
	<title><!--config_gl_site_name--></title>
	<link rel="stylesheet" type="text/css" href="./templates/style.css">
</head>
<body leftmargin="0" topmargin="0">
	<!--alert-->
	<div id="lz_chat_navigation_sub"></div>
	<div id="lz_chat_loading"><br><br><br><br><!--lang_client_loading--> ...</div>
	<!--errors-->
	<div id="lz_chat_login">
	<br>
		<form name="lz_login_form" method="post" action="./livezilla.php?template=lz_chat_frame.3.2.chat" target="lz_chat_frame.3.2">
		<table align="center" cellpadding="5" cellspacing="0" width="100%" border="0">
			<tr>
				<td align="center" valign="top">	
					<table cellpadding="5" height="0" width="100%">
						<tr>
							<td align="center" id="lz_chat_login_info_field"><!--info_text--></td>
						</tr>
					</table>
					<div id="lz_chat_login_values"><!--login_trap--></div>
					<div id="lz_chat_login_details">
						<table cellpadding="5" cellspacing="3">
							<tr>
								<td class="lz_chat_form_field" width="150"><strong><!--lang_client_your_name-->:</strong></td>
								<td width="220"><input id="lz_chat_login_name" name="login_name" class="lz_chat_login_box"  value="<!--login_value_name-->" maxlength="32" onkeydown="if(event.keyCode==13){return document.getElementById('login_email').focus();}"></td>
							</tr>
							<tr>
								<td class="lz_chat_form_field"><strong><!--lang_client_your_email-->:</strong></td>
								<td><input id="lz_chat_login_email" name="login_email" class="lz_chat_login_box" value="<!--login_value_email-->" maxlength="50" onkeydown="if(event.keyCode==13){return document.getElementById('login_company').focus();}"></td>
							</tr>
							<tr>
								<td class="lz_chat_form_field"><!--lang_client_your_company-->:</td>
								<td><input id="lz_chat_login_company" name="login_company" class="lz_chat_login_box" value="<!--login_value_company-->" maxlength="50" onkeydown="if(event.keyCode==13){return lz_chat_checkInputs();}"></td>
							</tr>
							<tr>
								<td class="lz_chat_form_field"><strong><!--lang_client_group-->:</strong></td>
								<td valign="middle">
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td><select id="lz_chat_login_groups" name="intgroup" onChange="top.lz_chat_change_group();"></select></td>
											<td width="25" align="right"><img id="lz_chat_groups_loading" src="./images/icon_network.gif" alt="" width="16" height="16" border="0">
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="40"></td>
								<td><input type="button" onclick="top.lz_chat_check_login_inputs();" id="lz_chat_login_button" disabled></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
		</form>
	</div>
</body>
</html>
