<em></em><?php
/* SVN FILE: $Id: calculator_controller.php 141 2008-08-19 13:32:57Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 141 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-19 08:32:57 -0500 (Tue, 19 Aug 2008) $
 */
class CalculatorController extends AppController {

	var $name = 'Calculator';
	var $uses = array('Product');
	var $helpers = array('Html', 'Text', 'Form', 'Session');
	var $components = array('Auth');			
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function index() {
		$products = $this->Session->read('Products');		
		if (count($products)!=0) {
		  $data = $this->Session->read('Calculator');
			$this->set('products',$products);
			$this->set('calc',$calc);
			$this->set('data',$data);
		}
	}
	     
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function add() {
		
		$fields = array('Product.product_code','Product.product_cost','Product.xr_cost','Product.product_name');			
		if (!empty($this->data['Product']['product_code'])) {
			$product_code = str_replace(' ','-',$this->data['Product']['product_code']);
			$conditions = array('Product.product_code'=>$product_code,'Product.out_of_stock'=>0);		
			$product = $this->Product->find($conditions,$fields);
			if(!empty($product)) { 
				$products = $this->Session->read('Products');
				$key = count($products)+1;
				$products[$key]['key'] 			= $key;
				$products[$key]['product_code'] = $product['Product']['product_code'];
				$products[$key]['product_name'] = $product['Product']['product_name'];
				$products[$key]['xr_cost']      = $product['Product']['xr_cost'];
				$products[$key]['product_cost'] = $product['Product']['product_cost'];	
				$this->Session->write('Products',$products);	
			} 
			else {
				$this->Session->setFlash('Sorry Invalid Product or Out of Stock!');
			}			
			$this->redirect(array('action'=>'index'), null, true);	
		}
		elseif (!empty($this->data['Product']['product_name'])) {
			$product_name = $this->data['Product']['product_name'];
			$conditions = array("Product.product_name LIKE '%".$product_name."%'",'Product.out_of_stock'=>0);		
			$product = $this->Product->find($conditions,$fields);
			if(!empty($product)) { 
				$products = $this->Session->read('Products');
				$key = count($products)+1;
				$products[$key]['key'] 			= $key;
				$products[$key]['product_code'] = $product['Product']['product_code'];
				$products[$key]['product_name'] = $product['Product']['product_name'];
				$products[$key]['product_cost'] = $product['Product']['product_cost'];	
				$this->Session->write('Products',$products);		
			} 
			else {
				$this->Session->setFlash('Sorry Invalid Product or Out of Stock!');
			}			
			$this->redirect(array('action'=>'index'), null, true);	
		} 
		else {
			$this->Session->setFlash('Sorry Invalid Product!');
			$this->redirect(array('action'=>'index'), null, true);	
		}

	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function delete() {
		$products = $this->Session->read('Products');
		unset($products[$this->params['pass'][0]]);
		$this->Session->write('Products', $products);
		$this->redirect(array('action'=>'index'), null, true);		
		exit();
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function add_country() {
		$country = $this->params['pass'][0];
		
		$info['country'] = $country;
		$calc = $info;		
		$this->Session->write('Calculator', $calc);
		$this->redirect('/calculator');
		exit;
	}

}
?>
