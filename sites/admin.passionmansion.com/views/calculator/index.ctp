
<h2>Estimate Calculator</h2>
<table>
<tr>
<td style="text-align: left">
<?
echo $form->create('Calculator', array('url' => '/calculator/add'));
echo $form->input('Product.product_code', array('style'=>'width:200px;'));
echo $form->input('Product.product_name', array('style'=>'width:200px;'));
echo $form->submit();
echo $form->end();
?>
</td>
<td></td>
<td style="text-align: left">

<?
if ($session->check('Message.flash')) {
	$session->flash();
}
if(isset($products)) {	

	foreach ($products as $product) {
		if (preg_match('/^PMXR-/',$product['product_code']) == false) {
			$vendor = 'stc';
		} else {
			$vendor = 'xr';
		}	
	}
echo $this->renderElement('countries');

if (isset($data['country'])) $country = $data['country'];
else $country = 'none';
?>
<strong>Country:</strong> <img src="/img/flags/<?=strtolower($country)?>.png" valign="middle" /><br />
<table style="width: 550px">
  <tr>
    <td style="border-bottom: 1px solid #c0c0c0; text-align:left; font-size: 10px"></td>
    <td style="border-bottom: 1px solid #c0c0c0; text-align:left; font-size: 10px"><strong>SKU#</strong></td>
    <td style="border-bottom: 1px solid #c0c0c0; text-align:left; font-size: 10px"><strong>Product</strong></td>
    <td style="border-bottom: 1px solid #c0c0c0; text-align:right; font-size: 10px"><strong>XR Cost</strong></td>
    <td style="border-bottom: 1px solid #c0c0c0; text-align:right; font-size: 10px"><strong>STC Cost</strong></td>
</tr>
<?
	$i = 0;
	foreach ($products as $product) {
		echo '<tr>';
		echo '<td style="text-align:left; font-size: 10px">'.$html->link('delete','/calculator/delete/'.$product['key']).'</td>';
		echo '<td style="text-align:left; font-size: 10px">'.$product['product_code'].'</td>';
		echo '<td style="text-align:left; font-size: 10px">'.$product['product_name'].'</td>';
		echo '<td style="text-align:right; font-size: 10px">$'.number_format($product['xr_cost'],2).'</td>';
		echo '<td style="text-align:right; font-size: 10px">$'.number_format($product['product_cost'],2).'</td>';
		echo '</tr>';
		
		$prices[$i] = $product['product_cost'];
		
		if (preg_match('/^PMXR-/',$product['product_code']) == true) $xr_cost[$i] = $product['xr_cost'];
		$i++;
		
	}

$cost = array_sum($prices);
$cost2 = array_sum($xr_cost);
		
if (isset($data['country'])) {
  if(count($xr_cost)>=1) {
    switch ($data['country']) {
  		case "US":
    		     if ($cost2 >   0 &&  $cost2 <  99) { $shipping2 =  9.80; }
    		else if ($cost2 > 100 &&  $cost2 < 199) { $shipping2 = 14.80; }
    		else if ($cost2 > 200 &&  $cost2 < 299) { $shipping2 = 15.80; }
    		else if ($cost2 > 300 &&  $cost2 < 399) { $shipping2 = 17.80; }
    		else if ($cost2 > 400 &&  $cost2 < 499) { $shipping2 = 19.80; }
    		else if ($cost2 > 500 &&  $cost2 < 699) { $shipping2 = 28.95; }
    		else if ($cost2 > 700 ) 			          { $shipping2 = 34.95; }
    		else 								                    { $shipping2 =  0.00; }
  		break;
  		case "CA":
    		     if ($cost2 >   0 &&  $cost2 <  99) { $shipping2 =  9.80; }
    		else if ($cost2 > 100 &&  $cost2 < 199) { $shipping2 = 14.80; }
    		else if ($cost2 > 200 &&  $cost2 < 299) { $shipping2 = 15.80; }
    		else if ($cost2 > 300 &&  $cost2 < 399) { $shipping2 = 17.80; }
    		else if ($cost2 > 400 &&  $cost2 < 499) { $shipping2 = 19.80; }
    		else if ($cost2 > 500 &&  $cost2 < 699) { $shipping2 = 28.95; }
    		else if ($cost2 > 700 ) 				        { $shipping2 = 34.95; }
    		else 								                    { $shipping2 =  0.00; }
  		break;
  		default: $shipping2 = 48.00;
    }
  }
  
  
	switch ($data['country']) {
		case "US": $shipping = 7.00; break;
		case "CA": $shipping = 9.00; break;
		case "AU": $shipping = 75.00; break;
		default: $shipping = 25.00;	
	}		
	
	 if ($data['country']!=US) {
	  if(count($products)>1) $rate = 3.50 * ((count($products)-count($xr_cost)) - 1);
	  else $rate = 0.00;
	}	
}

if($shipping2 == 0.00) $shipping_rate = $shipping + $rate;	
else if(count($prices) == count($xr_cost)) { $shipping_rate = 0.00;	$shipping = 0.00; }
else $shipping_rate = $shipping + $shipping2 + $rate;		

		
$ttl = 1.50+$shipping_rate+$cost;
$ttl2 = $shipping2+$cost2;
?>
<tr>
	<td>&nbsp;</td>
	<td colspan="2" style="border-top: 1px solid #c0c0c0; text-align:right; font-size: 10px">Sub Total:</td>
	<td style="border-top: 1px solid #c0c0c0; text-align:right; font-size: 10px">$<?=number_format($cost2,2);?></td>
	<td style="border-top: 1px solid #c0c0c0; text-align:right; font-size: 10px">$<?=number_format($cost,2);?></td>
</tr>
<tr>
	<td colspan="3" style="text-align:right; font-size: 10px">Fees:</td>
	<td style="text-align:right; font-size: 10px">$0.00</td>
	<td style="text-align:right; font-size: 10px">$1.50</td>
</tr>
<tr>
	<td colspan="3" style="text-align:right; font-size: 10px">XR Shipping:</td>
	<td style="text-align:right; font-size: 10px">$<?=number_format($shipping2,2);?></td>
	<td style="text-align:right; font-size: 10px">$<?=number_format($shipping2,2);?></td>
</tr>
<tr>
	<td colspan="3" style="text-align:right; font-size: 10px">STC Shipping:</td>
	<td style="text-align:right; font-size: 10px">$0.00</td>
	<td style="text-align:right; font-size: 10px">$<?=number_format($shipping + $rate,2);?></td>
</tr>
<tr>
	<td colspan="3" style="text-align:right; font-size: 10px">Total:</td>
	<td style="border-top: 1px solid #c0c0c0; text-align:right; font-size: 10px">$<?=number_format($ttl2,2);?></td>
	<td style="border-top: 1px solid #c0c0c0; text-align:right; font-size: 10px">$<?=number_format($ttl,2);?></td>
</tr>
</table>
<? } ?>
</td>
</tr>
</table>