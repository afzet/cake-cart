<?php
/* SVN FILE: $Id: categories_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class CategoriesController extends AppController {
	
	var $name = 'Categories';
	var $helpers = array('Html', 'Form', 'Session');
	var $components = array('Auth');		
	var $paginate = '';
	var $scaffold;
        
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function index()  {						
		$this->paginate = array(
	        	'limit' => 15,
				'conditions' => array('Category.parent_id' => 0),
				'order' => 'Category.name',
    	);
    	$this->Category->recursive = -1;	
    	$data = $this->paginate('Category');	
		$this->set('data', $this->paginate());
	}
        
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function child()  {						
		$this->paginate = array(
	        	'limit' => 15,
				'conditions' => array('Category.parent_id' => $this->params['pass'][0]),
				'order' => 'Category.name',
    	);
    	$this->Category->recursive = -1;	
    	$data = $this->paginate('Category');	
		$this->set('data', $this->paginate());
		$this->set('parent', urldecode($this->params['pass'][1]));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function view($id = null)  {
		if (!$id)  {
			$this->Session->setFlash('Invalid Category!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('Category', $this->Category->read(null, $id));
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function add() {
		if (!empty($this->data))  {
			$this->Category->create();
			if ($this->Category->save($this->data))
			{
				$this->Session->setFlash('The Category has been saved!');
				$this->redirect(array('action'=>'index'), null, true);
		} else  {
				$this->Session->setFlash('The Category could not be saved. Please, try again!');
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
			$this->Session->setFlash('Invalid Category!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (empty($this->data) == false) {
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash('The Category has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else {
				$this->Session->setFlash('The Category could not be saved. Please, try again!');
			}
		}
		if (empty($this->data))  {
			$this->data = $this->Category->read(null, $id);
		}
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Category!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Category->del($id)) {
			$this->Session->setFlash('Category #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
}
?>