<?php
/**
 * SVN FILE: $Id: profile.ctp 420 2008-09-01 02:13:20Z jonathan $
 *
 * Mewsletter Signup Success View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 420 $
 * Last Modified: $Date: 2008-08-31 22:13:20 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Affiliate Account Information
				</div>				
			</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</div>

<div style="padding: 10px;">
  <table width="600" cellspacing="0" cellpadding="0" align="left">
    <tr>
      <th colspan="2" style="padding: 4px; text-align: left;">Affiliate Details</th>
    </tr>
    <tr>
    	<td class="profile" width="200">Customer Number</td>
    	<td class="profile"><?=strtoupper($affiliate['customer_code'])?></td>
    </tr>
    <tr>
    	<td class="profile">Affiliate Code</td>
    	<td class="profile"><?=$affiliate['aff_code']?></td>
    </tr>
    <tr>
    	<td class="profile">Checks Payable To:</td>
    	<td class="profile"><?=ucwords($affiliate['check_name'])?></td>
    </tr>
    <tr>
      <th colspan="2" style="padding: 4px; text-align: left;">Contact Details</th>
    </tr>
    <tr>
    	<td class="profile">Contact Person</td>
    	<td class="profile"><?=ucwords($affiliate['contact_name'])?></td>
    </tr>
    <tr>
    	<td class="profile" valign="top">Address:</td>
    	<td class="profile">
    	  <?=ucwords($affiliate['address'])?><br />
    	  <?=ucwords($affiliate['city'])?>, <?=strtoupper($affiliate['state'])?> <?=$affiliate['zip']?><br />
    	  <?=$affiliate['country']?>
    	</td>
    </tr>
    <tr>
      <th colspan="2" style="padding: 4px; text-align: left;">Your Statistics</th>
    </tr>
    <tr>
    	<td class="profile">Amount Earned:</td>
    	<td class="profile">$<?=number_format($sales['amount'],2)?></td>
    </tr>
    <tr>
    	<td class="profile">Amount Paid To Date:</td>
    	<td class="profile">$<?=number_format($paid,2)?></td>
    </tr>
    <tr>
    	<td class="profile">Amount Owed:</td>
    	<td class="profile">$<?=number_format($sales['amount']-$paid,2)?></td>
    </tr>
    <tr>
    	<td class="profile">Total Sales:</td>
    	<td class="profile"><?=number_format($sales['sales'])?></td>
    </tr>
    <tr>
    	<th style="padding: 4px;">Date of Payment</th>
    	<th class="profile">Amount</th>
    </tr>
    <? foreach ($payments as $payment) {?>
    <tr>
    	<td class="profile" style="text-align: center;"><?=$payment['Payment']['created']?></td>
    	<td class="profile" style="text-align: center;">$<?=$payment['Payment']['amount_paid']?></td>
    </tr>
    <? } ?>
    <? if(empty($payments)) { ?>
    <tr>
      <td colspan="2" style="padding: 4px; text-align: left;">
    	  Sorry No Current Sales
      </td>
    </tr>
    <? } ?>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><th colspan="2" style="padding: 4px; text-align: left;">Your Tracking Link</th></tr>
      <td colspan="2" style="padding: 4px; text-align: left;">
        
        <a href="http://passionmansion.com/?a=<?=$affiliate['aff_code']?>e">http://passionmansion.com/?a=<?=$affiliate['aff_code']?></a><br />
      </td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><th colspan="2" style="padding: 4px; text-align: left;">Marketing Tools</th></tr>
      <td colspan="2" style="padding: 4px; text-align: left;">
        <strong>Banners</strong> - <a href="http://passionmansion.com/docs/advertise">http://passionmansion.com/docs/advertise</a><br />
        <strong>Whats New RSS Feed</strong> - <a href="http://passionmansion.com/rss/new.xml">http://passionmansion.com/rss/new.xml</a> <br />
        <strong>Most Popular New RSS Feed</strong> -  <a href="http://passionmansion.com/rss/popular.xml">http://passionmansion.com/rss/popular.xml</a><br />
        <strong>XML Product Feed</strong> - <a href="http://passionmansion.com/feed.xml">http://passionmansion.com/feed.xml</a><br />
      </td>
    </tr>
  </table>

</div>