<?php
/* SVN FILE: $Id: order_item.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class OrderItem extends AppModel 
{
	var $name = 'OrderItem';	
	
	var $fields = array(
		'OrderItem.quantity',
		'OrderItem.price',
		'OrderItem.code',
		'OrderItem.aff_code',
		
	);
	
	var $belongsTo = array(
		'Order' => array(
			'className' => 'Order',
			'foreignKey' => 'order_id',
			'fields' => array(
				'Order.id',
				'Order.customer_country',
				'Order.customer_state',
				'Order.created',
				'Order.payment_gross',
				'Order.payment_shipping',
				'Order.payment_handling',
				'Order.payment_fee',
				'Order.payment_tax',
				'Order.store',
				'Order.payment_type',
				'Order.created',
			)
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'fields' => array(
				'Product.id',
				'Product.category_id',
				'Product.product_vendor',
			)
			
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => '',
			'conditions' => 'Category.id = Product.category_id',
			'fields' => array(
				'Category.name'
			)
		)
	);
	
	
}
?>