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
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Contact Us
				</div>				
			</td>
		</tr>
	</table>
</div>

<br />

<? if ($session->check('Message.flash')) $session->flash(); ?>

<div style="padding: 10px;">	
    
Please fill out the below form in order to contact us. If you feel that you need to speak to someone directly, 
you can call us at (954) 297-9283.
<br /><br />
Items marked with (<span style="color: #990000">*</span>) are required.
<br /><br />
<?
echo $form->create('Ticket', array('url' => '/contact'));
echo $form->input('Ticket.customer', array('class'=>'contact', 'label'=>'Full Name'));
echo $form->input('Ticket.email', array('class'=>'contact', 'style'=>'width: 250px'));
echo $form->hidden('Ticket.topic', array('value' => 'New Ticket Recieved', 'class'=>'contact', 'style'=>'width: 400px', 'label'=>'What can we help you with?'));
echo $form->input('Ticket.body', array('class'=>'contact', 'cols'=>100,'style'=>'width: 400px; height: 200px', 'label'=>'Message'));
echo $form->submit('buttons/submit.gif');
echo $form->end();
?>
</div>