<?php
class OrdersController extends PaypalAppController {

	var $name = 'Orders';
	var $helpers = array('Html', 'Form');
	var $components = array('Auth','Email');
	
	/************
	  * beforeFilter makes sure the process is allowed by auth
	  *  since paypal will need direct access to it.
	  */
	function beforeFilter(){
	  parent::beforeFilter();
	  $this->Auth->allow('process','track');
	}  
	
	function track() {
		if(($this->params['url']['url'] = 'orders/track') == true) $data['tracking'] = $this->params['url'];
		else $data['tracking'] = '';
		$this->set('data',$data);
	}
	
	/*****************
	  * Paypal IPN processing action..
	  * This action is the intake for a paypal_ipn callback performed by paypal itself.
	  * This action will take the paypal callback, verify it (so trickery) and save the transaction into your database for later review
	  *
	  * @access public
	  * @author Nick Baker
	  */
	function process(){
	  if($this->Order->isValid($_POST)){
      foreach($notification['Order'] as $key => $data){
        $logval .= $key . " = " . $data . "\n";
      }
      $this->log($logval, 'debug');
      
      
      $notification = $this->Order->buildAssociationsFromIPN($_POST);
      $this->Order->saveAll($notification);
      $this->__processTransaction($this->Order->id);
	  }
	  $this->redirect('/');
  }
  
  /*************************
    * __processTransaction is a private callback function used to log a verified transaction
    * @access private
    * @param String $txnId is the string paypal ID and the id used in your database.
    */
  private function __processTransaction($txnId){
    $this->log("Processing Trasaction: $txnId",'paypal');
    //Put the afterPaypalNotification($txnId) into your app_controller.php
    $this->afterPaypalNotification($txnId);
  }
	
	/**
	  * Admin Only Functions... all baked
	  */
	
	/**
	  * Admin Index
	  */
	function admin_index() {	  
		$this->Order->recursive = 0;
		$this->set('orders', $this->paginate());
	}

	/**
	  * Admin View
	  * @param String ID of the transaction to view
	  */
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Order.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('Order', $this->Order->read(null, $id));
	}
	
	/**
	  * Admin Add
	  */
	function admin_add(){
	   $this->redirect(array('admin' => true, 'action' => 'edit')); 
	}

	/**
	  * Admin Edit
	  * @param String ID of the transaction to edit
	  */
	function admin_edit($id = null) {
		if (!empty($this->data)) {
			if ($this->Order->save($this->data)) {
				$this->Session->setFlash(__('The Order has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Order could not be saved. Please, try again.', true));
			}
		}
		if ($id && empty($this->data)) {
			$this->data = $this->Order->read(null, $id);
		}
	}

	/**
	  * Admin Delete
	  * @param String ID of the transaction to delete
	  */
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Order', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Order->del($id)) {
			$this->Session->setFlash(__('Order deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
	
}
?>