<?php
/**
 * SVN FILE: $Id: success.ctp 466 2008-09-01 04:27:47Z jonathan $
 *
 * Mewsletter Signup Success View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 466 $
 * Last Modified: $Date: 2008-09-01 00:27:47 -0400 (Mon, 01 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
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
		Thank you for signing up for our newsletter. We have sent an email to your address that your provided 
		in order to validate the email is yours. 
		<br /><br />
		Please check your email in the next few seconds and enter the 
		confirmation code you have recieved in the box below:
	<td>
</tr>
<tr>
	<td class="info" align="center">	
		<?
		echo $form->create('Subscriber', array('url' => '/newsletter/confirm'));
    echo $form->input('Subscriber.validation_code', array('class'=>'contact', 'label'=>'Enter Validation Code'));
		echo $form->submit('buttons/validate.gif');
    echo $form->end();
		?>
	<td>
</tr>
</table>