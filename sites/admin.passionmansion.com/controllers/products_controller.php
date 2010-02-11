<?php
/* SVN FILE: $Id: products_controller.php 141 2008-08-19 13:32:57Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 141 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-19 08:32:57 -0500 (Tue, 19 Aug 2008) $
 */
class ProductsController extends AppController {
	
	var $name = 'Products';
	var $uses = array('Product','Category');
	var $helpers = array('Html', 'Text', 'Form', 'Session');
	var $components = array('RequestHandler','Auth');		
	var $paginate = array('limit' => 25, 'order' => 'Product.id');
	var $scaffold;	     
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function index()  {
		if(!empty($this->data['Product'])) {
		
  		$str = '';
  		foreach ($this->data['Product'] as $key => $value) {
  		  if (!empty($value)) {
  		    if (empty($str)) $str .= "Product.$key LIKE '%".trim($value)."%' ";
  		    else             $str .= "&& Product.$key LIKE '%".trim($value)."%' ";    
  		  }
  		}
  		$this->Session->write('search', $str);
		  $this->paginate = array('conditions'=> $str);					  
		}
		$search = $this->Session->read('search');
		if(!empty($search)) {	
		  $this->paginate = array('conditions'=> $search);					  
		}
		$this->set('shipping',$this->Session->read('shipping'));
		$data = $this->paginate('Product');		
		$this->set(compact('data'));
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function clear()  {
	  $this->Session->delete('search');
	  $this->Session->delete('shipping');
		$this->redirect('/products');
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function view($id = null)  {
		if (!$id)  {
			$this->Session->setFlash('Invalid Product!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('Product', $this->Product->read(null, $id));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function add() {
		if (!empty($this->data))  {
			$this->Product->create();
			if ($this->Product->save($this->data))
			{
				$this->Session->setFlash('The Product has been saved!');
				$this->redirect(array('action'=>'index'), null, true);
		} else  {
				$this->Session->setFlash('The Product could not be saved. Please, try again!');
			}
		}
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function edit($id = null)  {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid Product!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (empty($this->data) == false) {
			if ($this->Product->save($this->data)) {
				$this->Session->setFlash('The Product has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else {
				$this->Session->setFlash('The Product could not be saved. Please, try again!');
			}
		}
		if (empty($this->data))  {
			$this->data = $this->Product->read(null, $id);
			$categories = $this->Product->Category->find('list',array('order'=>'Category.name ASC'));
			$this->set(compact('categories'));
		}
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Product!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Product->del($id)) {
			$this->Session->setFlash('Product #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function add_shipping() {
		$country = $this->params['pass'][0];
		
		switch ($country) {
			case "US": $shipping = 7.00;  break;
			case "CA": $shipping = 9.00;  break;
			case "AU": $shipping = 75.00; break;
			default:   $shipping = 25.00;
		}			
		
		$this->Session->write('shipping', $shipping);
		$this->redirect('/products');
		exit;
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function ajax_update_cost() {
		Configure::write('debug',0);
        if ($this->data) {
            App::import('Core', 'sanitize');
            $product_cost = Sanitize::clean($this->data['Product']['product_cost']);
            $this->Product->id = $this->data['Product']['id'];
            $this->Product->saveField('product_cost', $product_cost);
            $this->set('data', $product_cost);            
        }
    }
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function ajax_update_price() {
		Configure::write('debug',0);
        if ($this->data) {
            App::import('Core', 'sanitize');
            $product_price = Sanitize::clean($this->data['Product']['product_price']);
            $this->Product->id = $this->data['Product']['id'];
            $this->Product->saveField('product_price', $product_price);
            $this->set('data', $product_price);            
        }
    }
	
	public function ajax_update_name() {
		Configure::write('debug',0);
        if ($this->data) {
            App::import('Core', 'sanitize');
            $order_status = Sanitize::clean($this->data['Product']['order_status']);
            $this->Product->id = $this->data['Product']['id'];
            $this->Product->saveField('product_name', $product_name);
            $this->set('data', $order_status);            
        }
    }
}
?>
