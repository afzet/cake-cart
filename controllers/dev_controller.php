<?php

class DevController extends AppController {
	
	var $name = 'Dev';
	var $uses = array('Category', 'Product', 'BestSeller');
	
	function cats () {
		$data = $this->Product->find('all', array(
			'conditions' => array('Category.parent_id' => 0),
			'fields' => array('Category.name','Category.id'),
			'order' => array('Category.name' => 'ASC'),
			'recursive' => 0,
			'group' => 'Product.category_id'
		));
		echo '<pre>'; print_r($data); die;
	}
	
	function soldItems() {
		$conditions = array(
			'conditions' => array('Product.product_code !=' => ''),
			'fields' => array(
				'Product.product_name', 'Product.product_list', 'Product.product_cost',
				'BestSeller.product_code', 'Product.product_price', 'Product.category_id',
				'Product.id'
			),
			'recursive' => 0,
			'limit' => 8
		);
		$data = $this->BestSeller->find('all', $conditions);
		echo '<pre>'; print_r($data); die;
 	}
 	
	function bestsellers() {
		$data = $this->BestSeller->find('all', array(
			'order' => 'rand()'	
		));
		echo '<pre>'; print_r($data); die;
	}
}
?>