<?php
class SitemapsController extends AppController{

	var $name = 'Sitemaps';
	var $uses = array('Product');
	var $helpers = array('Time', 'Dojo');
	var $components = array('RequestHandler');

	function index (){	
		$products = $this->Post->find('all', array( 
			'conditions' => array('out_of_stock' => 0), 
			'fields' => array('product_name','id', 'product_code'),
			'recursive' => -1
		);
		$this->set('products', $products);
		//debug logs will destroy xml format, make sure were not in drbug mode
		Configure::write ('debug', 0);
	}
}
?>