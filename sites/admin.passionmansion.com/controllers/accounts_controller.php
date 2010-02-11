<?php
class AccountsController extends AppController {

	var $name = 'Accounts';
	var $helpers = array('Html', 'Text', 'Form', 'Session');
	var $components = array('Auth');	
	var $paginate = array('limit' => 15);
        
	public function beforeFilter() {
		if($user = $this->Auth->user()) {
			if ($user['User']['group_id']!=1) {
				$this->redirect('/pages/noaccess');
			}
		}
	}
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
			$this->Account->create();
			if ($this->Account->save($this->data))
			{
				$this->Session->setFlash('The Account has been saved!');
				$this->redirect(array('action'=>'index'), null, true);
		} else 
			{
				$this->Session->setFlash('The Account could not be saved. Please, try again!');
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
		if (!$id && empty($this->data))  {
			$this->Session->setFlash('Invalid Account!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data))  {
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash('The Account has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else {
				$this->Session->setFlash('The Account could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Account->read(null, $id);
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
			$this->Session->setFlash('Invalid id for Account!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Account->del($id)) {
			$this->Session->setFlash('Account #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
	
}
?>