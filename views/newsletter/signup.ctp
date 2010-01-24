<?php
/**
 * SVN FILE: $Id: signup.ctp 465 2008-09-01 04:10:12Z jonathan $
 *
 * Mewsletter Signup View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 465 $
 * Last Modified: $Date: 2008-09-01 00:10:12 -0400 (Mon, 01 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
 <div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Newsletter Signup
				</div>				
			</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</div>
<br />
<div style="padding: 10px;">	
		If you would like to sign up for our newsletter and recieve product updates and other information, please 
		submit your email below. <br />	 <br />	
		<?
		echo $form->create('Subscriber', array('url' => '/newsletter/signup'));
    echo $form->input('Subscriber.email', array('class'=>'contact', 'label'=>'Please enter your email'));
		echo $form->submit('buttons/signup.gif');
    echo $form->end();
		?>
</div>