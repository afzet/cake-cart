<?php

class UsersController extends AppController 
{
	var $name = 'Users';
	var $uses = array('User');
	var $scaffold = 'admin';
	var $layout = 'admin';
	
	function beforeFilter() {		
		parent::beforeFilter();
	}
	
	function admin_login() {
		if($this->Auth->user()) {
		    $this->redirect(array('controller'=>'users', 'action'=>'index'));
		}
	}
	
	function admin_logout() {
		$this->Session->del('Auth.User');
		$this->redirect(array('controller'=>'pages', 'action'=>'display', 'home', 'admin'=>false));
	}
}
?>