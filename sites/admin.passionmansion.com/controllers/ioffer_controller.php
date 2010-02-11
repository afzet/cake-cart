<?php
class IofferController extends AppController {

	var $name = 'Ioffer';
	var $uses = array('Ioffer');
	var $helpers = array('Html', 'Text', 'Form', 'Session');
	var $components = array('RequestHandler','Auth');		
	var $paginate = array('limit' => 25, 'order' => 'Ioffer.id');
	
	
	public function index()  {
		$data = $this->paginate('Ioffer');		
		$this->set(compact('data'));
	}

	
	public function search() {
		if(!empty($this->data)) {
			$this->paginate = array('conditions'=>$this->data['Ioffer'],'limit'=> 25);		
		}
			$data = $this->paginate('Ioffer');		
			$this->set(compact('data'));
			
	}
}
?>
