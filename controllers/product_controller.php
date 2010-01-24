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
	
	var $name 		= 'Product'; 
	var $uses = array('Product','Category','OrderItem');
	var $helpers = array('Dojo');
	var $components = array('Search');
	var $paginate = array(
	        	'limit' => 24,
				'conditions' => array('Product.out_of_stock' => 0),
    );
    var $scaffold = 'admin';
	
	function beforeFilter() {
		parent::adminLayout();
		$this->Auth->allow('*');
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
				'Product.product_name', 'Product.product_list', 'Product.product_cost', 'Product.out_of_stock',
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
			'conditions' => array('Product.out_of_stock' => 0),
			'fields' => array(
				'Product.product_name', 'Product.product_list', 'Product.product_cost', 'Product.out_of_stock',
				'Product.product_code', 'Product.product_price', 'Product.category_id', 'Product.product_thumb',
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
			
		//echo '<pre>'; print_r($search); die;
		
		$like   = "LIKE '%". urldecode($search['mainkeyword'])."%' ";
		
		if($search['field']!='') {
			$this->paginate = array(
				'conditions' => array(
					'and' => array(
						'Product.out_of_stock' => 0,
						"Product.".$search['field']." ".$like."",
					)
				),
				'fields' => array(
					'Product.product_name', 'Product.product_list',
					'Product.product_code', 'Product.product_price',  'Product.product_thumb', 'Product.product_image',
					'Product.id'
				),
				'recursive' => 1
			);	
			
		} else {
			$this->paginate = array(
				'conditions' => array(
					'or' => array(
						"Product.product_name $like",
						"Product.product_code $like",
						"Product.product_desc $like",
					),
					'and' => array(
						'Product.out_of_stock' => 0	
					)
				),
				'fields' => array(
					'Product.product_name', 'Product.product_list',
					'Product.product_code', 'Product.product_price',  'Product.product_thumb', 'Product.product_image',
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
}
?>
