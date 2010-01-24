<?php
/**
 * SVN FILE: $Id: cart_controller.php 120 2008-08-19 11:56:48Z jonathan $
 *
 * Cart Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 120 $
 * Last Modified: $Date: 2008-08-19 07:56:48 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class CartController extends AppController {

	/**
	 * View Name
	 * @var boolean 
	 */
	var $name = 'Cart';   
	/**
	 * Models Used
	 * @var array 
	 */ 
	var $uses = array('Product','Category','Country');
	
	var $helpers = array('Dojo');

	/**
	 * Cart View
	 * @access public
	 */
	public function index() {		
		$cart = $this->Session->read('Cart');
		$this->set('battery', $this->Product->batteries());
		$this->set(compact('cart'));
	}

	/**
	 * Cart View
	 * @access public
	 */
	public function debug() {		
	  echo '<pre>'; print_r($this->cart); die;
	}
	
	public function clearAll($id) {
		if ($id == '32jl9d9s0') {
			$this->Session->delete('Cart');
		}
		
		$this->redirect('/cart'); 
		exit();
	}
	
	/**
	 * Add a product to the cart
	 * @access public
	 * @param integer $id
	 */
	public function add($id) {	
		// Get the new product object from the database
		$this->cart = $this->Session->read('Cart');
		$this->Product->id = $id; 	
		$product = $this->Product->read(); 	
		 // add the new product to the cart.items object	
		$items = $this->cart['items'];
		if (count($items)!=0) {
			$i = 1; 
			foreach ($items as $item) {
				$c['items'][$i] = $item; 
				$i++;
			}
			$c['items'][$i] = $product;	
		}
		else {	
			$c['items'][1] = $product;	
		}
		$this->cart['items'] = $c['items'];
		
		 // get the total prices for the cart and save the object
		$cart = $this->__getTotal($this->cart);	
		$this->Session->write('Cart',$cart); 
		$this->redirect('/cart'); 
		exit();
	}


	/**
	 * Delete a product to the cart
	 * @access public
	 * @param integer $id
	 */
	public function delete($id) {
		$this->cart = $this->Session->read('Cart');
		unset($this->cart['items'][$id]);
		
		$items = $this->cart['items'];
		if(count($items)!=0) {
			$i = 1; 
			foreach ($items as $item) {
				$c['items'][$i] = $item; 
				$i++;
			}
			$this->cart['items'] = $c['items'];
			$cart = $this->__getTotal($this->cart);	
			$this->Session->write('Cart',$cart);	
		}
		else {
			$this->Session->delete('Cart');
		}
		$this->redirect('/cart');	
	}
	
	/**
	 * Set the Country for the cart for use in 
	 * determining which shipping rate to utilize
	 * @access public
	 * @param integer $id
	 */
	public function add_country($id = null) {
		// Set the country attribute to the cart.country object
		if (!isset($this->cart)) $this->cart = $this->Session->read('Cart');
		$this->cart['country'] = $id;				
		
		 // get the total prices for the cart and save the object
		$cart = $this->__getTotal($this->cart);	
		$this->Session->write('Cart',$cart); 
		$this->redirect('/cart'); 
		exit;
	}
	
	/**
	 * Calculate the total of the cart
	 * @access private
	 * @param array $cart
	 * @return array|rates
	 */
	private function __getTotal($cart) {	
	  //
		// Check if the $cart array has products in the cart.items object
		if (isset($cart['items']) && count($cart['items'])>0) {	
			foreach ($cart['items'] as $item) {			
				// check to see if the product is an XR LLC or STC product
				if (preg_match('/^PMXR-/',$item['Product']['product_code']) == true) { 	
				  $vendor[0] = 'xr';
				} else {		
				  $vendor[1] = 'stc';
				}
			}	
			// Set the vendor to cart.vendor
			$cart['vendor']    = $vendor;
			  
			// Set the countries object to cart.countries for use  
			// in the country pull dowwn in the cart
			$cart['countries'] = $this->Country->find('list',array('vendor'=>$vendor));
			$i=1;
			foreach ($cart['items'] as $item) {
				$prices[$i] = $item['Product']['product_price'];	
				$i++;
			}
			$cart['amt']['sub_total'] = array_sum($prices);	
			
			if (isset($cart['discount'])):
				switch ($cart['discount']['type']) {
				  	case 0:
			  			  if($cart['amt']['sub_total'] > $cart['discount']['amount']):
			  			    $cart['amt']['discount'] = $cart['discount']['amount'];
			  			  else:
			  			    $cart['amt']['discount'] = $cart['amt']['sub_total'];
			  			  endif;
	  				break;
	  				case 1:
	  			  		$cart['amt']['discount'] = number_format($cart['discount']['amount']*$cart['amt']['sub_total']);
				}
			else:
				$cart['amt']['discount'] = 0;
			endif;
			$rates = $this->__shippingRates($cart);
			return $rates;
		}	
	}	
	
	/**
	 * Calculate the Shipping Rates
	 * @access private
	 * @param array $cart
	 * @return object|cart
	 */
	private function __shippingRates($cart) {
		$cost = $cart['amt']['sub_total'];
		
		if(in_array('stc',$cart['vendor'])) {
			if (isset($cart['country'])) {
				if ($cart['country'] == "US") { $stc_shipping = 9.99; }
				if ($cart['country'] == "CA") { $stc_shipping = 12.00; }	
				if ($cart['country'] == "AU") { $stc_shipping = 75.00; }	
				if ($cart['country'] == "OT") { $stc_shipping = 25.00; }	
				
				if (count($cart['items'])>1) {
				  $count = count($cart['items'])-1;
				  $sub = $count*3.50;
				  $shipping1 = $stc_shipping + $sub;
				}
				else {
				  $shipping1 = $stc_shipping;
				}
			} 
		}
		else $shipping1 = 0.00;
		if(in_array('xr',$cart['vendor'])) {
			if (isset($cart['country']) && $cart['country'] == "US" || $cart['country'] == "CA") {
					 if ($cost > 0   &&  $cost < 99)  { $shipping2 =  11.00; }
				else if ($cost > 100 &&  $cost < 199) { $shipping2 = 14.80; }
				else if ($cost > 200 &&  $cost < 299) { $shipping2 = 15.80; }
				else if ($cost > 300 &&  $cost < 399) { $shipping2 = 17.80; }
				else if ($cost > 400 &&  $cost < 499) { $shipping2 = 19.80; }
				else if ($cost > 500 &&  $cost < 699) { $shipping2 = 28.95; }
				else if ($cost > 700) 				  { $shipping2 = 34.95; }
				else 								  { $shipping2 = 45.00; }
			} 
			else $shipping2 = 45.00;
		}
		else $shipping2 = 0.00;
		
		if (isset($cart['country'])) {
			$cart['amt']['shipping'] = $shipping1 + $shipping2;
		}
		else $cart['amt']['shipping'] = 0; 
		
		return $cart;
	}

}
?>
