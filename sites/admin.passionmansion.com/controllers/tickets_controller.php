<?php
/* SVN FILE: $Id: tickets_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class TicketsController extends AppController {
	
	var $name = 'Tickets';
	var $helpers = array('Html', 'Form', 'Session', 'Javascript');
	var $components = array('Auth','Cookie');		
	var $paginate = array('limit' => 15, 'order' => 'Ticket.created');
	var $uses = array('Ticket','TicketResponse');
	     
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function index()  {
		$this->set('data', $this->paginate());
	}
	
	public function add_response() {
		if (!empty($this->data))  {
			if ($this->TicketResponse->save($this->data['TicketResponse'])) {
				$this->Ticket->save($this->data);
				$this->Session->setFlash('The Ticket has been saved!');
				$this->redirect('/tickets/view/'.$this->data['Ticket']['id']);
			} 
			else {
				$this->Session->setFlash('The Subscriber could not be saved. Please, try again!');
			}
		}
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function view($id = null)  {
		if (!$id)  {
			$this->Session->setFlash('Invalid Ticket!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('data', $this->Ticket->read(null, $id));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function edit($id = null)  {
		if (!$id && empty($this->data))  {
			$this->Session->setFlash('Invalid Ticket!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data))  {
			if ($this->Ticket->save($this->data)) {
				$this->Ticket->setFlash('The Ticket has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else {
				$this->Session->setFlash('The Ticket could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Ticket->read(null, $id);
		}
	}
}
?>