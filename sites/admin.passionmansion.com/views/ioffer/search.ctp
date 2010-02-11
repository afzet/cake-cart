
<h2>iOffer Products </h2>

<?

if ($session->check('Message.flash')) {
	$session->flash();
}
?>

<table class="top">	
	<tr>
		<td colspan="10" class="count">
			<div class="input text">
				<form action="/ioffer/search" method="post" accept-charset="utf-8">			
					<label for="iOfferId">iOffer ID Lookup</label>
					<input id="iOfferId" type="text" value="" style="width: 130px;" name="data[Ioffer][id]"/>
					<input id="Search" type="submit" value="Search" />
				</form>
			</div>
		</td>
	</tr>
</table>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="count" style="text-align: right">
		<?php
		echo $paginator->prev().'&nbsp;';
		echo $paginator->numbers().'&nbsp;';
		echo $paginator->next();
		?>
		</td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('ID', 'id'),
				$paginator->sort('Type', 'product_type'),
				$paginator->sort('Product', 'title'),
				$paginator->sort('iOffer Link', 'link'),
				$paginator->sort('Cost', 'cost'),
				$paginator->sort('Retail', 'price')
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
				$value['Ioffer']['id'],
				$value['Ioffer']['product_type'],
				$value['Ioffer']['title'],
				$html->link('View Listing',$value['Ioffer']['link'], null, null, false),
				$value['Ioffer']['cost'], 
				$value['Ioffer']['price'], 
			),
			array('class'=>'row'),
			array('class'=>'altrow')
			);
	$i++;
	}
	?>
</table>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="count" style="text-align: right">
		<?php
		echo $paginator->prev().'&nbsp;';
		echo $paginator->numbers().'&nbsp;';
		echo $paginator->next();
		?>
		</td>
	</tr>
</table>