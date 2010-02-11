<?php
/* SVN FILE: $Id: faqs_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class FaqsController extends AppController {
	
	var $name = 'Faqs';
	var $helpers = array('Html', 'Form', 'Javascript','Session' );
	var $components = array('Auth');		
	var $paginate = array('limit' => 15, 'order' => 'Faq.faq_category_id');   
	     
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
	public function add() {
		if (!empty($this->data)) 
		{
			$this->Faq->create();
			if ($this->Faq->save($this->data))
			{
				$this->Session->setFlash('The Faq has been saved!');
				$this->redirect(array('action'=>'index'), null, true);
			} 
			else {
				$this->Session->setFlash('The Faq could not be saved. Please, try again!');
			}
		}
		$faq_categories = $this->Faq->FaqCategory->find('list');
		$this->set(compact('faq_categories'));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function edit($id = null)  {
		if (!$id && empty($this->data))  {
			$this->Session->setFlash('Invalid Faq!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data))  {
			if ($this->Faq->save($this->data)) {
				$this->Session->setFlash('The Faq has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else {
				$this->Session->setFlash('The Faq could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Faq->read(null, $id);
		}
		$faq_categories = $this->Faq->FaqCategory->find('list');
		$this->set(compact('faq_categories'));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Faq!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Faq->del($id)) {
			$this->Session->setFlash('Faq #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
}
?>
