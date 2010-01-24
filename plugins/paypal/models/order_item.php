<?php
class OrderItem extends PaypalAppModel {
  var $name = 'OrderItem';
  
  var $belongsTo = array(
    'Order' => array(
      'className' => 'Paypal.Order'
    )
  );
  
  
}
?>