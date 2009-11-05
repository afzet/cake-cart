<?php
/**
 * SVN FILE: $Id: nav_cart.ctp 138 2008-08-19 13:14:21Z jonathan $
 *
 * Element Navigation Cart View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 138 $
 * Last Modified: $Date: 2008-08-19 09:14:21 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>

<div style=" width: 100%; background-color: rgb(252, 214, 196); height: 29px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					<a href="/cart">My Cart</a>
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<? if (isset($cart) && count($cart['items'])>0) { ?>
<table width="100%" cellspacing="0" cellpadding="0">
<? 
$i = 1;
foreach ($cart['items'] as $item) { 
?>
	<tr>
		<td><a href="/cart/delete/<?=$i?>" style="color:red" onclick="return confirm('Are you sure?');"><img src="/img/icons/bullet_delete.png" alt="Delete" valign="middle" align="left" /></a></td>
		<td><?=$dojo->productLink($item)?><?=$item['Product']['product_name']?></a></td>
	</tr>
<? 
	$i++;
} 
?>
</table>
<?php
		$cart = $session->read('Cart');
		if(isset($cart['amt']['discount'])): 
		  $sub = $cart['amt']['shipping'] + $cart['amt']['sub_total'];
		  $total = $sub-$cart['amt']['discount'];
		else: 
		  $total = array_sum($cart['amt']);
		endif;
		?>
<p align="center"><br />
	<img src="/img/icons/cart.png" valign="middle" alt="" />
<strong>Total: $<?=number_format($total,2)?></strong><br /><br />
<? 
} else { 
	?>
<strong>You have 0 item in your cart.</strong>
<br /><br />
<? } ?>
</p>