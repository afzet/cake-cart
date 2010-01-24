<?php
/**
 * SVN FILE: $Id: press_controller.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Promo Code Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class PromosController extends AppController 
{	
	var $name = 'Promos';  
	var $uses = array('Promo');
	var $scaffold = 'admin';
	
	function beforeFilter() {
		parent::adminLayout();
	}
		
	function code() 
	{
	  if(empty($this->data)): $this->redirect('/cart');
	  else:
  	  App::import('Sanitize');
  	  $data = Sanitize::clean($this->data);
  	  $result = $this->Promo->find(array('code'=>$data['Promo']['code']));
  	  
  	  if ($result == NULL): 
  	    return $this->invalid();
  	  else:	  
  	    $cart = $this->Session->read('Cart');
    	  if (isset($cart['amt']['discount'])): 
    	    unset($cart['amt']['discount']);
    	    unset($cart['discount']['info']);
    	  endif;
    	  
    	  if($result['Promo']['limit_max'] != 'X'):
    	    if($result['Promo']['limit_max'] == $result['Promo']['used']) return $this->invalid();
    	  endif;
    	    
    	  if ($result['Promo']['date_start'] != 'X'):
    	    if(time() < $result['Promo']['date_start']) return $this->invalid(); 
    	    if (time() > $result['Promo']['date_end']) return $this->invalid();
    	  endif;
    	  
    	  if ($result['Promo']['min_purchase'] != 0):
    	    if ($cart['amt']['sub_total'] < $result['Promo']['min_purchase']) return $this->invalid();
    	  endif;
    	  
    	  $cart['discount']['type'] = $result['Promo']['type'];  
    	  
    	  switch($result['Promo']['type']) {
    	    case 1:
    	      $discount = round($result['Promo']['amount']/100,2);
    	      $cart['amt']['discount'] = number_format($discount*$cart['amt']['sub_total']);
    	      $cart['discount']['amount'] = number_format($discount*$cart['amt']['sub_total']); 
    	      $cart['discount']['info'] = $result['Promo']['amount'] .'%';
    	    break;
    	    default:
    	      $discount = $result['Promo']['amount'];
    	      if ($discount > $cart['amt']['sub_total']): 
    	        $cart['amt']['discount'] = $cart['amt']['sub_total'];  
    	        $cart['discount']['amount'] = $result['Promo']['amount']; 
    	        $cart['discount']['info'] = '$'. $result['Promo']['amount'] .' off';       
    	      else: 
    	        $cart['amt']['discount'] = $discount;
    	        $cart['discount']['amount'] = $result['Promo']['amount'];  
    	        $cart['discount']['info'] = '$'. $result['Promo']['amount'] .' off';
    	      endif;
    	  }
    	  
    	  $cart['discount']['code'] = $result['Promo']['code'];
      	  
    	  $this->Session->write('Cart',$cart);
    	  $this->Session->setFlash('Promo code validated!');
    	  $this->redirect('/cart');
    	  exit();
  	  endif;
  	endif;
	}
	
	
	function incriment_code($code) {
	  $info = $this->Promo->find(array('code'=>$code));
	  $info['Promo']['used'] = $info['Promo']['used'] + 1;
	  $this->Promo->save($info);
	}
	
	function invalid() {
	  $cart = $this->Session->read('Cart');
	  unset($cart['amt']['discount']);
  	unset($cart['discount']['info']);
  	$this->Session->write('Cart', $cart);
	  $this->Session->setFlash('Promo code invalid!');
	  $this->redirect('/cart');
	  exit();
	}
}
?>