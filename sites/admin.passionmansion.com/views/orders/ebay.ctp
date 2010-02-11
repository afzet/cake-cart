
<h2>Orders </h2>

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
				$paginator->sort('Transaction ID', 'id'),
				$paginator->sort('Customer', 'customer_name'),
				$paginator->sort('eBay Name', 'customer_address'),
				//$paginator->sort('Address', 'item_name'),
				$paginator->sort('Item', 'item_name'),
				$paginator->sort('Status', 'payment_status'),
				$paginator->sort('Paid', 'payment_gross'),
				$paginator->sort('Date', 'created'),
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
			  $value['Order']['id'],
				array(ucwords($value['Order']['customer_name']), array('class'=>'info')),
				array(ucwords($value['Order']['auction_buyer_id']), array('class'=>'info')),
				//ucwords($value['Order']['customer_street']).' '.ucwords($value['Order']['customer_city']).' '.$value['Order']['customer_state'].' '.$value['Order']['customer_zip'],
				array($text->truncate($value['OrderItem'][0]['item_name'],30), array('class'=>'info')),
			  $html->image('icons/'.$value['Order']['payment_status'].'.png'),
				array('$'.number_format($value['Order']['payment_gross'],2), array('class'=>'money')),
				date('m-d-y', $value['Order']['created']),
			),
			array('class'=>'row'),
			array('class'=>'altrow')
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