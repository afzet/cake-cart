<h2>Invoice#: dlmz<?=substr($data['Order']['id'],0,8)?> </h2>
<?

		switch($data['Order']['store']) {
			case "ioffer@passionmansion.com":  $store = 'iOffer'; 		break;
			case "orders@passionmansion.com":  $store = 'pmansion.com'; break;
			case "ecrater@passionmansion.com": $store = 'eCrater'; 		break;
			case "ebid@passionmansion.com":    $store = 'ebid'; 		break;
			case "amazon@passionmansion.com":    $store = 'amazon'; 		break;
			case "swapace@passionmansion.com": $store = 'swapace'; 			break;
			case "bonanzle@passionmansion.com":$store = 'bonanzle'; 		break;
		}
?>
<table border="0" cellspacing="5" cellpadding="5" width="90%">
	
	<tr>
		<td valign="top" style="width: 300px">			
			<table border="0" cellspacing="2" cellpadding="5">
				<tr><th colspan="7" style="padding: 5px">Customer Information</th></tr>
				<tr>
					<td class="customer_head">Customer</td>
					<td class="customer_info"><?=$data['Order']['customer_name']?></td>
				</tr>
				<tr>
					<td class="customer_head">Email</td>
					<td class="customer_info"><?=$data['Order']['customer_email']?></td>
				</tr>
				<tr>
					<td class="customer_head">Street</td>
					<td class="customer_info"><?=$data['Order']['customer_street']?></td>
				</tr>
				<tr>
					<td class="customer_head">City</td>
					<td class="customer_info"><?=$data['Order']['customer_city']?></td>
				</tr>
				<tr>
					<td class="customer_head">State</td>
					<td class="customer_info"><?=$data['Order']['customer_state']?></td>
				</tr>
				<tr>
					<td class="customer_head">ZipCode</td>
					<td class="customer_info"><?=$data['Order']['customer_zip']?></td>
				</tr>
				<tr>
					<td class="customer_head">Country</td>
					<td class="customer_info"><?=$data['Order']['customer_country']?></td>
				</tr>
				<tr>
					<td class="customer_head">Phone Number</td>
					<td class="customer_info"><?=$data['Order']['customer_phone']?></td>
				</tr>
			</table>			
			<table border="0" cellspacing="2" cellpadding="5">
				<tr><th colspan="7" style="padding: 5px">Order Information</th></tr>
				<tr>
					<td class="customer_head">Date</td>
					<td class="customer_info"><?=date('m-d-y', $data['Order']['created'])?></td>
				</tr>
				<tr>
					<td class="customer_head">Time</td>
					<td class="customer_info"><?=date('h:i A', $data['Order']['created'])?></td>
				</tr>
				<tr>
					<td class="customer_head">Payment Type</td>
					<td class="customer_info"><?=$data['Order']['payment_type']?></td>
				</tr>
				<tr>
					<td class="customer_head">Payment Status</td>
					<td class="customer_info"><?=$data['Order']['payment_status']?></td>
				</tr>
				<tr>
					<td class="customer_head">Order Status</td>
					<td id="<?=$data['Order']['id']?>" class="order customer_info"><?=$data['Order']['order_status']?></td>		
				</tr>
				<tr>
					<td class="customer_head">Transaction Type</td>
					<td class="customer_info"><?=$data['Order']['txn_type']?></td>
				</tr>
			</table>
		</td>
		<td style="width: 20px;">&nbsp;</td>
		<td valign="top">	
		<table>	
			<?php echo $html->tableHeaders(
					array(
						'Product #',
						'Name',
						'QTY',
						'Cost',
						'Paid',
						)
					);
			$i=1; 
			foreach($data['OrderItem'] as $key=>$value)  {
				echo $html->tableCells(
					array(
						$value['product_code'],
						$value['item_name'],
						$value['quantity'],
						number_format($value['cost'],2),
						number_format($value['price'],2),
					),
					null,
					array('class'=>'altrow')
				);
				if($value['product_code']=='PMEX-118782D1') $code = true;
				else $code = false;
				
				if($value['product_code']=='PMCD-17761') $code2 = true;
				else $code2 = false;
				
				if(preg_match('PMXR',$value['product_code'])) $stc = false;
				elseif(preg_match('PMADK',$value['product_code'])) $stc = false;
				else $code = true;
				$order['cost'][$i]  = number_format($value['cost'],2);
				$order['price'][$i] = number_format($value['price'],2);
				$i++;
			}
			?>
		</table>		
		<p align="right"><a href="/order_items/view/<?=$data['Order']['id']?>">Add/Edit Order Items</a></p>	
		<br />		
			<table border="0" cellspacing="2" cellpadding="5">
				<tr>
					<th>What The Customer Pays</th>
					<th>What We Pay</th>
				</tr>
				<tr>
					<td valign="top">						
						<table border="0" cellspacing="2" cellpadding="5">
							<tr>
								<td class="customer_head">Products</td>
								<td class="customer_info">$<?=number_format(array_sum($order['price']),2)?></td>
							</tr>
							<tr>
								<td class="customer_head">Handling</td>
								<td class="customer_info">$<?=number_format($data['Order']['payment_handling'],2)?></td>
							</tr>
							<tr>
								<td class="customer_head">Shipping</td>
								<td class="customer_info">$<?=number_format($data['Order']['payment_shipping'],2)?></td>
							</tr>
							<tr>
								<td class="customer_head">Total</td>
								<td class="customer_info">$<?=number_format($data['Order']['payment_gross'],2)?></td>
							</tr>
						</table>
					</td>
					<td>			
						<table border="0" cellspacing="2" cellpadding="5">
							<tr>
								<td class="customer_head">Distributor Cost</td>
								<td class="customer_info">
									$<?
										if ($stc == false) {
										  echo number_format(array_sum($order['cost'])+$data['rate'],2);
										}
										else {										  
										  echo number_format(array_sum($order['cost'])+1.50+$data['rate'],2);
										}
									?>
									
								</td>
							</tr>
							<? 
							if ($store == 'iOffer') { 
								$ttl = $data['Order']['payment_gross'];
									if ($ttl < 4.99) 											      { $ioffer = 0.50; }
									else if ($ttl < 9.95 &&  $ttl > 5.00) 			{ $ioffer = 0.75; }
									else if ($ttl > 10.00 &&  $ttl < 24.99) 		{ $ioffer = 1.25; }
									else if ($ttl > 25.00 &&  $ttl < 99.99) 		{ $ioffer = $ttl*.05; }
									else if ($ttl > 100.00 &&  $ttl < 1499.99)      { $ioffer = $ttl*02.5+5.00; }
									else if ($ttl > 1500.00) 						{ $ioffer = $ttl*01.5+40.00; }
									else { $ioffer = 0.00; }
							?>
							<tr>
								<td class="customer_head">iOffer Fee</td>
								<td class="customer_info">$<?=number_format($ioffer,2)?></td>
							</tr>
							<? } ?>
							<tr>
								<td class="customer_head">Paypal Fee</td>
								<td class="customer_info">$<?=number_format($data['Order']['payment_fee'],2)?></td>
							</tr>
							<tr>
								<td class="customer_head">Total Cost</td>
								<td class="customer_info">
									$<?
										if ($stc == false) {
										  if($store == 'iOffer') $fees = $data['Order']['payment_fee']+$data['rate']+$ioffer;
										  else $fees = $data['Order']['payment_fee']+$data['rate'];
										}
										else {										  
										  if($store == 'iOffer') $fees = $data['Order']['payment_fee']+1.50+$data['rate']+$ioffer;
										  else $fees = $data['Order']['payment_fee']+1.50+$data['rate'];
										}
										if ($code == true) $fees = $data['Order']['payment_fee']+6.99;
										if ($code2 == true) $fees = $data['Order']['payment_fee']+2.99;
										  
									  $cost = $fees+array_sum($order['cost']);
									  echo number_format($cost,2);
									?>
								</td>
							</tr>
							<tr><td colspan="2">&nbsp;</td></tr>
						</table>
					</td>
				</tr>
				<tr><th colspan="2">Total Profit</th></tr>
				<tr>
					<td colspan="2" style="text-align: center; font-weight: bold; color: green;">
						<h1>$<? echo number_format($data['Order']['payment_gross']-$cost,2);?></h1>					
					</td>									
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
echo $form->create('OrderNote',array('url'=>'/orders/add_note'));
echo $form->hidden('OrderNote.order_id', array('value'=>$data['Order']['id']));
echo $form->input('OrderNote.note', array('label'=>'Add Order Message'));
echo $form->submit();
echo $form->end();
?>
<? if(!empty($data['OrderNote'])) { ?>		
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><th>Notes:</th></tr>
		<? foreach ($data['OrderNote'] as $note) { ?>		
			<tr>
				<td style="text-align: left; border-bottom: 1px solid #444">
				<?=$note['created']?> <br />
				Message: <?=$note['note']?>
				</td>		
			</tr>
		<? } ?>		
	</table>
<? } ?>	
	