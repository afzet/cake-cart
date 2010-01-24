<?php
class OrderItemsController extends IpnAppController {

	var $name = 'OrderItems';
	var $helpers = array('Html', 'Form', 'Number', 'Time');

	function admin_index() {
		$this->OrderItem->recursive = 0;
		$this->set('OrderItems', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid OrderItem.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('OrderItem', $this->OrderItem->read(null, $id));
	}

	function admin_add(){
	   $this->redirect(array('admin' => true, 'action' => 'edit')); 
	}

	function admin_edit($id = null) {
		if (!empty($this->data)) {
			if ($this->OrderItem->save($this->data)) {
				$this->Session->setFlash(__('The OrderItem has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The OrderItem could not be saved. Please, try again.', true));
			}
		}
		if ($id && empty($this->data)) {
			$this->data = $this->OrderItem->read(null, $id);
		}
		$instantPaymentNotifications = $this->OrderItem->Order->find('list');
		$this->set(compact('instantPaymentNotifications'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for OrderItem', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->OrderItem->del($id)) {
			$this->Session->setFlash(__('OrderItem deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>