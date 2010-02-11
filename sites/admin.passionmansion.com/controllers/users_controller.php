<?php
/* SVN FILE: $Id: users_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class UsersController extends AppController {
	
	var $name = 'Users';
	var $helpers = array('Html', 'Form', 'Session', 'Javascript');
	var $components = array('Auth','Cookie');		
	var $paginate = array('limit' => 15, 'order' => 'User.id');
	     
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function index()  {
		$this->set('data', $this->paginate());
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function view($id = null)  {
		if (!$id)  {
			$this->Session->setFlash('Invalid User!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('user', $this->User->read(null, $id));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function add() {
		if (!empty($this->data))  {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The User has been saved!');
				$this->redirect(array('action'=>'index'), null, true);
			} 
			else $this->Session->setFlash('The User could not be saved. Please, try again!');
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function edit($id = null)  {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid User!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data)) {			
            App::import('Core', 'sanitize');
            $data = Sanitize::clean($this->data);
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The User has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else $this->Session->setFlash('The User could not be saved. Please, try again!');
		}
		if (empty($this->data)) $this->data = $this->User->read(null, $id);
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for User!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->User->del($id)) {
			$this->Session->setFlash('User #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function login()  {  
		if ($this->Auth->user())  {  
			if (!empty($this->data)) {  
				if (empty($this->data['User']['remember_me']))  {  
					$this->Cookie->del('User');  
				}  
				else {  
					$cookie = array();  
					$cookie['username'] = $this->data['User']['username'];  
					$cookie['token']    = $this->data['User']['password'];  
					$this->Cookie->write('User', $cookie, true, '+2 weeks');  
				}  				
				unset($this->data['User']['remember_me']);  
			}  
			$this->redirect($this->Auth->redirect());  
		}  
	}  
	
	function logout() {  
		$this->Cookie->del('User');  
		$this->redirect($this->Auth->logout());  
	}  
}
?>