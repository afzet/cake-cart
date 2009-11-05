<?php 
App::import('Core', 'Controller'); 
class DownloadTask extends Shell {
	
    
    var $feeds = array(
    	'outofstock' => 'http://www.sextoyclub.com/datafeeds/csv/OutOfStock.csv',
    	'discontinued' => 'http://www.sextoyclub.com/datafeeds/csv/discontinued.csv'
    );    
    
    function initialize() {
        $this->Controller =& new Controller();
    	$this->csv_path = APP . 'vendors' . DS . 'shells' . DS . 'files';
    }
    
    function getCSV() {
    	foreach ($this->feeds as $key => $value):
    		exec('curl '. $value .' -O ' . $this->csv_path . DS . $key . '.csv');
    	endforeach;
    }

}
?>