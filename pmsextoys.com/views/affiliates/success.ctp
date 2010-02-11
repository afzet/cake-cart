<?php
/**
 * SVN FILE: $Id: success.ctp 391 2008-09-01 00:57:28Z jonathan $
 *
 * Mewsletter Signup Success View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 391 $
 * Last Modified: $Date: 2008-08-31 20:57:28 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Signup Succesful
				</div>				
			</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</div>
<table border="0" cellspacing="5" cellpadding="0" id="main">
<tr>
	<td class="info">
		<br /><br />
		Thank you for signing as an affiliate. We have sent an email to your address that your provided 
		in order to validate the email is yours. 
		<br /><br />
		Please check your email in the next few seconds and enter the 
		confirmation code you have recieved in the box below:
	<td>
</tr>
<tr>
	<td class="info" align="center">		
		<form action="/affiliates/confirm" method="post" accept-charset="utf-8">
    		<?php echo $form->input('Affiliate.verification', array('style'=>'padding:4px; font-size: 11px; width: 160px', 'label'=>''))?> <br />	
    		<input type="submit" value="Enter Validation Code" />	
		</form>
	<td>
</tr>
</table>