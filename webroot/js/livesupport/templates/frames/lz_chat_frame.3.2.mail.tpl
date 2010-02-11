<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<META NAME="robots" CONTENT="index,follow">
	<title><!--config_gl_site_name--></title>
	<link rel="stylesheet" type="text/css" href="./templates/style.css">
</head>
<body leftmargin="0" topmargin="0" onload="document.getElementById('lz_chat_mail_details').style.display='block';">
	<!--alert-->
	<div class="lz_chat_navigation">
	<table width="100%" height="100%" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="10"></td>
			<td id="lz_chat_site_title">
				<!--config_gl_site_name-->&nbsp;&raquo;&nbsp;<!--lang_client_leave_message_short-->
			</td>
			<td align="right">
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td><img src="./images/chat_bg_navigation_left.gif" alt="" width="10" height="30" border="0"></td>
						<td><img class="lz_chat_clickable_image" onclick="top.lz_chat_close();" src="./images/button_chat.gif" border="0" title="Live Chat" alt=""></td>
						<td><img src="./images/chat_bg_navigation_dev.gif" alt="" width="10" height="30" border="0"></td>
						<td><img class="lz_chat_clickable_image" onclick="top.lz_chat_mail_print();" src="./images/button_print.gif" border="0" title="<!--lang_client_print-->" alt=""></td>
						<td><img src="./images/chat_bg_navigation_dev.gif" alt="" width="10" height="30" border="0"></td>
						<td><img class="lz_chat_clickable_image" onclick="top.lz_chat_bookmark();" src="./images/button_fav.gif" border="0" title="<!--lang_client_bookmark-->" alt=""></td>
						<td><img src="./images/chat_bg_navigation_dev.gif" alt="" width="10" height="30" border="0"></td>
						<td><img class="lz_chat_clickable_image" onclick="top.close();" src="./images/button_close.gif" border="0" title="<!--lang_client_close_window-->" alt=""></td>
						<td><img src="./images/chat_bg_navigation_right.gif" alt="" width="10" height="30" border="0"></td>
					</tr>
				</table>
			</td>
			<td width="5"></td>
		</tr>
	</table>
	</div>
	<div id="lz_chat_navigation_sub"></div>
	<form name="lz_login_form" method="post" action="./livezilla.php?template=lz_chat_frame.3.2.chat" target="lz_chat_frame.3.2" style="padding:0px;margin:0px;">
	<table align="center" cellpadding="2" cellspacing="0" width="100%">
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td align="center" valign="top">	
				<div id="lz_chat_mail_values"><!--login_trap--></div>
				<div id="lz_chat_mail_details">
					<table cellpadding="5" cellspacing="3">
						<tr>
							<td class="lz_chat_form_field" width="150"><strong><!--lang_client_your_name-->:</strong></td><td width="220"><input name="name" id="lz_chat_mail_name" class="lz_chat_mail_box" value="<!--login_value_name-->" maxlength="32"></td>
						</tr>
						<tr>
							<td class="lz_chat_form_field"><strong><!--lang_client_your_email-->:</strong></td><td><input name="email" id="lz_chat_mail_email" class="lz_chat_mail_box" value="<!--login_value_email-->" maxlength="50"></td>
						</tr>
						<tr>
							<td class="lz_chat_form_field"><!--lang_client_your_company-->:</td><td><input name="company" id="lz_chat_mail_company" class="lz_chat_mail_box" value="<!--login_value_company-->" maxlength="50"></td>
						</tr>
						<tr>
							<td class="lz_chat_form_field"><!--lang_client_your_message-->:</td><td valign="top"><textarea name="text" id="lz_chat_mail_message" class="lz_chat_mail_box"></textarea></td>
						</tr>
						<tr>
							<td class="lz_chat_form_field"><strong><!--lang_client_group-->:</strong></td>
							<td valign="middle">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td><select id="lz_chat_mail_groups"><!--groups--></select></td>
										<td width="25" align="right"><img id="lz_chat_groups_loading" src="./images/icon_network.gif" alt="" width="16" height="16" border="0">
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td></td><td height="40"><input type="button" onclick="top.lz_chat_check_mail_inputs();" value="<!--lang_client_send_message-->" id="lz_chat_mail_button"></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
	</form>
</body>
</html>
