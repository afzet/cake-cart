<?php
	echo $form->create('Google', array('url'=>'https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/'. $session->read('google.merchantID'), 'accept-charset'=>'utf-8') );
	$i = 0;	
	foreach($cart['items'] AS $item) {
		$x = $i+1;
		echo $form->hidden("item_name_$x", array( 'value'=>$item['Product']['product_name']));
		echo $form->hidden("item_description_$x", array( 'value'=>$item['Product']['product_desc']));
		echo $form->hidden("item_quantity_$x", array( 'value'=>1));
		echo $form->hidden("item_price_$x", array( 'value'=>$item['Product']['product_price']));
		echo $form->hidden("item_currency_$x", array( 'value'=>'USD'));
		echo $form->hidden("ship_method_name_$x", array( 'value'=>'USPS'));
		echo $form->hidden("ship_method_price_$x", array( 'value'=>$cart['amt']['shipping']));
	}
	echo $form->hidden('_charset_');
	echo $html->image('http://checkout.google.com/buttons/checkout.gif?merchant_id='. $session->read('google.merchantID') .'&w=180&h=46&style=white&variant=text&loc=en_US',
	array('name'=>'Google Checkout', 'alt'=>'Fast checkout through Google', 'height'=>'46', 'width'=>'180'));
	echo $form->end( array( 'label' => ' Confirm Order ' ) );
?>