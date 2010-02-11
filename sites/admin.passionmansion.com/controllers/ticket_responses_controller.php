<?php
/**
 * SVN FILE: $Id: ticket_responses_controller.php 518 2008-09-05 04:22:40Z jonathan $
 *
 * Ticket Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 518 $
 * Last Modified: $Date: 2008-09-04 23:22:40 -0500 (Thu, 04 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class TicketResponsesController  extends AppController {

	var $name = 'TicketResponses';
  var $uses = array('Ticket','TicketResponse');
  var $helpers = array('Form','Html');
	var $components = array('Email'); 
	
	function add() {	  
	  if(!empty($this->data)) {
	    $this->TicketResponse->set($this->data);
	    if($this->TicketResponse->save($this->data['TicketResponse'])) {
	      $this->data['Ticket']['id'] = $this->data['TicketResponse']['ticket_id'];
	      $this->data['Ticket']['status'] = 2;
	      $this->Ticket->save($this->data['Ticket']);
	      $this->redirect('/tickets/view/'.$this->data['TicketResponse']['ticket_id']);
	      exit;
	    }
	  }
	}
	
}
?>
