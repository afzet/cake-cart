<?php
/**
 * SVN FILE: $Id: orders_controller.php 379 2008-08-31 09:36:04Z jonathan $
 *
 * Orders Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 379 $
 * Last Modified: $Date: 2008-08-31 05:36:04 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class OrdersController extends AppController {

  var $name = 'Orders';
  var $uses = array('Order','OrderItem','Product','Subscriber','IpnPost','Promo');
  var $components = array('Email'); 

  function track() {
    if(($this->params['url']['url'] = 'orders/track') == true) $data['tracking'] = $this->params['url'];
    else $data['tracking'] = '';
    $this->set('data',$data);
  }
  
  function thank_you() {
    $post = $this->params['url'];
    // Order Information    
    
    $this->data['Order']['id']                 = $post['txn_id'];
    $this->data['Order']['order_customer_id'] = $post['payer_id'];
    $this->data['Order']['txn_type']           = $post['txn_type'];
    $this->data['Order']['reciever_email']     = $post['business'];
    $this->data['Order']['custom']             = $post['custom'];
    $this->data['Order']['payment_type']       = $post['payment_type'];
    $this->data['Order']['payment_gross']     = $post['payment_gross'];
    $this->data['Order']['payment_fee']       = $post['payment_fee'];
    $this->data['Order']['payment_date']       = $post['payment_date'];
    $this->data['Order']['tax']               = $post['tax'];
    $this->data['Order']['num_cart_items']     = $post['num_cart_items'];
    $this->data['Order']['mc_currency']       = $post['mc_currency'];
    $this->data['Order']['mc_gross']           = $post['mc_gross'];
    $this->data['Order']['mc_fee']             = $post['mc_fee'];
    $this->data['Order']['mc_shipping']       = $post['mc_shipping'];
    $this->data['Order']['mc_handling']       = $post['mc_handling'];  
    $this->data['Order']['order_status']       = 'Pending';  
    
    // Customer Information
    $this->data['OrderCustomer']['id']                  = $post['payer_id'];
    $this->data['OrderCustomer']['payer_email']        = $post['payer_email'];
    $this->data['OrderCustomer']['payer_status']      = $post['payer_status'];
    $this->data['OrderCustomer']['first_name']          = $post['first_name'];
    $this->data['OrderCustomer']['last_name']          = $post['last_name'];
    $this->data['OrderCustomer']['address_status']    = $post['address_status'];
    $this->data['OrderCustomer']['address_name']      = $post['address_name'];
    $this->data['OrderCustomer']['address_street']    = $post['address_street'];
    $this->data['OrderCustomer']['address_city']      = $post['address_city'];
    $this->data['OrderCustomer']['address_state']     = $post['address_state'];
    $this->data['OrderCustomer']['address_zip']         = $post['address_zip'];
    $this->data['OrderCustomer']['address_country']   = $post['address_country'];
    $this->data['OrderCustomer']['address_country_code']= $post['address_country_code'];
    $this->data['OrderCustomer']['contact_phone']     = $post['contact_phone'];  
    
    if(strtolower($post['business'])!='store@passionmansion.com') {
      for($i=1; $i<=$qty; $i++) {
        list($id,$code) = explode('|',$post['item_number'.$i]);      
        $product = $this->Product->findById($id);      
        $this->item['Item']['order_id']      = $post['txn_id'];
        $this->item['Item']['product_id']    = $id;
        $this->item['Item']['product_code']  = $code;
        $this->item['Item']['item_name']     = $post['item_name'.$i];
        $this->item['Item']['quantity']      = $post['quantity'.$i];
        $this->item['Item']['price']         = $post['mc_gross'.$i];
        $this->item['Item']['cost']          = $product['Product']['product_cost'];    
        $this->item['Item']['aff_code']      = $post['custom'];    
        $this->item['Item']['aff_amt']       = $product['Product']['product_cost']*0.25;
        if (@array_key_exists($post['item_name'.$i+1], $post) == false) $qty = $i;
      }            
    }
    else {
      list($id,$code) = explode('|',$post['item_number']);      
      $product = $this->Product->findById($id);      
      $this->data['OrderItem']['order_id']       = $post['txn_id'];
      $this->data['OrderItem']['item_name']     = $post['item_name'];
      $this->data['OrderItem']['quantity']       = $post['quantity'];
      $this->data['OrderItem']['price']         = $post['mc_gross'];  
      $this->data['OrderItem']['item_number']   = $post['item_number'];
    }      
    $cart = $this->Session->read('Cart');
    $this->_incriment_code($cart['discount']['code']);
    $this->Session->delete('Cart');
    $this->set('data',$this->data);
  }
  
  function paypal_ipn() {
    // Post Variables
    $post = $this->params['form'];
    $this->iposts['IpnPost']['ipn_data'] = serialize($post);
    $this->IpnPost->save($this->iposts);    
    
    // Check if exists
    if (isset($post['mc_handling'])) $handling = $post['mc_handling']; else $handling = $post['mc_handling'];
    if (isset($post['shipping'])) $shipping = $post['shipping']; else $shipping = '';
    if (isset($post['pending_reason'])) $pending_reason = $post['pending_reason']; else $pending_reason = '';
    
    if (isset($post['quantity'])) $qty = $post['quantity'];   
    else if (isset($post['num_cart_items'])) $qty = $post['num_cart_items'];     
    else $qty = 0; 
    
    if (isset($post['contact_phone'])) $phone = $post['contact_phone']; else $phone = '';
    if (isset($post['auction_buyer_id'])) $buyer = $post['auction_buyer_id']; else $buyer = '';
    if (isset($post['auction_closing_date'])) $close = $post['auction_closing_date'];  else $close = '';
        
    // Structure Order Payment Information
    $this->order['Order']['id']               = $post['txn_id'];
    $this->order['Order']['store']             = strtolower($post['business']);
    $this->order['Order']['txn_type']         = $post['txn_type'];
    $this->order['Order']['payment_type']     = $post['payment_type'];
    $this->order['Order']['payment_status']   = $post['payment_status'];
    $this->order['Order']['payment_reason']   = $pending_reason;
    $this->order['Order']['payment_gross']     = $post['mc_gross'];
    $this->order['Order']['payment_fee']       = $post['mc_fee'];
    $this->order['Order']['payment_currency'] = $post['mc_currency'];
    $this->order['Order']['payment_tax']       = $post['tax'];
    $this->order['Order']['payment_shipping'] = $shipping;  
    $this->order['Order']['payment_handling'] = $handling;    
    $this->order['Order']['payment_qty']       = $qty;  
    
    // Structure Order Customer Information
    $this->order['Order']['customer_name']    = $post['first_name'].' '.$post['last_name'];
    $this->order['Order']['customer_email']    = strtolower($post['payer_email']);
    $this->order['Order']['customer_street']  = $post['address_street'];
    $this->order['Order']['customer_city']    = $post['address_city'];
    $this->order['Order']['customer_state']   = $post['address_state'];
    $this->order['Order']['customer_zip']       = $post['address_zip'];
    $this->order['Order']['customer_country'] = $post['address_country_code'];
    $this->order['Order']['customer_phone']   = $phone;
    
    // Structure Order Auction Information
    $this->order['Order']['auction_buyer_id'] = $buyer;
    $this->order['Order']['auction_closing']  = $close;
    
    // Save Order Information
    $this->Order->save($this->order);      
        
    // Structure Newsletter Subscriber Information
    $this->signup['Subscriber']['email']    = strtolower($post['payer_email']);
    $this->Subscriber->save($this->signup);
    
    if(strtolower($post['business'])=='paypal@passionmansion.com') {
      $this->item['OrderItem']['product_id']    = NULL;
      $this->item['OrderItem']['order_id']     = $post['txn_id'];
      $this->item['OrderItem']['item_name']   = $post['item_name'];
      $this->item['OrderItem']['quantity']     = $post['quantity'];
      $this->item['OrderItem']['price']       = $post['mc_gross'];  
      $this->OrderItem->save($this->item);      
    } 
    elseif(strtolower($post['business'])=='orders@passionmansion.com') {
      $qty = 100;
      for($i=1; $i<=$qty; $i++) {
        list($id,$code) = explode('|',$post['item_number'.$i]);      
        $product = $this->Product->findById($id);      
        $this->item['OrderItem']['order_id']      = $post['txn_id'];
        $this->item['OrderItem']['product_id']    = $id;
        $this->item['OrderItem']['product_code']  = $code;
        $this->item['OrderItem']['item_name']     = $post['item_name'.$i];
        $this->item['OrderItem']['quantity']      = $post['quantity'.$i];
        $this->item['OrderItem']['price']         = $post['mc_gross'.$i];
        $this->item['OrderItem']['cost']          = $product['Product']['product_cost'];    
        $this->item['OrderItem']['aff_code']      = $post['custom'];    
        $this->item['OrderItem']['aff_amt']       = $product['Product']['product_cost']*0.25;
        if (@array_key_exists($post['item_name'.$i+1], $post) == false) $qty = $i;
        $this->OrderItem->create();
        $this->OrderItem->save($this->item);
      }
    }
    else {
      $this->item['OrderItem']['product_id']    = NULL;
      $this->item['OrderItem']['order_id']     = $post['txn_id'];
      $this->item['OrderItem']['item_name']   = $post['item_name'];
      $this->item['OrderItem']['quantity']     = $post['quantity'];
      $this->item['OrderItem']['price']       = $post['mc_gross'];  
      $this->OrderItem->save($this->item);     
    }      
    
    
    $this->__orderNotice($this->order);
    exit();
  }
  
  function __confirmation($data) {      
        $this->Email->to     = $data['OrderCustomer']['payer_email'];
        $this->Email->subject   = 'Order Confirmation';
        $this->Email->replyTo   = 'info@passionmansion.com';
        $this->Email->from     = 'Passion Mansion <noreply@passionmansion.com>';
        $this->Email->smtpOptions = array(
            'port'=>'25', 
            'timeout'=>'30',
            'host' => 'smtp.1and1.com',
            'username'=>'jonathan@passionmansion.com',
            'password'=>'m3m0tyh');
        $this->Email->delivery = 'smtp';
        $this->Email->template   = 'confirmation';
        $this->Email->sendAs   = 'both';
        $this->set('data', $data);
         $this->Email->send();
  }
  
  function __orderNotice($data) {  
        $amount = $data['Order']['payment_gross'];
            
        switch($data['Order']['store']) {
          case "paypal@passionmansion.com":    $store = 'eBay';           break;
          case "auction_ebay@passionmansion.com":    $store = 'eBay';           break;
          case "sale2trust@hotmail.com":      $store = 'eBay';          break;
          case "ioffer@passionmansion.com":    $store = 'iOffer';         break;
          case "auction_ioffer@passionmansion.com":    $store = 'iOffer';         break;
          case "store@passionmansion.com":    $store = 'pmansion.com';   break;
          case "orders@passionmansion.com":    $store = 'pmansion.com';   break;
          case "ecrater@passionmansion.com":   $store = 'eCrater';       break;
          case "ebid@passionmansion.com":      $store = 'ebid';          break;
          case "amazon@passionmansion.com":   $store = 'amazon';        break;
        }
        
        $this->Email->to        = '9542979283@mymetropcs.com';
        
        if ($store!='eBay') {
          $this->Email->bcc       = array('9545138875@mymetropcs.com');
        }
        $this->Email->subject   = "$store - $amount Sale";
        $this->Email->replyTo   = 'info@passionmansion.com';
        $this->Email->from       = 'Passion Mansion <noreply@passionmansion.com>';
        $this->Email->sendAs     = 'text';
        $this->Email->smtpOptions   = array(
          'port'=>'25',  'timeout'=>'30', 'host' => 'smtp.1and1.com',
          'username'=>'jonathan@passionmansion.com', 'password'=>'m3m0tyh'
        );
        $this->Email->delivery = 'smtp';
        $this->Email->send("$store - $amount Sale");
        exit();
    } 
}
?>

