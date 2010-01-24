<?php

class OrdersController extends AppController {

	var $name = 'Orders';
	var $uses = array();
	var $components = array('Email'); 		
    
	function beforeFilter() {
		parent::adminLayout();
	}
}
?>