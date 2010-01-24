<?php
/**
 * SVN FILE: $Id: newsletter_controller.php 212 2008-08-31 03:22:51Z jonathan $
 *
 * Newsletter Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 212 $
 * Last Modified: $Date: 2008-08-30 23:22:51 -0400 (Sat, 30 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class NewsletterController extends AppController {

	var $name = 'Newsletter';
	var $components = array('Email'); 
	var $uses = array('Subscriber'); 
	
	function signup() {
		App::import('Core', 'sanitize');
		if (!empty($this->data))  {		
		  $data = Sanitize::clean($this->data);
		    $data['Subscriber']['validation_code'] = $this->__createCode($data['Subscriber']['email']);
		    $this->Subscriber->set($data);
		    $this->Subscriber->save($data['Subscriber']);
			if ($this->Subscriber->validates($data['Subscriber'])) {
			    $this->__confirmation($data);
				$this->redirect(array('action'=>'success'), null, true);
		    } 
		    else  {
		      $errors = $this->Subscriber->invalidFields();
				  $this->Session->setFlash('Sorry please try again!');
			}
		}
	}
	
	function remove() {
		App::import('Core', 'sanitize');
		if (!empty($this->data))  {		    
		  $post = Sanitize::clean($this->data);
		  $email = $post['Subscriber']['email'];
		   if ($data = $this->Subscriber->find(array('Subscriber.email'=>$email))) {
			  $this->Subscriber->delete($data['Subscriber']['id']);
				$this->Session->setFlash('Email successfully removed!');
		    } 
		    else  {
		        $errors = $this->Subscriber->invalidFields();
				$this->Session->setFlash('Sorry please try again!');
			}
		}
	}
	
	function success() {
	}

	
	function confirm() {
		App::import('Core', 'sanitize');
		if (!empty($this->data))  {		
		  $post = Sanitize::clean($this->data);
		  $code = $posta['Subscriber']['validation_code'];
			if ($data = $this->Subscriber->find(array('Subscriber.validation_code'=>$code))) {
			    if ($data['Subscriber']['active'] == 0) {			        
    			    $data['Subscriber']['active'] = 1;
    			    $this->Subscriber->save($data['Subscriber']);
    			    $this->__confirmed($data);
    				$this->Session->setFlash('Success! Your email address has been validated');
			    }
			    else {
			        $this->Session->setFlash('This email has already been validated!');
			    }
		    } 
		    else  {
				$this->Session->setFlash('Sorry invalid confirmation code, please try again!');
			}
	    }
	}
	
	function __createCode($data) {		
	  $hash = sha1($data);
	  return substr($hash, 0, 8);
	}	
	
	function __confirmation($data) {			
        $this->Email->to 		= $data['Subscriber']['email'];
        $this->Email->subject 	= 'Newsletter Confirmation';
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
	
	function __confirmed($data) {
        $this->Email->to 		= $data['Subscriber']['email'];
        $this->Email->subject 	= 'Email Validated';
        $this->Email->replyTo 	= 'info@passionmansion.com';
        $this->Email->from 		= 'Passion Mansion <noreply@passionmansion.com>';
		$this->Email->smtpOptions = array(
		    'port'=>'25', 
		    'timeout'=>'30',
		    'host' => 'smtp.1and1.com',
		    'username'=>'jonathan@passionmansion.com',
		    'password'=>'m3m0tyh');
		$this->Email->delivery = 'smtp';
        $this->Email->template 	= 'newsletter_validated';
        $this->Email->sendAs 	= 'html';
        $this->set('data', $data);
       	$this->Email->send();
	}
}
?>
