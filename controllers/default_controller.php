<?php

class DefaultController extends AppController  {
	
	var $name = 'Default';
	var $uses = array('Product', 'Post');
	
	function beforeFilter() {
		$this->Auth->allow('index');
	}
	
	function beforeRender() {
		$this->set('title_for_layout', $this->Session->read('Settings.site.title'));
	}
	
	function index()  {		
		$featured = $this->Product->featured();
		$vibrators = $this->Product->vibrators();
		$posts = $this->Post->newest();
		$this->set('featured', $featured);
		$this->set('vibrators', $vibrators);
		$this->set('post', $posts);
	}	
	
}
?>
