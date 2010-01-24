<?php
/**
 * SVN FILE: $Id: contact_controller.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Contact Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class ContactController extends AppController {

	var $name = 'Contact';
    var $uses = array('Ticket','TicketResponse');
	var $components = array('Email'); 
	
	function beforeFilter() {
		parent::beforeFilter();
	}

	function index() {
		if (!empty($this->data))  {		   	    
		    $this->Ticket->set($this->data);				
			if (($data = $this->__structureTicket($this->data)) == true) {
			    $this->__notifySupport($data);
			    $this->Session->setFlash('Thank you for your inquiry. A new ticket has been created!');
		    } 
		    else  {
		        $errors = $this->Ticket->invalidFields();
				$this->Session->setFlash('Sorry please try again!');
			}
		}
	}
	
	function __structureTicket($data) {
		$code = $this->createCode();
		$data['Ticket']['id'] = $code;
		$data['TicketResponse']['ticket_id'] = $code;
		$data['TicketResponse']['sender'] = $data['Ticket']['customer'];				
		$this->Ticket->save($data);
		$this->TicketResponse->save($data);
		return $data;
	}
	
	function __notifySupport($data) {	
        $this->Email->to 		= 'support@passionmansion.com';
        $this->Email->subject 	= 'New Ticket Recieved';
        $this->Email->from 		= $data['Ticket']['customer'] .' <'. $data['Ticket']['email'] .'>';
		$this->Email->smtpOptions = array(
		    'port'=>'25', 
		    'timeout'=>'30',
		    'host' => 'smtp.1and1.com',
		    'username'=>'jonathan@passionmansion.com',
		    'password'=>'m3m0tyh');
		$this->Email->delivery = 'smtp';
		$this->Email->layout   = 'ticket';
        $this->Email->template = 'support/staff/notify';
        $this->Email->sendAs   = 'html';
        $this->set('data', $data);
       	$this->Email->send();
	}
  
	/**
	 * __createCode Action
	 * Create Code
	 * @access public
	 * @param array $data
	 * @return boolean $code
	 */
	public function createCode() {		
		$code_feed = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyv0123456789";
		$code_length = 10;  // Set this to be your desired code length
		$final_code = "";
		$feed_length = strlen($code_feed);
		
		for($i = 0; $i < $code_length; $i ++) {
		    $feed_selector = rand(0,$feed_length-1);
		    $final_code .= substr($code_feed,$feed_selector,1);
		}

	    $hash  = sha1($final_code);  // hash data with sha1
	    $hash2 = substr($hash, 0, 10);  // grab first 10 digits
	    $hash3 = strtoupper($hash2);  // force uppercase of all values
	    return $hash3;  // force uppercase of all values
	}	
}
?>
