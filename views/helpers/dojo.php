<?php

class DojoHelper extends AppHelper {
	
	var $replace = array(
		'product' => array('%',', ','&','-')
	);
		
	/**
	 * Phone Format Utility for 10 digit US Phone numbers
	 */
	function productLink($product = array(), $type = 'product_thumb') {
		$name = htmlspecialchars_decode($product['Product']['product_name']);
		$name = str_replace($this->replace['product'], '', $name);
		$name = urlencode($name);
		return sprintf('<a href="/p/%s/%s/%s.html">', $product['Product']['id'], $product['Product']['product_code'], $name);
	}
}
?>