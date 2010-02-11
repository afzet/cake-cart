<?php

class SearchComponent extends Object {
	
	var $components = array('Session', 'Cookie');
	
	function startup(&$controller) {
		$cookie = $this->Cookie->read('Search.recent');
		if (is_array($cookie)) $this->recent = $cookie;
		else $this->recent = array();
		$this->recent = array();
	}
	
	function store($term, $count) {
		$new_item = self::__link($term) . ' - Found ' . $count . ' items';
		
		if (!in_array($new_item, $this->recent)):
			$this->recent[] = $new_item;
			self::reset();
			sort($this->recent);
		endif;
		$this->Cookie->write('Search.recent', $this->recent);
		$this->Session->write('Search.recent', $this->recent);
	}
		
	function reset() {
		if (count($this->recent) == 4) {
			unset($this->recent[0]);
		}
	}
	
	function __link($term) {
		App::import('Helper', 'Html');
		$this->Html = new HtmlHelper();
		$link = array(
				'controller' => 'product', 
				'action' => 'search', 
				'mainkeyword:' . $term
			);
		return $this->Html->link(ucwords(strtolower($term)), $link);
		
	}
}
?>