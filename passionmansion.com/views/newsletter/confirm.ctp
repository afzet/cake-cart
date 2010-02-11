<?php
/**
 * SVN FILE: $Id: confirm.ctp 466 2008-09-01 04:27:47Z jonathan $
 *
 * Mewsletter Vlidate Email View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 466 $
 * Last Modified: $Date: 2008-09-01 00:27:47 -0400 (Mon, 01 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
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
		<?
		echo $form->create('Subscriber', array('url' => '/newsletter/confirm'));
    echo $form->input('Subscriber.validation_code', array('class'=>'contact', 'label'=>'Enter Validation Code'));
		echo $form->submit('buttons/validate.gif');
    echo $form->end();
		?>
	<td>
</tr>
</table>