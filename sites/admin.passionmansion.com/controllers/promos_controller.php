<?php
class PromosController extends AppController {

	var $name = 'Promos';
	var $uses = array('Promo');
	var $helpers = array('Html', 'Text', 'Form', 'Session');
	var $components = array('RequestHandler','Auth');		
	var $paginate = array('limit' => 25, 'order' => 'Promo.id');


	function index() {
		$data = $this->paginate('Promo');		
		$this->set(compact('data'));

	}

	public function add() {
		if (!empty($this->data)) 
		{
		  if ($this->data['Promo']['skip_date'] == 0):
  		  $start_month = $this->data['Promo']['date_start']['month'];
  		  $start_day = $this->data['Promo']['date_start']['day'];
  		  $start_year = $this->data['Promo']['date_start']['year'];
  		  $this->data['Promo']['date_start'] = strtotime($start_year.'/'.$start_month.'/'.$start_day);
  		  
  		  $end_month = $this->data['Promo']['date_end']['month'];
  		  $end_day = $this->data['Promo']['date_end']['day'];
  		  $end_year = $this->data['Promo']['date_end']['year'];
  		  $this->data['Promo']['date_end'] = strtotime($end_year.'/'.$end_month.'/'.$end_day);
		  else:
  		  $this->data['Promo']['date_start'] = "X";
  		  $this->data['Promo']['date_end'] = "X";
		  endif;
		  
		  unset($this->data['Promo']['skip_date']);
		  
			if ($this->Promo->save($this->data))
			{
				$this->Session->setFlash('The Promo Code has been saved!');
				$this->redirect(array('action'=>'index'), null, true);
		} else 
			{
				$this->Session->setFlash('The Promo Code could not be saved. Please, try again!');
			}
		}
	}
	
	public function edit($id = null)  {
		if (!$id && empty($this->data))  {
			$this->Session->setFlash('Invalid Promo Code!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data))  {
		  if ($this->data['Promo']['skip_date'] == 0):
  		  $start_month = $this->data['Promo']['date_start']['month'];
  		  $start_day = $this->data['Promo']['date_start']['day'];
  		  $start_year = $this->data['Promo']['date_start']['year'];
  		  $this->data['Promo']['date_start'] = strtotime($start_year.'/'.$start_month.'/'.$start_day);
  		  
  		  $end_month = $this->data['Promo']['date_end']['month'];
  		  $end_day = $this->data['Promo']['date_end']['day'];
  		  $end_year = $this->data['Promo']['date_end']['year'];
  		  $this->data['Promo']['date_end'] = strtotime($end_year.'/'.$end_month.'/'.$end_day);
		  else:
  		  $this->data['Promo']['date_start'] = "X";
  		  $this->data['Promo']['date_end'] = "X";
		  endif;
		  
		  unset($this->data['Promo']['skip_date']);
		  
			if ($this->Promo->save($this->data))
			if ($this->Promo->save($this->data)) {
				$this->Session->setFlash('The Promo Code has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else {
				$this->Session->setFlash('The Promo Code could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Promo->read(null, $id);
		}
	}
	
	public function delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Promo Code!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Promo->del($id)) {
			$this->Session->setFlash('Promo Code #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
}
?>
