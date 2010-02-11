<?php
/**
 * SVN FILE: $Id: confirmation.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Element Email HTML Newsletter Confirmation Template
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
		<table border="0" cellspacing="0" cellpadding="0" id="main">
	<tr>
				<td class="info">
					Thank you <?php echo $data['OrderCustomer']['first_name']?>, 
					<br /><br />
					We wanted to take this moment and thank you for shopping with Passion Mansion. We have included a 
					copy of your order, as well as how to track your order to help ensure the best experiance possible 
					while shopping with us. If you have any question regarding this order, please do not hesitate to 
					contact us at 
					<a href="mailto:sales@passionmansion.com?subject=Invoice ID dlmz<?=$data['Order']['id']?>">sales@passionmansion.com</a> and one 
					of our sales reprensentatives will contact you as soon as possible.
					<br /><br />
					Thank you,<br />
					Passion Mansion
					<br /><Br />
					<div style="width: 350px; margin: 0 auto;">						
					<strong>Please use the following steps to track your package</strong> <br /><br />
					<ol>
						<li>Visit to <a href="http://passionmansion.com/orders/track">http://passionmansion.com/orders/track</a></li>
						<li>Enter your Invoice ID as show below: dlmz<?=$data['Order']['id']?></li>
						<li>Enter your Zip: <?php echo $data['OrderCustomer']['address_zip']?></li>
					</ol>	<br />
					<span class="smaller">
					Reminder: It can take up to 2-3 days for your order to show up in the order tracking system.</span>
					</div>
					<br />
<table border="0" cellspacing="5" cellpadding="5" width="100%">
	<tr>
		<td valign="top" width="50%">
		<table border="0" cellspacing="5" cellpadding="5" width="100%">
			<tr><th colspan="2" style="padding:4px">Transaction Information</th></tr>
			<tr>
				<td width="40%" style="font-weight: bold">Transaction ID: </td>
				<td><?php echo $data['Order']['id']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold">Payment Date: </td>
				<td><?php echo $data['Order']['payment_date']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold">Program Type: </td>
				<td><?php echo $data['Order']['payment_type']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold">Invoice ID: </td>
				<td>dlmz<?=$data['Order']['id']?></td>
			</tr>
		</table>
		</td>
		<td width="10">&nbsp;</td>
		<td valign="top" width="50%">
			<table border="0" cellspacing="5" cellpadding="5" width="100%">
				<tr><th colspan="2" style="padding:4px">Shipping Information</th></tr>
				<tr>
					<td width="40%" style="font-weight: bold">Name: </td>
					<td><?php echo $data['OrderCustomer']['address_name']?></td>
				</tr>
				<tr>
					<td style="font-weight: bold">Address: </td>
					<td><?php echo $data['OrderCustomer']['address_street']?></td>
				</tr>
				<tr>
					<td style="font-weight: bold">City: </td>
					<td><?php echo $data['OrderCustomer']['address_city']?></td>
				</tr>
				<tr>
					<td style="font-weight: bold">State: </td>
					<td><?php echo $data['OrderCustomer']['address_state']?></td>
				</tr>
				<tr>
					<td style="font-weight: bold">Postal Code: </td>
					<td><?php echo $data['OrderCustomer']['address_zip']?></td>
				</tr>
				<tr>
					<td style="font-weight: bold">Country: </td>
					<td><?php echo $data['OrderCustomer']['address_country']?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colpan="3">&nbsp;</tr>
	<tr>
		<td colspan="3">
			<table border="0" cellspacing="0" cellpadding="20" width="100%">
				<tr>
					<th style="padding:4px">Item #</th>
					<th style="padding:4px">Product Information</th>
					<th style="padding:4px">Qty</th>
					<th style="padding:4px">Price</th>
				</tr>
				<?
				for($i=1; $i<=$data['Order']['num_cart_items']; $i++) {
				?>
				<tr>
					<td valign="top" style="padding:5px"><?php echo $data['Item'][$i]['product_code']?></td>
					<td valign="top" style="padding:5px"><?php echo $data['Item'][$i]['item_name']?></td>
					<td valign="top" style="padding:5px; text-align: center;"><?php echo $data['Item'][$i]['quantity']?></td>
					<td valign="top" style="padding:5px; text-align: right;">$<?php echo $data['Item'][$i]['price']?></td>
				</tr>
				<?
				}				
				?>
				<tr>
					<td colspan="3" style="padding:5px;text-align: right; border-top: 1px #ccc solid">Shipping & Handling: </td>
					<td style="padding:5px;text-align: right; border-top: 1px #ccc solid">$<?php echo $data['Order']['mc_handling']?></td>
				</tr>
				<tr>
					<td colspan="3" style="padding:5px;text-align: right;">Total: </td>
					<td style="padding:5px;text-align: right;">$<?php echo $data['Order']['mc_gross']?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br /><br />
<br /><br />
					
						Thank you for your business!
					</p>
				<td>
			</tr>
		</table>