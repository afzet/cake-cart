<?php

Configure::write('debug', 2);
class GoogleController extends AppController {
	
	var $name = 'Google';
	var $uses = array();
	var $helpers = array('Gateways.Payment');
	var $components = array('DebugKit.Toolbar');
	
	function purchase() {
	}
	
	function callback() {
		$data['GooglePostback']['data'] = serialize($_REQUEST);
		ClassRegistry::init('GooglePostback')->save($data);
	}
}
?>