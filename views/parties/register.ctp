<?php
/**
 * SVN FILE: $Id: add.ctp 507 2008-09-05 02:05:06Z jonathan $
 *
 * Contact Index View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 507 $
 * Last Modified: $Date: 2008-09-04 22:05:06 -0400 (Thu, 04 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 
?>
<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
					Register to hold Home Parties
				</div>				
			</td>
		</tr>
	</table>
</div>

<div style="padding: 10px;">	  
<?php
if ($session->check('Message.flash')) {
	$session->flash();	
}
?>
<table border="0" cellspacing="5" cellpadding="5">
  <tr>    
    <th style="padding: 5px">Signup</th>
  </tr>
  <tr>
    <td valign="top" width="50%">  
      <p class="note">
        Sign up to sell sextoys at your very own passion parties.
<br /><br />
Items marked with (<span style="color: #990000">*</span>) are required.
      </p>
      <?
      echo $form->create('Party', array('action' => 'register'));
      echo $form->input('Party.name', array('class'=>'contact','label'=>'Contact Name','style' => 'width:250px'));
      echo $form->input('Party.address', array('class'=>'contact','label'=>'Address', 'style' => 'width:250px'));
      echo $form->input('Party.city', array('class'=>'contact','label'=>'City', 'style' => 'width:250px'));
      echo $form->input('Party.state', array(
        'between' => '<span class="field_info">2 Letter State Abbreviation</span><br />',
        'maxLength' => 2,
        'class'=>'contact','label'=>'State', 'style' => 'width:20px'));
      echo $form->input('Party.zip', array( 
        'between' => '<span class="field_info">5 Digit Zip Code</span><br />',
        'maxLength' => 5,
        'class'=>'contact','label'=>'Zip', 'style' => 'width:50px')
      );
      echo $form->input('Party.phone', array('class'=>'contact','label'=>'Phone Number', 'style' => 'width:250px'));
      echo $form->input('Party.mobile', array('class'=>'contact', 'label'=>'Mobile Number', 'style' => 'width:250px'));
      echo $form->input('Party.date_of_birth', array('class'=>'contact','label' => 'Date of birth', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y') - 18 ));

      ?>
      
      <p class="note">
      When an order is placed, an email confirmation will be sent to the address above (leave blank if you don't want to receive order confirmation emails)  
      </p>       
      <?=$form->input('Party.email_confirm', array('class'=>'contact','label'=>'Notifcation Email', 'style' => 'width:250px')); ?>
      
      <p class="note">
      The address above is the email we will use to contact you.
      </p>      
      <?=$form->input('Party.email', array('class'=>'contact','label'=>'Contact Email', 'style' => 'width:250px')); ?>
      
      <p class="note">
      Main URL for your Company (Leave blank if you don't have a website).
      </p>
      <?=$form->input('Party.website', array('class'=>'contact','label'=>'Main URL', 'style' => 'width:250px')); ?>
      
      <?
      echo $form->submit('buttons/register.gif');
      echo $form->end();
      ?>
    </td>    
  </tr>
</table>