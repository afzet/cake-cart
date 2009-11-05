<?php

class DownloadComponent extends Object {
	
    var $feeds = array(
    	'outofstock' => 'http://www.sextoyclub.com/datafeeds/csv/OutOfStock.csv',
    	'discontinued' => 'http://www.sextoyclub.com/datafeeds/csv/discontinued.csv'
    );    
    
	function initialize(&$controller, $settings) {
		$this->cron_path = APP . 'plugins' . DS . 'crons' . DS . 'files' . DS;
	}
	
	function startup(&$controller) {
	}
	
    function fetch() {
    	foreach ($this->feeds as $key => $value):
	    	$path = $this->cron_path . $key . '.csv';
	    	exec('wget '. $value .' -O ' . $path);
    	endforeach;
    }
}
?>