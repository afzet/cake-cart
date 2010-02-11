
<h2>Orders </h2>
<strong><a href="/orders/export">Export Orders</a></strong>
<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row"></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Store', 'store'),
				$paginator->sort('Transaction ID', 'id'),
				$paginator->sort('Paid', 'payment_status'),
				$paginator->sort('Status', 'order_stats'),
				$paginator->sort('Country', 'customer_country'),
				$paginator->sort('Customer', 'customer_name'),
				$paginator->sort('Shipping', 'payment_shipping'),
				$paginator->sort('Handling', 'payment_handling'),
				$paginator->sort('Amount Due', 'payment_gross'),
				$paginator->sort('Order Submitted', 'created'),
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		switch($value['Order']['store']):
          case "paypal@passionmansion.com":    
          case "auction_ebay@passionmansion.com":   
          case "sale2trust@hotmail.com":      
	          $store = 'eBay';          
	          break;
          case "ioffer@passionmansion.com":   
          case "auction_ioffer@passionmansion.com":    
          	$store = 'iOffer';         
          	break;
          case "store@passionmansion.com": 
          case "orders@passionmansion.com":  
          case "anthony@passionmansion.com":    
          	$store = 'Passion Manion';  
          	 break;
          case "ecrater@passionmansion.com":   
	          $store = 'eCrater';       
	          break;
          case "ebid@passionmansion.com":      
	          $store = 'ebid';          
	          break;
          case "amazon@passionmansion.com":  
	          $store = 'amazon';        
	          break;
          default: 
          	$store = $value['Order']['store'];
		endswitch;
		
		$view = '/orders/view/'.$value['Order']['id'];
		echo $html->tableCells(
			array(
				$store,
				// $html->image('icons/'.$store.'.png'),
				$value['Order']['id'],
				str_replace('_',' ',$value['Order']['payment_status']),
				str_replace('_',' ',$value['Order']['order_status']),
				$value['Order']['customer_country'],
				// $html->image('flags/'.strtolower($value['Order']['customer_country']).'.png'),
				array(ucwords(strtolower($value['Order']['customer_name'])), array('class'=>'info')),
				array('$'.number_format($value['Order']['payment_shipping'],2), array('class'=>'money')),
				array('$'.number_format($value['Order']['payment_handling'],2), array('class'=>'money')),
				array('$'.number_format($value['Order']['payment_gross'],2), array('class'=>'money')),
				date('m-d-y', $value['Order']['created']),
			),
			array('class'=>'row', 'onClick'=>"redirect('$view')"),
			array('class'=>'altrow', 'onClick'=>"redirect('$view')")
			);
	$i++;
	}
	?>
</table>
<?php
echo $paginator->prev().'&nbsp;';
echo $paginator->numbers().'&nbsp;';
echo $paginator->next();
?>