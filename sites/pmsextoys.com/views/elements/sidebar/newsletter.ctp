<?php
/**
 * SVN FILE: $Id: newsletter.ctp 472 2008-09-01 04:34:18Z jonathan $
 *
 * Element Sidebar Newsletter Box
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 472 $
 * Last Modified: $Date: 2008-09-01 00:34:18 -0400 (Mon, 01 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<div style=" width: 100%; background-color: #FDD0E2; height: 29px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Newsletter
				</div>				
			</td>
		</tr>
	</table>
</div>
<table width="224" cellspacing="12" cellpadding="0" border="0" bgcolor="#ececec">
	<tr>
		<td valign="top">	
		<table width="100%" cellspacing="0" cellpadding="0">
		</table>
		<br />
  		If you would like to sign up for our newsletter and recieve product updates and other information, please 
  		submit your email below. <br />	 <br />	
  			<div align="center">
    		<?
    		echo $form->create('Subscriber', array('url' => '/newsletter/signup'));
        	echo $form->input('Subscriber.email', array('class'=>'contact', 'label'=>'Enter Your Email'));
    		echo $form->submit('buttons/signup.gif');
        	echo $form->end();
    		?>
    		</div>
		</td>
		
	</tr>
</table>
<br /><br />