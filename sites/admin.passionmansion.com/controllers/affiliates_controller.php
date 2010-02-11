<?php
/* SVN FILE: $Id: users_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class AffiliatesController extends AppController {
	
	var $name = 'Affiliates';
	var $uses = array('Affiliate','OrderItem');		
	var $helpers = array('Html', 'Form', 'Session', 'Javascript');
	var $components = array('Auth','Cookie');		
	var $paginate = array('limit' => 15, 'order' => 'Affiliate.id');
	     
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
			$this->Session->setFlash('Invalid Affiliate!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$affiliate = $this->Affiliate->read(null, $id);
		$this->OrderItem->recursive = -1;
		$orderitem = $this->OrderItem->findAllByAff_code($affiliate['Affiliate']['aff_code']);
		for ($i=0; $i<=(count($orderitem)-1); $i++) {
		  $order_id = $orderitem[$i]['OrderItem']['order_id'];
		  $id = $orderitem[$i]['OrderItem']['id'];
		  $order['OrderItem'][$order_id][$id] = $orderitem[$i]['OrderItem'];
		}
		if(count($order['OrderItem'])>0) $data = array_merge($affiliate,$order);
		else  $data = $affiliate;
		$this->set('data', $data);
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Affiliate!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Affiliate->del($id)) {
			$this->Session->setFlash('Affiliate #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
}
?>