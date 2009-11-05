<?php
/**
 * SVN FILE: $Id: product.php 89 2008-08-19 08:58:53Z jonathan $
 *
 * Product Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 89 $
 * Last Modified: $Date: 2008-08-19 04:58:53 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Product extends AppModel {

	var $name = 'Product'; 
	var $belongsTo = array('Category');
	
	var $fields = array(
				'Product.out_of_stock', 'Product.product_name', 'Product.product_list', 'Product.product_cost',
				'Product.product_code', 'Product.product_price', 'Product.category_id', 'Product_thumb', 'Product.product_image',
				'Product.id'
	);
	
	function cats ($id = 0) {
		return $this->find('all', array(
			'conditions' => array('Category.parent_id' => $id, 'Category.status' => 1),
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
			'conditions' => array('Product.category_id' => $data['Product']['category_id'], 'Product.out_of_stock' => 0),
			'fields' => array(
				'Product.product_name', 'Product.product_list',
				'Product.product_code', 'Product.product_price',  'Product.product_thumb', 
				'Product.id'
			),
			'recursive' => 1,
			'limit' => 4,
			'order' => 'rand()'
		));
	}
	
	function vibrators() {		
		$conditions = array(
			'conditions' => array('Category.parent_id' => 674, 'Product.out_of_stock' => 0),
			'fields' => array(
				'Product.product_name', 'Product.product_list',
				'Product.product_code', 'Product.product_price',  'Product.product_thumb', 
				'Product.id'
			),
			'order' => 'rand()',
			'recursive' => 1,
			'limit' => 8
		);
		return $this->find('all', $conditions);
	}
	
	function featured() {
		return $this->find('all', array(
			'conditions' => array('Category.parent_id' => 675, 'Product.out_of_stock' => 0),
			'fields' => array(
				'Product.product_name', 'Product.product_list',
				'Product.product_code', 'Product.product_price',  'Product.product_thumb', 
				'Product.id'
			),
			'recursive' => 1,
			'limit' => 8,
			'order' => 'rand()'
		));
	}
	
	function newest() {	
		$conditions = array(
			'conditions' => array('Product.out_of_stock' => 0),
			'order' => array('Product.created' => 'desc'),
			'fields' => $this->fields,
			'recursive' => -1,
			'limit' => 8
		);
		return $this->find('all', $conditions);
	}
	
	function popular() {
		$conditions = array(
			'conditions' => array('Product.out_of_stock' => 0, '1=1 GROUP BY Product.product_name'),
			'order' => array('Product.viewed' => 'desc'),
			'recursive' => 1,
			'limit' => 8
		);
		return $this->find('all', $conditions);
	}
}
?>
