<?php
/**
 * SVN FILE: $Id: order_item.php 89 2008-08-19 08:58:53Z jonathan $
 *
 * Order Item Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 89 $
 * Last Modified: $Date: 2008-08-19 03:58:53 -0500 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */ 
class OrderItem extends AppModel {

	var $name = 'OrderItem'; 
	var $actsAs = array('Containable','Increment');
	
	var $belongsTo = array(
		'Order' => array(
			'className' => 'Order',
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => '',
			'conditions' => 'Product.product_code = OrderItem.product_code'
		)
	);	

	
	var $fields = array(
		'Product.out_of_stock', 'Product.product_name', 'Product.product_list', 'Product.product_cost',
		'Product.product_code', 'Product.product_price', 'Product.category_id', 'Product.product_thumb', 
		'Product.id'
	);
	
	function soldItems() {
		$conditions = array(
			'conditions' => array('Product.product_code !=' => ''),
			'fields' => array(
				'OrderItem.item_name', 'Product.product_list', 'Product.product_cost',
				'OrderItem.product_code', 'Product.product_price', 'Product.category_id',
				'OrderItem.product_id'
			),
			'order' => array('OrderItem.id' => 'desc'),
			'group' => 'OrderItem.product_id',
			'recursive' => 0,
			'limit' => 8
		);
		$data = $this->find('all', $conditions);
		echo '<pre>'; print_r($data); die;
 	}
	
	function soldItems2() {
		$conditions = array(
			'conditions' => array(
				'OrderItem.product_id !=' => null, 
				'Product.id !=' => null, 
			),
			'group' => array('OrderItem.product_id'),
			'order' => array('OrderItem.id' => 'desc'),
			'recursive' => 1,
			'fields' => $this->fields
		);
		return $this->find('all', $conditions);
	}
	
	function getSales($code) {
		$conditions = array(
			'conditions' => array('OrderItem.aff_code'=>$cod),
			'order' => array('OrderItem.id' => 'desc'),
			'recursive' => 1,
			'fields' => array(
				'count(OrderItem.id) as sales',
				'SUM(OrderItem.aff_amt) as amount'
			)
		);
		return $this->find('all', $conditions);
	}
	
}
?>
