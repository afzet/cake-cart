<?php
/* SVN FILE: $Id: newsletters_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class NewslettersController extends AppController 
{
	var $name = 'Newsletters';
	var $helpers = array('Html', 'Form', 'Session' );
	var $uses = array('Newsletter','Subscriber');
	var $components = array('Auth','Email'); 
	var $paginate = array('limit' => 15);
        
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function index() {
		$this->set('data', $this->paginate());
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function compose() {
		if (!empty($this->data)) {
			$subscribers = $this->Subscriber->findAll();
			foreach ($subscribers as $mailer) {
				$this->__send($mailer,$this->data);
			}
			$this->data['Newsletter']['user_id'] = $this->Auth->user('id');
			$this->Newsletter->create();
			if ($this->Newsletter->save($this->data)) {
				$this->Session->setFlash('The Newsletter has been sent!');
				$this->redirect(array('action'=>'index'), null, true);
			} 
			else {
				$this->Session->setFlash('The Newsletter could not be sent. Please, try again!');
			}
		}
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Newsletter!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Newsletter->del($id)) {
			$this->Session->setFlash('Newsletter #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	private function __send($mailer,$data) {
        $this->Email->to 		= $mailer['Subscriber']['email'];
        $this->Email->subject 	= $data['Newsletter']['topic'];
        $this->Email->replyTo 	= 'info@passionmansion.com';
        $this->Email->from 		= 'Passion Mansion <noreply@passionmansion.com>';
		$this->Email->smtpOptions = array(
		    'port'=>'25', 
		    'timeout'=>'30',
		    'host' => 'smtp.1and1.com',
		    'username'=>'jonathan@passionmansion.com',
		    'password'=>'m3m0tyh');
		$this->Email->delivery = 'smtp';
        $this->Email->template 	= 'newsletter';
        $this->Email->sendAs 	= 'html';
        $this->set('data', $data);
       	$this->Email->send();
	}
	
}
?>