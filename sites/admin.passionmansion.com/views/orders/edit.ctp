
<h2><a href="/orders">Orders</a> &raquo; Edit</h2>
<?php
echo $form->create('Order', array('action' => 'edit'));
echo $form->input('Order.id', array('type'=>'hidden')); 

// Customer Information
echo $form->input('customer_name', array('style' => 'width: 130px'));
echo $form->input('customer_email', array('style' => 'width: 250px'));
echo $form->input('customer_street', array('style' => 'width: 130px'));
echo $form->input('customer_city', array('style' => 'width: 130px'));
echo $form->input('customer_state', array('style' => 'width: 130px'));
echo $form->input('customer_zip', array('style' => 'width: 130px'));
echo $form->input('customer_phone', array('style' => 'width: 130px'));

echo $form->submit();
echo $form->end();
?>