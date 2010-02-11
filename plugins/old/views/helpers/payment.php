<?php

App::import('Component', 'Gateways.GoogleCheckout');
App::import('Component', 'Session');
class PaymentHelper extends AppHelper {
	
	function __construct() {
		App::import(array('type' => 'File', 'name' => 'Gateways.GatewaysSettings', 'file' => 'config'.DS.'settings.php'));
		$this->config =& new GatewaysSettings();
		parent::__construct();  
	}
	
	function google() {
		
		$this->GoogleCheckout =& new GoogleCheckoutComponent();
		if (empty($this->products)) {
			$this->products = ClassRegistry::init('Product')->find('all', array(
					'fields' => array('Product.product_name AS name', 'Product.product_price AS price', 'Product.product_desc AS desc'),
					'limit' => 3,
					'recursive' => -1,
					
				));
		}
		
		foreach ($this->products as $product) {
			$this->GoogleCheckout->setItem($product['Product']['name'],$product['Product']['desc'],1,$product['Product']['price']);
		}
		
		return $this->GoogleCheckout->generateCartButton($this->config->google);
	}
}
?>