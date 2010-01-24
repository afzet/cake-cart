<?php

class Product extends AppModel {

	var $name = 'Product'; 
	var $belongsTo = array(
		'Category', 
		'Image' => array('fields' => array('Image.id', 'Image.name'))
	);
	
	var $fields = array(
		'Product.name', 'Product.desc', 'Product.model', 'Product.price', 
		'Product.id', 'Product.image_id', 'Image.name'
	);
	
	function batteries() {	
		$conditions = array(
			'conditions' => array('Product.category_id' => 55, 'Product.status' => 1),
			'fields' => $this->fields,
			'order' => 'rand()',
			'recursive' => 1,
			'limit' => 4
		);
		return $this->find('all', $conditions);
	}
	
	function cats ($id = 0) {
		return $this->find('all', array(
			'conditions' => array('Category.parent_id' => $id),
			'fields' => array('Category.name','Category.id'),
			'order' => array('Category.name' => 'ASC'),
			'recursive' => 0,
			'group' => 'Product.category_id'
		));
	}
	
	function doIncrement ($id) {
	  $fields = array('Product.viewed');
	  $conditions = array('Product.id'=>$id);
	  $product = $this->find($conditions,$fields);
	  $data['Product']['id'] = $id; 
	  $data['Product']['viewed'] = $product['Product']['viewed']+1;
	  $this->save($data);
	}
	
	function getRecommended ($data) {
		return $this->find('all', array(
			'conditions' => array('Product.category_id' => $data['Product']['category_id'], 'Product.status' => 1),
			'fields' => $this->fields,
			'recursive' => 1,
			'limit' => 4,
			'order' => 'rand()'
		));
	}
	
	function featured() {
		$featured = $this->find('all', array(
			'conditions' => array('Product.featured' => 1, 'Product.status' => 1),
			'fields' => $this->fields,
			'recursive' => 1,
			'limit' => 48,
			'order' => 'rand()'
		));
		foreach ($featured as $key => $value):
			if (parent::imageCheck($value['Image']['name']) == 0) {
				unset($featured[$key]);
			}
		endforeach;
		sort($featured);
		foreach ($featured as $key => $value):
			if ($key > 11) unset($featured[$key]);
		endforeach;
		return $featured;
	}
	
	function newest() {
		$featured = $this->find('all', array(
			'conditions' => array('Product.status' => 1),
			'fields' => $this->fields,
			'recursive' => 1,
			'limit' => 48,
			'order' => array('Product.id' => 'desc')
		));
		foreach ($featured as $key => $value):
			if (parent::imageCheck($value['Image']['name']) == 0) {
				unset($featured[$key]);
			}
		endforeach;
		sort($featured);
		foreach ($featured as $key => $value):
			if ($key > 11) unset($featured[$key]);
		endforeach;
		return $featured;
	}
}
?>
