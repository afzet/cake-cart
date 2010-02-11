<?php
/**
 * SVN FILE: $Id: index.ctp 466 2008-09-01 04:27:47Z jonathan $
 *
 * Contact Index View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 466 $
 * Last Modified: $Date: 2008-09-01 00:27:47 -0400 (Mon, 01 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Rep Login
				</div>				
			</td>
		</tr>
	</table>
</div>

<br />

<? if ($session->check('Message.flash')) $session->flash(); ?>

<div style="padding: 10px;">	
    
If you need to sign up to host passion parties please <?php echo $html->link('sign up here', '/parties/register'); ?>.
<br /><br />
Items marked with (<span style="color: #990000">*</span>) are required.
<br /><br />
<?
echo $form->create('Rep', array('action' => 'login', 'type' => 'post'));
echo $form->input('email', array('class'=>'contact', 'style'=>'width: 250px'));
echo $form->input('password', array('class'=>'contact', 'style'=>'width: 250px'));
echo $form->submit('buttons/login.gif');
echo $form->end();
?>
</div>