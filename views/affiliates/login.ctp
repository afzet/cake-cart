<?php
/**
 * SVN FILE: $Id: login.ctp 466 2008-09-01 04:27:47Z jonathan $
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
					Affiliate Login
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

echo $form->create('Affiliate',array('action'=>'login'));
echo $form->input('Login.aff_code', array('label'=>'Affiliate Code'));
echo $form->input('Login.password');
echo $form->submit('buttons/login.gif');
echo $form->end();
?>