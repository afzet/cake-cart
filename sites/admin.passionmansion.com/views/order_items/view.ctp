<h2>Invoice#: <a href="/orders/view/<?=$data['order']?>"  style="font-size: 18px">dlmz<?=substr($data['order'],0,8)?></a> </h2>

<h3><strong style="font-size: 14px">All Items</strong></h3>

<table>
<p align="right"><a href="/order_items/add/<?=$data['order']?>">Add Item</a></p>
<?php echo $html->tableHeaders(
		array(
			'Affiliate',
			'Product #',
			'Product Code',
			'Name',
			'QTY',
			'Cost',
			'Paid',
			'Edit',
			'Delete',
			)
		);
foreach($data['items'] as $key=>$value)  {
	echo $html->tableCells(
		array(
			$value['OrderItem']['aff_code'],
			$value['OrderItem']['product_code'],
			$value['OrderItem']['product_code'],
			$value['OrderItem']['item_name'],
			$value['OrderItem']['quantity'],
			number_format($value['OrderItem']['cost'],2),
			number_format($value['OrderItem']['price'],2),
			$html->link($html->image('icons/edit.png'),'/order_items/edit/'.$value['OrderItem']['id'].'/'.$data['order'], null, null, false),
			$html->link($html->image('icons/delete.png'),'/order_items/delete/'.$value['OrderItem']['id'].'/'.$data['order'], null, 'Are you sure?', false),
			),
		null,
		array('class'=>'altrow')
	);
}
?>
</table>