<?php
/* SVN FILE: $Id: orders_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class OrdersController extends AppController {

	var $name = 'Orders';
	var $helpers = array('Html', 'Text', 'Form', 'Session', 'Javascript');
	var $uses = array('Order','OrderItem','OrderNote');
	var $components = array('Auth','RequestHandler','Cookie');	
	var $paginate = array('limit' => 15, 'order' => array('Order.created' => 'desc'));
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function index() {
		$this->paginate = array(
			'conditions' => array(
				'Order.store!="sale2trust@hotmail.com"',
				'Order.store!="auction_ebay@passionmansion.com"',
				'Order.store!="anthony@passionmansion.com"'
			),
			'limit' => 15, 
			'order' => array('Order.created' => 'desc')
		);	
		$data = $this->paginate('Order');
		$this->set(compact('data'));

	}
	
	public function export() {
		$data['fields'] = array(
			'id','store','order_status','payment_tax','payment_handling',
			'payment_shipping','payment_fee','payment_gross','payment_currency',
			'customer_name','customer_email','customer_street','customer_city',
			'customer_state','customer_zip','customer_country','customer_phone','created'
		);
		
		$conditions = array(
			'fields' => $data['fields'],
			'order' => array('Order.created' => 'desc'),
			'recursive' => -1,
			'conditions' => array(
				'Order.store !=' => 'sale2trust@hotmail.com',
				'Order.customer_email !=' => 'ed@thefloridadesigngroup.com',
			)
		);
		
		$data['orders'] = $this->Order->find('all', $conditions);
		$this->layout = 'csv';
		$this->set(compact('data'));
	}
	
	public function ebay() {
		$this->paginate = array(
			'conditions' => array(
				'Order.auction_buyer_id <> ""'
			),
			'limit' => 15, 
			'order' => array('Order.created' => 'desc')
		);	
		$data = $this->paginate('Order');
		$this->set(compact('data'));

	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function view($id = null) {
		$this->Order->id = $id;
		$data = $this->Order->read();
		switch ($data['Order']['customer_country']) {
			case "US": $data['rate'] = 7.00;	$data['handling'] = 8.95;	$data['extra'] = 0.00; break;
			case "CA": $data['rate'] = 9.00;	$data['handling'] = 8.95;	$data['extra'] = 0.00; break;
			case "AU": $data['rate'] = 75.00;	$data['handling'] = 0.00;	$data['extra'] = 3.50; break;
			default:   $data['rate'] = 25.00;	$data['handling'] = 0.00;	$data['extra'] = 3.50;
		}
		$this->set(compact('data'));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function add_note() {
		if (!empty($this->data))  {
			$this->OrderNote->create();
			$this->data['OrderNote']['author'] = $this->Auth->user('username');
			if ($this->OrderNote->save($this->data)) {
				$this->Session->setFlash('The Note has been saved!');
				$this->redirect('/orders/view/'.$this->data['OrderNote']['order_id']);
			} 
			else {
				$this->Session->setFlash('The Note could not be saved. Please, try again!');
				$this->redirect('/orders/view/'.$this->data['OrderNote']['order_id']);
			}
		}
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function edit($id = null)  {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid Order!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data)) {
			if ($this->Order->save($this->data)) {
				$this->Session->setFlash('The Order has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else {
				$this->Session->setFlash('The Order could not be saved. Please, try again!');
			}
		}
		if (empty($this->data))  {
			$this->data = $this->Order->read(null, $id);
		}
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function update_order_status() {
		Configure::write('debug',0);
        if ($this->data) {
            App::import('Core', 'sanitize');
            $order_status = Sanitize::clean($this->data['Order']['order_status']);
            $this->Order->id = $this->data['Order']['id'];
            $this->Order->saveField('order_status', $order_status);
            $this->set('order_status', $order_status);            
        }
    }
}
?>
