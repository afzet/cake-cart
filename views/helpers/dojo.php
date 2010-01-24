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
		
		$code = $this->productCode($product['Product']['product_code']);
		
		return sprintf('<a href="/p/%s/%s/%s.html">', $product['Product']['id'], $code, $name);
	}
	
	function productCode($code) {
		//$code = 'PM' . $code;
		return $code;
	}
	
	function imageFix($image) {		
		$image = str_replace('http://images.sextoysex.com/', '', $image);
		$large = PRODUCT_IMAGES . $image;
		if (file_exists($large)): 
			return URL_IMAGES . $image;
		else: 
			return DS . 'img' . DS . 'notfound_thumb.jpg';
		endif;
	}
}
?>