<?php
/**
 * SVN FILE: $Id: remove.ctp 466 2008-09-01 04:27:47Z jonathan $
 *
 * Mewsletter Remove View
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
					Remove Email
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
	    Please enter your email:
		<form action="/newsletter/remove" method="post" accept-charset="utf-8">
    		<?=$form->input('Subscriber.email', array('style'=>'padding:4px; font-size: 11px; width: 160px', 'label'=>''))?> <br />	
    		<input type="submit" value="Remove" />	
		</form>
		<?
		echo $form->create('Subscriber', array('url' => '/newsletter/remove'));
    echo $form->input('Subscriber.email', array('class'=>'contact', 'label'=>'Enter your email'));
		echo $form->submit('buttons/remove.gif');
    echo $form->end();
		?>
	<td>
</tr>
</table>