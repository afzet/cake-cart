<?php

class DefaultController extends AppController  {
	
	var $name = 'Default';
	var $uses = array('Product', 'Post');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}
	
	function beforeRender() {
		parent::beforeRender();
	}
	
	function index()  {		
		$featured = $this->Product->featured();
		$newest = $this->Product->newest();
		$posts = $this->Post->newest();
		$this->set('featured', $featured);
		$this->set('newest', $newest);
		$this->set('post', $posts);
		$this->set('title_for_layout', $this->Session->read('Settings.site.title'));
	}	
	
}
?>
