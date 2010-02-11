<?php
/**
 * SVN FILE: $Id: index.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Press Index View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<?
$currentUrl = isset($this->params['pass'])?$this->params['pass']:array();
$paginator->options(array('url' => $currentUrl));
?>
<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Press
				</div>				
			</td>
		</tr>
	</table>
</div>

<br />
<div class="smaller" style="padding: 10px" >
<strong>Media Contact </strong> <br /><br />
We love to talk to the press and will be happy to make a member of our team available for interviews, insights, sound bites and more! For media inquiries, please feel free to contact:
<br /><br />

Jonathan Bradley<br />

<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td valign="middle" height="26" width="20" align="center"><img src="/img/icons/telephone.png" /></td>
		<td>&nbsp;</td>
		<td valign="middle">+1 (954) 632-5387 </td>
	</tr>
	<tr>
		<td valign="middle" height="26" width="20" align="center"><img src="/img/icons/email.png" /></td>
		<td>&nbsp;</td>
		<td valign="middle"><a href="mailto:jonathan@passionmansion.com">jonathan@passionmansion.com</a></td>
	</tr>
</table>
<br /><br />

<strong>In The News </strong>
<br /><br />

<?
foreach($data as $item) {
	echo ' <a href="'.$item['Press']['link'].'" target="_blank"><img src="/img/icons/page.png" align="left" valign="middle" /></a>  &nbsp;
	
	'.$item['Press']['outlet'].' - <a href="'.$item['Press']['link'].'" target="_blank">'.$item['Press']['title'].'</a> 
	<br /><br />';
}
?>
</div>