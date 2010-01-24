<?php
/**
 * SVN FILE: $Id: category_controller.php 88 2008-08-19 08:58:09Z jonathan $
 *
 * Category Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 88 $
 * Last Modified: $Date: 2008-08-19 04:58:09 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class CategoryController extends AppController {
	
	var $name = 'Category';
	var $uses = array('Product','Category');
	
	function view($id = null)  {
		$this->Category->id = $id;						
		$category = $this->Category->read();	
		$this->pageTitle = strtolower($category['Category']['name']).' - passion mansion adult store'; 
		
		
		if($category['Category']['parent_id'] == 0)  {
			$cats = ClassRegistry::init('Product')->cats($category['Category']['id']);
			$i=0;
			foreach ($cats as $cat) {
				$array[$i] = $cat['Category']['id'];
				$i++;
			}			
			$array[$i+1] = $category['Category']['id'];
			
			$_cats = implode(',',$array);
			$conditions = array(
				'and' => array(
					'Product.category_id IN ('.$_cats.')',
					'Product.out_of_stock' => 0,
					'Product.product_image !=""'
				)
			);				
			$this->set('cats',$cats);
		}
		else {			
			$conditions = array(
				'or' => array(
					'Product.category_id' => $id, 
					'Category.parent_id' => $id
				),
				'and' => array(
					'Product.out_of_stock' => 0,
					'Product.product_image !=""'
				)
			);
			$cats = ClassRegistry::init('Product')->cats($category['Category']['id']);
			$this->set('cats',$cats);
			
		}
		$this->set('category', $category);	
		
		$this->paginate = array(
				'conditions' => $conditions,
				'limit' => 24,
				'fields' => array(
					'Product.out_of_stock', 'Product.product_name', 'Product.product_list', 'Product.product_thumb',
					'Product.product_cost', 'Product.product_code', 'Product.product_price', 'Product.product_image',
					'Product.category_id', 'Product.id', 'Category.name'
				),
				'recursive' => 0
			);		
			
		$data = $this->paginate('Product');
		$this->set(compact('data'));
	}   

}
?>