<?php

class ProductsController extends AppController {
	
	var $name = 'Products';
	var $uses = array('Product');
    
	function beforeFilter() {
		parent::adminLayout();
	}
	 
	function admin_index()  {
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
	
	function clear()  {
	  $this->Session->delete('search');
	  $this->Session->delete('shipping');
		$this->redirect(array('prefix' => 'admin', 'action' => 'index'));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function admin_view($id = null)  {
		if (!$id)  {
			$this->Session->setFlash('Invalid Product!');
			$this->redirect(array('prefix' => 'admin', 'action' => 'index'));
		}
		$this->set('Product', $this->Product->read(null, $id));
	}
    
	function add() {
		if (!empty($this->data))  {
			$this->Product->create();
			if ($this->Product->save($this->data))
			{
				$this->Session->setFlash('The Product has been saved!');
				$this->redirect(array('prefix' => 'admin', 'action' => 'index'));
		} else  {
				$this->Session->setFlash('The Product could not be saved. Please, try again!');
			}
		}
	}
    
	function admin_edit($id = null)  {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid Product!');
			$this->redirect(array('prefix' => 'admin', 'action' => 'index'));
		}
		if (empty($this->data) == false) {
			if ($this->Product->save($this->data)) {
				$this->Session->setFlash('The Product has been edited!');
				$this->redirect(array('prefix' => 'admin', 'action' => 'index'));
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
    
	function admin_delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Product!');
			$this->redirect(array('prefix' => 'admin', 'action' => 'index'));
		}
		if ($this->Product->del($id)) {
			$this->Session->setFlash('Product #'.$id.' deleted!');
			$this->redirect(array('prefix' => 'admin', 'action' => 'index'));
		}
	}
	    
	function add_shipping() {
		$country = $this->params['pass'][0];
		
		switch ($country) {
			case "US": $shipping = 7.00;  break;
			case "CA": $shipping = 9.00;  break;
			case "AU": $shipping = 75.00; break;
			default:   $shipping = 25.00;
		}			
		
		$this->Session->write('shipping', $shipping);
		$this->redirect(array('prefix' => 'admin', 'action' => 'index'));
		exit;
	}
	    
	function ajax_update_cost() {
		Configure::write('debug',0);
        if ($this->data) {
            App::import('Core', 'sanitize');
            $product_cost = Sanitize::clean($this->data['Product']['product_cost']);
            $this->Product->id = $this->data['Product']['id'];
            $this->Product->saveField('product_cost', $product_cost);
            $this->set('data', $product_cost);            
        }
    }
	    
	function ajax_update_price() {
		Configure::write('debug',0);
        if ($this->data) {
            App::import('Core', 'sanitize');
            $product_price = Sanitize::clean($this->data['Product']['product_price']);
            $this->Product->id = $this->data['Product']['id'];
            $this->Product->saveField('product_price', $product_price);
            $this->set('data', $product_price);            
        }
    }
	
	function ajax_update_name() {
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