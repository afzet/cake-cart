<?php
/**
 * SVN FILE: $Id: product_controller.php 172 2008-08-29 08:14:52Z jonathan $
 *
 * Product Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 172 $
 * Last Modified: $Date: 2008-08-29 04:14:52 -0400 (Fri, 29 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class ProductController extends AppController  {
	
	var $name = 'Product'; 
	var $uses = array('Product', 'Category');
	var $components = array('Search', 'FileHandler');
    var $scaffold = 'admin';
	
	function beforeFilter() {
		parent::beforeFilter();
		parent::adminLayout();
		$this->Auth->allow('view', 'search');
	}
	
	function beforeRender() {
		parent::beforeRender();
	}
	
	function view($id = null)  {
		$this->Product->id = $id;		
		$this->Product->doIncrement($id); 
		$data['product'] 		= $this->Product->read();		
		$data['recommended'] 	= $this->Product->getRecommended($data['product']);
		
		$this->pageTitle = $data['product']['Product']['product_name'] .' - ' . $data['product']['Product']['product_list']; 
		$this->set('title_for_layout', $this->pageTitle);
		
		$this->set(compact('data'));		
	}	
	
	function whats_new()  {
		$this->pageTitle = 'Newly Added Sex Toys - What\'s New - Passion Mansion Adult Sex Toys Store'; 
		$this->set('title_for_layout', $this->pageTitle);
		$this->paginate = array('order' => array('Product.created' => 'desc'));					
			
		$data = $this->paginate('Product');		
		$this->set(compact('data'));
	}		
	
	function best_sellers()  {
		$this->pageTitle = 'Best Sellers - Passion Mansion Adult Sex Toys Store'; 	
		$conditions = array(
			'fields' => array(
				'Product.product_name', 'Product.product_list', 'Product.product_cost', 'Product.status',
				'Product.product_code', 'Product.product_price', 'Product.category_id', 'Product.product_thumb',
				'Product.id'
			),
			'recursive' => 0,
			'limit' => 40
		);
		// $data = ClassRegistry::init('BestSeller')->find('all', $conditions);
		// echo '<pre>'; print_r($data);die;
		// $this->set(compact('data'));
	}		
	
	function most_popular()  {
		$this->pageTitle = 'Popular Sex Toys - Most Popular - Passion Mansion Adult Sex Toys Store'; 		
			
		
		$conditions = array(
			'conditions' => array('Product.status' => 0),
			'fields' => array(
				'Product.name', 'Product.desc', 'Product.cost', 'Product.status',
				'Product.model', 'Product.price', 'Product.category_id', 'Product.image',
				'Product.id'
			),
			'recursive' => -1,
			'order' => 'rand()',
			'limit' => 24
		);
		$data = $this->Product->find('all', $conditions);
		
		$this->set(compact('data'));
	}		
	
	function search() {
		App::import('Core', 'sanitize');
		$this->pageTitle = 'Sex Toys - Keyword Search - Passion Mansion Adult Sex Toys Store'; 
		
		
		if (empty($this->params['form']['mainkeyword']) == false)  { 
			$search['mainkeyword'] = trim($this->params['form']['mainkeyword']);
			$search['field']   = '';
			$this->Session->write('Search', $search);
		}
		else if (empty($this->params['named']['mainkeyword']) == false)  { 
			if(isset($this->params['url']['field']) && $this->params['named']['field']!='') { 
				$search['field'] = $this->params['named']['field'];
			}
			else {
				$search['field']   = '';
			}
			$search['mainkeyword'] = trim($this->params['named']['mainkeyword']);
			$this->Session->write('Search', $search);			
		}
		else if (empty($this->params['url']['mainkeyword']) == false)  { 
			if($this->params['url']['field']!='') { 
				$search['field'] = $this->params['url']['field'];
			}
			else {
				$search['field']   = '';
			}
			$search['mainkeyword'] = trim($this->params['url']['mainkeyword']);
			$this->Session->write('Search', $search);			
		}
		else {
			$search = $this->Session->read('Search');
		}
			
		
		$like   = "LIKE '%". urldecode($search['mainkeyword'])."%' ";
		
		if($search['field']!='') {
			$this->paginate = array(
				'conditions' => array(
					'and' => array(
						'Product.status' => 0,
						"Product.".$search['field']." ".$like."",
					)
				),
				'fields' => array(
					'Product.name', 'Product.desc',
					'Product.model', 'Product.price', 'Product.product_image',
					'Product.id'
				),
				'recursive' => 1
			);	
			
		} else {
			$this->paginate = array(
				'conditions' => array(
					'or' => array(
						"Product.name $like",
						"Product.model $like",
						"Product.desc $like",
					),
					'and' => array(
						'Product.status' => 0	
					)
				),
				'fields' => array(
					'Product.name', 'Product.desc',
					'Product.model', 'Product.price', 'Product.image',
					'Product.id'
				),
				'recursive' => 1
			);	
		}			
				
		$data = $this->paginate('Product');	
		$count = $this->Product->find('count', array('conditions' => $this->paginate['conditions']));		
		$this->Search->store($search['mainkeyword'], $count);
		$this->set('phrase',$search['mainkeyword']);
		$this->set(compact('data'));
	}
	
	function productCode($code) {
		$code = str_replace('CNV', '', $code);
		$code = 'PM' . $code;
		return $code;
	}	
	
	function admin_add() {
		if (!empty($this->data)) {
			
			$this->FileHandler->dbModel  = 'Image';
			$image_id = $this->FileHandler->upload($this->data['Product'], 'image', PRODUCT_IMAGES); 
			$this->data['Product']['image_id'] = $image_id;
			if ($this->Product->save($this->data)) {
				$this->redirect('index');
			}
		}
		$this->set('categories', ClassRegistry::init('Category')->find('list'));
	}
	
	function admin_edit($id = null) {
		if (!empty($this->data)) {
			if (!empty($this->data['Product']['image']['name'])) {
				$this->FileHandler->dbModel  = 'Image';
				$image_id = $this->FileHandler->upload($this->data['Product'], 'image', PRODUCT_IMAGES); 
				$this->data['Product']['image_id'] = $image_id;
			}
			if ($this->Product->save($this->data['Product'])) {
				$this->redirect('index');
			}
		}
		if (!empty($id)) {
			$this->data = $this->Product->find('first', array(
				'conditions' => array('Product.id' => $id)
			));
		}
		else {
			$this->redirect('index');
		}
		$this->set('categories', ClassRegistry::init('Category')->find('list'));
	}
}
?>
