
<h2>Products </h2>
<br />
<a href="/products/clear">Remove Search Information</a>

<?

if ($session->check('Message.flash')) {
	$session->flash();
}
?>

<form action="/products" method="post" accept-charset="utf-8">			
<table class="top" style="width: 75%">	
	<tr>
		<td colspan="10" class="count" width="200">
			<div class="input text">		
				<label for="ProductProductcode"><strong>Product Code Lookup</strong></label>
				<input id="ProductProductcode" type="text" value="" name="data[Product][product_code]"/>
				<input id="Search" type="submit" value="Search" />
			</div>
		</td>
		<td colspan="10" class="count" width="200">
			<div class="input text">
				<label for="ProductProductname"><strong>Product Name Lookup</strong></label>
				<input id="ProductProductname" type="text" value="" name="data[Product][product_name]"/>
			</div>
		</td>
		<td colspan="10" class="count" width="200">
			<?=$this->renderElement('select_country');?>
		</td>
	</tr>
</table>
</form>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('ID', 'id'),
				$paginator->sort('Product Code', 'product_code'),
				$paginator->sort('Category', 'name'),
				$paginator->sort('Name', 'product_name'),
				$paginator->sort('Cost', 'product_cost'),
				'Cost w/ Shipping',
				$paginator->sort('Retail', 'product_price'),
				$paginator->sort('inStock', 'out_of_stock'),
				'Edit',
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
	  // set shipping rates
	  $with_shipping = $shipping + $value['Product']['product_cost'];
	  
		echo $html->tableCells(
			array(
				$value['Product']['id'],
				array($value['Product']['product_code'], array('class'=>'info')),
				array($value['Category']['name'], array('class'=>'info')),
				array($value['Product']['product_name'],  array('class'=>'info')),
				'$'.$value['Product']['product_cost'],
				'$'.$with_shipping,
				'$'.$value['Product']['product_price'],
				$html->image('icons/status/'.$value['Product']['out_of_stock'].'.png'),
				$html->link($html->image('icons/edit.png'),'/products/edit/'.$value['Product']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/products/delete/'.$value['Product']['id'], null, 'Are you sure?', false),
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