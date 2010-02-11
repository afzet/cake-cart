
<div style=" width: 100%; background-color: #FDD0E2; height: 29px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Members
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table width="100%" cellspacing="0" cellpadding="4">
<?php
	if(isset($rep) && !empty($rep) && $rep['active'] == 1) {
		list($firstname, $lastname) = explode(' ', $rep['name']);
		echo '<tr><td style="text-align:center">Welcome '. $firstname .'! ('. $html->link('logout', '/reps/logout') .')</td></tr>';
	}
	else {
		echo '<tr><td style="text-align:center">'. $html->link('Login', '/reps/login') .' - '. $html->link('Register', '/parties/register') .'</td></tr>';
	}
?>
</table>
<br />