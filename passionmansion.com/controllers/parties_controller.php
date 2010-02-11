<?php
class PartiesController extends AppController {

	var $name = 'Parties';
	
	function index() {
		$data = $this->Party->parties();
		$this->set(compact('data'));
	}
	
	function show() {
		$this->paginate = array(
				'fields' => array('Party.name', 'Party.code', 'Party.thumb'),
				'recursive' => -1,
				// 'order' => 'rand()'
			);		
			
		$data = $this->paginate('Party');
		$this->set(compact('data'));
	}
	
	function register() {
		$msg = 'New Party Hosting Partner' . "\n";
		foreach ($this->data['Party'] as $key => $value):
			$msg .= $key . ': ' . $value . "\n";
		endforeach;
		mail('anthony@passionmansion.com','New Party Hosting Partner', $msg);
	}
}
?>
