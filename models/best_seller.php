<?php

class BestSeller extends AppModel {

	var $name = 'BestSeller';
	
	var $belongsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => '',
			'conditions' => 'BestSeller.product_code = Product.product_code'
		)
	);	

	
	function getFront() {
		$conditions = array(
			'conditions' => array('Product.product_code !=' => ''),
			'fields' => array(
				'Product.product_name', 'Product.product_list', 'Product.product_cost',
				'Product.product_code', 'Product.product_price', 'Product.category_id',
				'Product.id'
			),
			'recursive' => 0,
			'limit' => 8
		);
		return $this->find('all', $conditions);
	}

	
}
?>
