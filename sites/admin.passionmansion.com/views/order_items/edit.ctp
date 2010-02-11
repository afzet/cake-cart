<h2>Invoice#: 
	<a href="/orders/view/<?=$data['OrderItem']['order_id']?>"  style="font-size: 18px">dlmz<?=substr($data['OrderItem']['order_id'],0,8)?></a>
	 &raquo; 
	<a href="/order_Items/view/<?=$data['OrderItem']['order_id']?>"  style="font-size: 18px">Order Items</a>
	 &raquo; Edit
</h2>

<?
echo $form->create('OrderItem',array('edit'));
echo $form->hidden('OrderItem.id');
echo $form->hidden('OrderItem.order_id');
echo $form->input('OrderItem.item_name', array('label' => 'Product Name'));
echo $form->input('OrderItem.product_id', array('label' => 'Product #'));
echo $form->input('OrderItem.product_code');
echo $form->input('OrderItem.quantity');
echo $form->input('OrderItem.cost');
echo $form->input('OrderItem.price');
echo $form->input('OrderItem.aff_code');
echo $form->submit();
?>