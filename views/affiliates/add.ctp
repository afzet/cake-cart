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
 
if (empty($_SERVER['HTTPS'])) {
  echo '<meta http-equiv="refresh" content="0;url=https://passionmansion.com/affiliates/add">';
}
?>
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Affiliates
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
    <td>&nbsp;</td>
    <th style="padding: 5px">Login</th>
  </tr>
  <tr>
    <td valign="top" width="50%">  
      <p class="note">
        Create your own affiliate account today and start earning up to 30% commission on all your sales.
<br /><br />
Items marked with (<span style="color: #990000">*</span>) are required.
      </p>
      <?
      echo $form->create('Affiliate');
      echo $form->input('Affiliate.check_name', array('class'=>'contact','label'=>'Who do we make the check out to?','style' => 'width:250px'));
      echo $form->input('Affiliate.address', array('class'=>'contact','label'=>'Address', 'style' => 'width:250px'));
      echo $form->input('Affiliate.city', array('class'=>'contact','label'=>'City', 'style' => 'width:250px'));
      echo $form->input('Affiliate.state', array(
        'between' => '<span class="field_info">2 Letter State Abbreviation</span><br />',
        'maxLength' => 2,
        'class'=>'contact','label'=>'State', 'style' => 'width:20px'));
      echo $form->input('Affiliate.zip', array( 
        'between' => '<span class="field_info">5 Digit Zip Code</span><br />',
        'maxLength' => 5,
        'class'=>'contact','label'=>'Zip', 'style' => 'width:50px')
      );
      echo $form->input('Affiliate.phone', array('class'=>'contact','label'=>'Phone Number', 'style' => 'width:250px'));
      echo $form->input('Affiliate.mobile', array('class'=>'contact', 'label'=>'Mobile Number', 'style' => 'width:250px'));
      echo $form->input('Affiliate.contact_name', array('class'=>'contact','label'=>'Contact Person', 'style' => 'width:250px'));
      echo $form->input('date_of_birth', array('class'=>'contact','label' => 'Date of birth', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 70, 'maxYear' => date('Y') - 18 ));

      ?>
      
      <p class="note">
      When an order is placed, an email confirmation will be sent to the address above (leave blank if you don't want to receive order confirmation emails)  
      </p>       
      <?=$form->input('Affiliate.email_confirm', array('class'=>'contact','label'=>'Notifcation Email', 'style' => 'width:250px')); ?>
      
      <p class="note">
      The address above is the email we will use to contact you.
      </p>      
      <?=$form->input('Affiliate.email', array('class'=>'contact','label'=>'Contact Email', 'style' => 'width:250px')); ?>
      
      <p class="note">
      Main URL for your Company (Leave blank if you don't have a website).
      </p>
      <?=$form->input('Affiliate.website', array('class'=>'contact','label'=>'Main URL', 'style' => 'width:250px')); ?>
      
      <p class="note">
      If you don't live the US or if you are incorporated, you don't need to submit Tax ID or social security.
      <br /><br />
      The US government requires us to collect a social security number or Tax ID number for all non-incorporated US residents because if US residents get over $600 in commission, we need to send you a 1099 for tax purposes. If you don't send this info now, when you get over $600 in commission we will need this information or have to stop sending you money.
      </p>
      <?
      echo $form->input('Affiliate.ssn_unsafe', array(
        'between' => '<span class="field_info">Format 111-111-1111</span><br />',
        'class'=>'contact','label'=>'Social Security Number', 'style' => 'width:100px'));
      echo $form->input('Affiliate.tax_unsafe', array('class'=>'contact','label'=>'E.I.N.', 'style' => 'width:100px')); 
      ?>
      
      <p class="note">
      Please enter an affiliate unique affiliate code that will identify you in our system.
      </p>
      <?
      echo $form->input('Affiliate.aff_code', array(
        'maxLength' => 20,'class'=>'contact','label'=>'Affiliate Code', 'style' => 'width:100px')); 
      echo $form->input('Affiliate.password', array('class'=>'contact','label'=>'Password', 'style' => 'width:100px')); 
      echo $form->submit('buttons/register.gif');
      echo $form->end();
      ?>
    </td>    
    <td>&nbsp;</td>
    <td valign="top" width="50%">      
      <p class="note">
      If you already have an account you can login now.
      </p>
      <?
      echo $form->create('Affiliate',array('action'=>'login'));
      echo $form->input('Login.aff_code', array('class'=>'contact','label'=>'Affiliate Code','error'=>false));
      echo $form->input('Login.password', array('class'=>'contact','error'=>false));
      echo $form->submit('buttons/login.gif');
      echo $form->end();
      ?>
    </td>    
  </tr>
</table>