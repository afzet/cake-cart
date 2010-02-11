<?php

class Party extends AppModel {
	
	function parties() {
		return $this->find('all', array(
			'fields' => array('Party.name', 'Party.code', 'Party.thumb'),
			'recursive' => -1,
			'limit' => 8,
			'order' => 'rand()'
		));
	}
}
?>