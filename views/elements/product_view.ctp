<?php
/**
 * SVN FILE: $Id: product_view.ctp 122 2008-08-19 12:29:24Z jonathan $
 *
 * Element Product View View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 122 $
 * Last Modified: $Date: 2008-08-19 08:29:24 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr><td style="height: 2px; font-size: 0px; border-top: 1px dotted #FCD6C4" colspan="11">&nbsp;</td></tr>
	<tr>
<?
$i = 1;
	$ttl = count($data);
	foreach ($data as $product) {
		if ((ceil($i%8)%4) == 1)  { 
			echo '<td width="15" style="border-right: 1px dotted #FCD6C4" />';
		} else {
			echo '<td width="15" />';
		}
		
		if ($ttl>=4) { $width = "25%"; }
		else { $width = 100%$ttl.'%'; }
		?>
		<td valign="bottom" width="<?=$width?>" style="font-size: 10px; text-align:center; padding: 8px; border-right: 1px dotted #FCD6C4">
			<a href="/product_info/<?=$product['Product']['id'];?>">
				<img src="<?=$product['Product']['product_thumb'];?>" />				
			</a>	
			<br />
			<a href="/product_info/<?=$product['Product']['id'];?>">
				<strong><?=$product['Product']['product_name'];?></strong>
			</a> 
			<br /><br />
			<?=$product['Product']['product_code'];?>
			<br />
			<?=$product['Product']['product_list'];?>
			<br /><br />
			Price: $<?=number_format($product['Product']['product_price'],2);?> 
			<br /><br />								
				<a href="/cart/add/<?=$product['Product']['id'];?>">
					<img style="vertical-align: middle;" alt="" src="/img/buttons/cart.gif" border="0" />
				</a>
			<br />
		</td>			
	<?
		if ((ceil($i%8)%4) == 0 && $i!=$ttl)  { 
			echo '
			<td width="10"/>
			</tr>
			<tr><td colspan="11" style="height: 0px; font-size: 0px;">&nbsp;</td></tr>
			<tr><td style="height: 2px; font-size: 0px; border-top: 1px dotted #FCD6C4" colspan="11">&nbsp;</td></tr>
			<tr>';
		}		
	$i++;
}
?>
</tr><tr><td style="height: 2px; font-size: 0px; border-top: 1px dotted #FCD6C4" colspan="11">&nbsp;</td></tr>
</table>