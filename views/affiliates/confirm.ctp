<?php
/**
 * SVN FILE: $Id: confirm.ctp 391 2008-09-01 00:57:28Z jonathan $
 *
 * Mewsletter Vlidate Email View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 391 $
 * Last Modified: $Date: 2008-08-31 20:57:28 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Confirm Email
				</div>				
			</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</div>
<?
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table border="0" cellspacing="5" cellpadding="0" id="main">
<tr>
	<td class="info">		
	    Please enter your confirmation code:
		<form action="/affiliates/confirm" method="post" accept-charset="utf-8">
    		<?=$form->input('Affiliate.validation', array('style'=>'padding:4px; font-size: 11px; width: 160px', 'label'=>''))?> <br />	
    		<input type="submit" value="Enter Validation Code" />	
		</form>
	<td>
</tr>
</table>