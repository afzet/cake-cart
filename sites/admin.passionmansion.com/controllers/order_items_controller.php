<?php
/* SVN FILE: $Id: orders_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class OrderItemsController extends AppController {

	var $name = 'OrderItems';
	var $helpers = array('Html', 'Text', 'Form', 'Session', 'Javascript');
	var $uses = array('Order','OrderItem');
	var $components = array('Auth','RequestHandler','Cookie');	
	
	public function view($id) {
		$data['order'] = $id;
		$data['items'] = $this->OrderItem->find('all', array('conditions' => array('OrderItem.order_id' => $id)));
		$this->set('data', $data);
	}
	
	public function edit($id) {
		$this->OrderItem->id = $id;
		if (!empty($this->data)) {
			$this->OrderItem->save($this->data['OrderItem']);
			$this->Session->setFlash('Item Updated');
			$this->redirect('/order_items/view/'.$this->data['OrderItem']['order_id']);
		}
		else {
			$this->data = $this->OrderItem->read(NULL, $id);
			$this->set('data', $this->data);
		}
	}
	
	public function add($id) {
		if (!empty($this->data)) {
			$this->OrderItem->save($this->data['OrderItem']);
			$this->Session->setFlash('Item Added');
			$this->redirect('/order_items/view/'.$this->data['OrderItem']['order_id']);
		}
		else {
			$this->set('id', $id);
		}
	}
	
	public function delete($id, $orderid) {
		$this->OrderItem->del($id);
		$this->Session->setFlash('Item Deleted');
		$this->redirect('/order_items/view/'.$orderid);
	}
	
}