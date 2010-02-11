<?php

class ProductsController extends CronsAppController {
    
	var $uses = array('Product');
	
	var $feeds = array(
			'instock' => 'http://www.sextoyclub.com/datafeeds/csv/InStock.csv',
			'outofstock' => 'http://www.sextoyclub.com/datafeeds/csv/OutOfStock.csv',
			'discontinued' => 'http://www.sextoyclub.com/datafeeds/csv/discontinued.csv',
			'eld' => 'http://www.sextoyclub.com/datafeeds/csv2/by_vendor/ELD-02.csv',
			'ef' => 'http://www.sextoyclub.com/datafeeds/csv2/by_vendor/EF-02.csv'
		);
		
	
	
	function beforeFilter() {
		$this->cron_path = APP . 'plugins' . DS . 'crons' . DS . 'files' . DS;
		$this->published = $this->cron_path . DS . 'published' . DS;
		$this->unpublish = $this->cron_path . Ds . 'unpublish' . DS;
	}
	
	function update() {
		$this->published();
		$this->unpublish();
		$this->Product->query("UPDATE products SET product_price = product_cost*1.95");
		mail('jonathan@passionmansion.com', 'passionmansion.com', 'products were updated');
		mail('anthony@passionmansion.com', 'passionmansion.com', 'products were updated');
	}
	
	function published() {
	    exec('wget '. $this->feeds['instock'] .' -O ' . $this->published . 'instock.csv');
	    $this->processCSV($this->published . 'instock.csv', 0);
	    return true;
	}
	
    function unpublish() {
	    exec('wget '. $this->feeds['outofstock'] .' -O '. $this->unpublish .'outofstock.csv');
	    exec('wget '. $this->feeds['discontinued'] .' -O ' . $this->unpublish . 'discontinued.csv');
	    $this->processCSV($this->unpublish . 'outofstock.csv', 1);
	    $this->processCSV($this->unpublish . 'discontinued.csv', 1);
	    return true;
    }
    
    function eldFeed() {
	    exec('wget '. $this->feeds['eld'] .' -O ' . $this->unpublish . 'ELD-02.csv');
    	$this->processCSV($this->unpublish . 'ELD-02.csv', 1);
    }
    
    function efFeed() {
	    exec('wget '. $this->feeds['ef'] .' -O ' . $this->unpublish . 'EF-02.csv');
    	$this->processCSV($this->unpublish . 'ELD-02.csv', 1);
    }
    
    function processCSV($filename, $value) {
    	$handle = @fopen($filename, "r");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE):
			$product = $this->Product->find('first', array(
					'conditions' => array('Product.product_code' => $data[0]),
					'fields' => array('Product.id'),
					'recursive' => -1
				));
			$this->Product->id = $product['Product']['id'];
			$this->Product->saveField('out_of_stock', $value);
		endwhile;
    }
    
    function processCSV($filename, $value) {
    	$handle = @fopen($filename, "r");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE):
			$product = $this->Product->find('first', array(
					'conditions' => array('Product.product_code' => $data[0]),
					'fields' => array('Product.id'),
					'recursive' => -1
				));
			$this->Product->id = $product['Product']['id'];
			$this->Product->saveField('out_of_stock', $value);
		endwhile;
    }
}
?>