<?php
/**
 * SVN FILE: $Id: index.ctp 526 2008-09-05 07:06:56Z jonathan $
 *
 * Cart Index View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 526 $
 * Last Modified: $Date: 2008-09-05 03:06:56 -0400 (Fri, 05 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Your Cart
				</div>				
			</td>
		</tr>
	</table>
</div>

<br />

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<div style="padding: 10px;">	

<?
if(isset($cart['items']) && count($cart['items'])!=0) {	
?>
<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr>
	<td height="22" class="cart" width="22">&nbsp;</td>
	<td class="cart" style="font-weight: bold">Description</td>
	<td class="cart" style="font-weight: bold; text-align:right" width="75">Price</td>
</tr>
	<?
	$i = 1;
	foreach ($cart['items'] as $item) {
	  $color = 'style="background: #f0f0f0"';
		if (ceil($i%2) == 0) $style = $color;
		else $style = '';
		echo '
			<tr '.$style.'>
				
				<td align="center" valign="middle" height="22"><a href="/cart/delete/'.$i.'" onclick="return confirm(\'Are you sure?\');"><img src="/img/icons/delete.png" alt="Delete" /></a></td>
				<td valign="top">
					'. $dojo->productLink($item) .'
						<img src="'.$item['Product']['product_thumb'].'" align="left" alt="'.$item['Product']['id'].'" style="padding-right: 10px; width:40px; height: 40px;" />
					</a>
					'. $dojo->productLink($item) .''.$item['Product']['product_name'].'</a><br />
					'.$item['Product']['product_code'].'
				</td>
				<td valign="top" style="text-align: right">$'.number_format($item['Product']['product_price'],2).'</td>
			</tr>
		';
		$i++;		
	}
?>
  <tr>
		<td colspan="3" valign="middle" height="22" class="cart" style="text-align: right">
			<form action="/promos/code" method="POST">
			  Enter Promo Code: <input type="text" name="data[Promo][code]" />
        <input type="submit" value="Add" />
			</form>
		</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
	<tr>
		<td colspan="3" valign="middle" height="22" class="cart" style="text-align: right">
			<form action="" method="get">
			Please Select A Country:
			<select onchange="goto_URL(this.form.country)" name="country" class="smaller" class="contact">
				<option value=""> Select Country</option>
				<option value="/cart/add_country/US">United States</option>
				<option value="/cart/add_country/AU">Australia</option>		
				<option value="/cart/add_country/CA">Canada</option>			
				<option value="/cart/add_country/OT">Other</option>				
			</select>
		</form>
		</td>
	</tr>
  
	<tr>
		<td colspan="2" height="22" valign="middle" class="cart" style="text-align: right">Sub Total: </td>
        <td valign="middle" class="cart" style="text-align: right">$<?=number_format($cart['amt']['sub_total'],2)?></td>
	</tr>
	<tr>
		<td colspan="2" valign="middle" height="22" class="cart" style="text-align: right">Shipping &amp; Handling: </td>
        <td valign="middle" class="cart" style="text-align: right">$<?=number_format($cart['amt']['shipping'],2)?></td>
	</tr>
	<?php if(isset($cart['amt']['discount'])): ?>
	<tr>
		<td colspan="2" valign="middle" height="22" class="cart" style="text-align: right">Discount (<?=@$cart['discount']['info']?>): </td>
        <td valign="middle" class="cart" style="text-align: right">$<?=number_format(@$cart['amt']['discount'],2)?></td>
	</tr>
	<?php endif; ?>
	<tr>
		<td colspan="2" valign="middle" height="22" class="cart" style="text-align: right">Total Due: </td>
		<?php
		if(isset($cart['amt']['discount'])): 
		  $sub = $cart['amt']['shipping'] + $cart['amt']['sub_total'];
		  $total = $sub-$cart['amt']['discount'];
		else: 
		  $total = array_sum($cart['amt']);
		endif;
		?>
    <td valign="middle" class="cart" style="text-align: right">$<?=number_format($total,2)?></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td colspan="3" style="text-align: right">	
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" accept-charset="utf-8">
				<input type="hidden" name="business" value="store@passionmansion.com">	
				<input type="hidden" name="cmd" value="_cart">
				<input type="hidden" name="upload" value="1">
				<input type="hidden" name="no_shipping" value="2">
				<input type="hidden" name="amount" value="<?=number_format(array_sum($cart['amt']),2)?>">
				<input type="hidden" name="return" value="https://passionmansion.com/orders/thank_you">
				<input type="hidden" name="handling_cart" value="<?=number_format($cart['amt']['shipping'],2)?>">
				<input type="hidden" name="no_note" value="1">
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="tax_cart" value="0.00">			
				<input type="hidden" name="custom" value="<?=$session->read('affiliate_code')?>">			

				
				<? $i = 1; foreach ($cart['items'] as $item) { ?>
					<input type="hidden" name="item_name_<?=$i?>" value="<?=$item['Product']['product_name']?>" />
					<input type="hidden" name="item_number_<?=$i?>" value="<?=$item['Product']['id']?>|<?=$item['Product']['product_code']?>" />
					<input type="hidden" name="amount_<?=$i?>" value="<?=$item['Product']['product_price']?>" />
					<input type="hidden" name="quantity_<?=$i?>" value="1" />
				<? 
					$i++;
				} 
				?>
				<br />
				<?php
				if(isset($cart['country']) && $cart['country']!='' && count($cart['items'])!=0) {	
				?>
				<input type="image" value="submit" src="/img/buttons/checkout.gif"/>
				<? } else { ?>
				<img src="/img/buttons/no-checkout.gif" alt="" />
				<? } ?>
			</form>
		</td>
	</tr>
</table>
<? 
} 
else {
?>
<p align="center">
	<strong>You have 0 item in your cart.</strong>
</p>
<br /><br />
<? 
}
?>

<p align="center"><img src="/img/credits.gif" alt="Accepted Payments" /></p>

<br /><br />
<table border="0" cellspacing="0" cellpadding"5" width="75%" style="margin: 0 auto;"> 
	<tr>
		<td colspan="4" style="text-align: center; font-weight: bold; background-color: rgb(252, 214, 196); height: 20px;">Do you wish to add batteries? </td>
	</tr>
	<tr>
		<td align="center" style="text-align: center;padding: 10px"><a href="/product_info/14485"><img src="/img/items/thumbnails/14485_1.jpg" alt="4pack AA $2.51" /> <br />4pack AA $2.51</a> <br /><a href="/cart/add/14485"><img style="vertical-align: middle;" alt="" src="/img/buttons/cart.gif" border="0" /></a></td>
		<td align="center" style="text-align: center;padding: 10px"><a href="/product_info/12015"><img src="/img/items/thumbnails/12015_1.jpg" alt="4pack AAA $2.51" /> <br />4pack AAA $2.51</a> <br /> <a href="/cart/add/12015"><img style="vertical-align: middle;" alt="" src="/img/buttons/cart.gif" border="0" /></a></td>
		<td align="center" style="text-align: center;padding: 10px"><a href="/product_info/10248"><img src="/img/items/thumbnails/10248_1.jpg" alt="2pack C $4.23" /> <br />2pack C $4.23</a>  <br /><a href="/cart/add/10248"><img style="vertical-align: middle;" alt="" src="/img/buttons/cart.gif" border="0" /></a></td>
	</tr>
	
</table>
</div>