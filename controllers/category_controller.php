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
	var $uses = array('Category');
	var $scaffold = 'admin';
		
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view');
	}
		
	function view($id = null)  {
		$this->Category->id = $id;						
		$category = $this->Category->read();	
		$this->pageTitle = strtolower($category['Category']['name']).' - passion mansion adult store'; 
		$this->set('title_for_layout', $this->pageTitle);
		
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
					'Product.status' => 1,
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
					'Product.status' => 1,
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
					'Product.status', 'Product.name', 'Product.desc', 'Image.name',
					'Product.cost', 'Product.model', 'Product.price',
					'Product.category_id', 'Product.id', 'Category.name'
				),
				'recursive' => 0
			);		
			
		$data = $this->paginate('Product');
		$this->set(compact('data'));
	}   
    

        
	

}
?>