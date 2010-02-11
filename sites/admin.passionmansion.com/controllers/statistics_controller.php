<?php
class statisticsController extends AppController {

	var $name = 'statistics';
	var $helpers = array('Html', 'Text', 'Form', 'Session');
	var $components = array('Auth');	
	var $uses = array('OrderItem');
	
	function index () {
		echo '<pre>';
		
		$year = '2008';
		$year_now 	= strtotime('1-1-'.$year);
		$year_next 	= strtotime('12-31-'.$year);
		
		$conditions = array(
			'conditions' => array(
				'Order.created <' => $year_now,
				'Order.created >' => $year_next,
			)
		);
		
		$data = $this->OrderItem->find('all', $conditions);
		
		print_r($data);
		exit();
	}
}
?>