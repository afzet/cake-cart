<?php

class RepsController extends AppController {
	
	var $name = 'Reps';
	
	var $uses = array('Rep');
	var $components = array('DebugKit.Toolbar', 'Session');  
	var $helpers = array('Dojo', 'Html', 'Thumbnail', 'Javascript', 'Form');
	
	function beforeRender() {
		parent::beforeRender();
	}
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function login() {
		if (!empty($this->data)):
			App::import('Core', 'Sanitize');
			$this->data = Sanitize::clean($this->data, array('encode' => false));
			$conditions = array(
					'conditions' => array(
						'and' => array(
							'Rep.email' => $this->data['Rep']['email'],
							'Rep.password' => $this->data['Rep']['password'],
							'Rep.active' => 1
						)
					)
				);
			if(($rep = $this->Rep->find('first', $conditions)) == true):
				$this->Session->write('Vendor', $rep['Rep']);
				$this->redirect('/');
			else:
			
			endif;
		else:
			//echo '<pre>'; print_r($_POST); die;
		endif;
	}
	
	function logout() {
		$this->Session->delete('Vendor');
		$this->redirect('login');
		
	}
}
?>