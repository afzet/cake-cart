<?
function format_phnum($ph) {
	$num  = '(' . substr($ph,0,3) . ') ';
	$num .= substr($ph,3,3) . '-';
	$num .= substr($ph,6,4);
	return $num;
}
?>
<h2>Affiliate - <?=$data['Affiliate']['aff_code']?></h2>

<table border="0" cellspacing="5" cellpadding="5" width="90%">
	
	<tr>
		<td valign="top" style="width: 350px">	
			<table border="0" cellspacing="2" cellpadding="5">
				<tr><th colspan="7" style="padding: 5px">Affiliate Information</th></tr>
				<tr>
					<td class="customer_head">Contact Name</td>
					<td class="customer_info"><?=$data['Affiliate']['contact_name']?></td>
				</tr>
				<tr>
					<td class="customer_head">Street</td>
					<td class="customer_info"><?=ucwords($data['Affiliate']['address'])?></td>
				</tr>
				<tr>
					<td class="customer_head">City</td>
					<td class="customer_info"><?=ucwords($data['Affiliate']['city'])?></td>
				</tr>
				<tr>
					<td class="customer_head">State</td>
					<td class="customer_info"><?=strtoupper($data['Affiliate']['state'])?></td>
				</tr>
				<tr>
					<td class="customer_head">ZipCode</td>
					<td class="customer_info"><?=$data['Affiliate']['zip']?></td>
				</tr>
				<tr>
					<td class="customer_head">Country</td>
					<td class="customer_info"><?=$data['Affiliate']['country']?></td>
				</tr>
				<tr>
					<td class="customer_head">Email</td>
					<td class="customer_info">
					  <?=$html->link($data['Affiliate']['email'],'mailto:'.$data['Affiliate']['email'].'')?>
				  </td>
				</tr>
				<tr>
					<td class="customer_head">Phone Number</td>
					<td class="customer_info"><?=format_phnum($data['Affiliate']['phone'])?></td>
				</tr>
				<tr>
					<td class="customer_head">Mobile Number</td>
					<td class="customer_info">
					  <? if (!empty($data['Affiliate']['mobile'])) echo format_phnum($data['Affiliate']['mobile']); ?>
				  </td>
				</tr>
			</table>			
			<table border="0" cellspacing="2" cellpadding="5">
				<tr><th colspan="7" style="padding: 5px">Account Information</th></tr>
				<tr>
					<td class="customer_head">Customer Code</td>
					<td class="customer_info"><?=strtoupper($data['Affiliate']['customer_code'])?></td>
				</tr>
				<tr>
					<td class="customer_head">Affiliate Code</td>
					<td class="customer_info"><?=$data['Affiliate']['aff_code']?></td>
				</tr>
				<tr>
					<td class="customer_head">Make Checks out to</td>
					<td class="customer_info"><?=$data['Affiliate']['check_name']?></td>
				</tr>
				<tr>
					<td class="customer_head">SSN On File</td>
					<td class="customer_info">
					  <? 
					  if (!empty($data['Affiliate']['ssn'])) echo 'Yes';
					  else echo 'No';
					  ?>
				  </td>
				</tr>
				<tr>
					<td class="customer_head">EIN On File</td>
					<td class="customer_info">
					  <? 
					  if (!empty($data['Affiliate']['tax_id'])) echo 'Yes';
					  else echo 'No';
					  ?>
				  </td>
				</tr>
			</table>			
		</td>
		<td style="width: 20px;">&nbsp;</td>
		<td valign="top" style="text-align: left">		
		<table>	
		  <tr><th colspan="6" style="padding: 5px">Affiliate Sales</th></tr>
		  
		  <?
			echo $html->tableHeaders(
					array(
						'Date',
						'Cost',
						'Price',
						'Qty',
						'Profit',
						'Owed',
						)
					);
		  foreach ($data['OrderItem'] as $orderitem) {
		    $i = 1;
		    $trans_id = key($data['OrderItem']);
		    foreach ($orderitem as $item) {
		      $order['items']['price'][$i] = $item['price'];
		      $order['items']['cost'][$i] = $item['cost'];
		      $order['created'] = $item['created'];
		      $i++;
		    }
		    
		    $cost = number_format(array_sum($order['items']['cost']),2);
		    $paid = number_format(array_sum($order['items']['price']),2);		    
		    $diff = number_format(($paid-$cost),2);
		    $aff  = number_format(($diff * .30),2);
		    
		    $items[$trans_id]['trans_id'] = $trans_id;
		    $items[$trans_id]['date']     = substr($order['created'],0,10);
		    $items[$trans_id]['cost']     = $cost;
		    $items[$trans_id]['paid']     = $paid;
		    $items[$trans_id]['items']    = count($order['items']['price']);
		    $items[$trans_id]['profit']   = $diff;
		    $items[$trans_id]['aff_amt']  = $aff;
		    unset($order);
		    $i = 1;
			foreach($items as $key=>$value)  {
				echo $html->tableCells(
					array(
						$value['date'],
						'$'.number_format($value['cost'],2),
						'$'.number_format($value['paid'],2),
						$value['items'],
						'$'.number_format($value['profit'],2),
						'$'.number_format($value['aff_amt'],2),
					),
					array('class'=>'row'),
					array('class'=>'altrow')
				);
			  $i++;
			}
		  }
		  ?>
		  </table>
		</td>
	</tr>
</table>