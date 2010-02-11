
<h2>Products </h2>

<?

if ($session->check('Message.flash')) {
	$session->flash();
}
?>

<table class="top">	
	<tr>
		<td colspan="10" class="count">
			<div class="input text">
				<form action="/products/search" method="post" accept-charset="utf-8">			
					<label for="ProductProductcode">Product Code Lookup</label>
					<input type="hidden" name="data[Product][field]" value="product_code" >
					<input id="ProductProductcode" type="text" value="" name="data[Product][product_code]"/>
					<input id="Search" type="submit" value="Search" />
				</form>
			</div>
		</td>
		<td colspan="10" class="count">
			<div class="input text">
				<form action="/products/search" method="post" accept-charset="utf-8">			
					<label for="ProductProductname">Product Name Lookup</label>
					<input type="hidden" name="data[Product][field]" value="product_name" >
					<input id="ProductProductcode" type="text" value="" name="data[Product][product_name]"/>
					<input id="Search" type="submit" value="Search" />
				</form>
			</div>
		</td>
	</tr>
</table>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="count" style="text-align: right">   
			<?php
				echo $paginator->prev('« Previous ', null, null, array('class' => 'disabled'));
				echo '&nbsp;';
				echo $paginator->numbers(); 
				echo '&nbsp;';
				echo $paginator->next(' Next »', null, null, array('class' => 'disabled'));
			?> 
        </td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Product Code', 'product_code'),
				$paginator->sort('Category', 'name'),
				$paginator->sort('Name', 'product_name'),
				$paginator->sort('Cost', 'product_cost'),
				$paginator->sort('Retail', 'product_price'),
				$paginator->sort('inStock', 'out_of_stock'),
				'Edit',
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
				$value['Product']['product_code'],
				$value['Category']['name'],
				array($value['Product']['product_name'],  array('id'=>$value['Product']['id'],'class'=>'product_name')),
				array($value['Product']['product_cost'],  array('id'=>$value['Product']['id'],'class'=>'product_cost')),
				array($value['Product']['product_price'], array('id'=>$value['Product']['id'],'class'=>'product_price')),
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