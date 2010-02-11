<?php
/* SVN FILE: $Id: app_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class AppController extends Controller {

	var $components = array('Auth','RequestHandler','Cookie');	
	var $uses = array('User');      
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function beforeFilter()  {  
		Security::setHash('md5');  
		
		$this->Auth->loginAction    = array('controller' => 'users', 'action' => 'login');  
		$this->Auth->logoutRedirect = '/';  
		$this->Auth->loginError     = 'Wrong username / password combination';  
		$this->Auth->authError      = 'You must be logged in before you try to do that';  
		$this->Auth->authorize      = 'controller';  
		$this->Auth->autoRedirect   = false;  
		
		$cookie = $this->Cookie->read('User');  
		if (is_array($cookie) && !$this->Auth->user()){  
		if ($this->User->checkLogin($cookie['username'], $cookie['token']))  
		if (!$this->Auth->login($this->User))  
		    $this->Cookie->del('User');  
		}  
	}  
	    
	function beforeRender() {
		$this->set('auth_user',$this->Auth->user());
	}
	function isAuthorized() {
		if (isset($this->params[Configure::read('Routing.admin')])) {
			if ($this->Auth->user('group_id') != 1) {
				return false;
			}
		}
		return true;
   }
}
?>