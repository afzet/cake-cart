<?php

class DojoHelper extends AppHelper {
	
	var $replace = array(
		'product' => array('%',', ','&','-')
	);
		
	/**
	 * Phone Format Utility for 10 digit US Phone numbers
	 */
	function productLink($product = array(), $type = 'product_thumb') {
		$name = htmlspecialchars_decode($product['Image']['filename']);
		$name = str_replace($this->replace['product'], '', $name);
		$name = urlencode($name);
		
		$code = $this->productCode($product['Product']['model']);
		
		return sprintf('<a href="/p/%s/%s/%s.html">', $product['Product']['id'], $code, $name);
	}
	
	function sitemap($product = array()) {
		$name = htmlspecialchars_decode($product['Product']['name']);
		$name = str_replace($this->replace['product'], '', $name);
		$name = urlencode($name);
		
		$code = $this->productCode($product['Product']['model']);
		
		return sprintf('p/%s/%s/%s.html', $product['Product']['id'], $code, $name);
	}
	
	function productCode($code) {
		//$code = 'PM' . $code;
		return $code;
	}
}
?>