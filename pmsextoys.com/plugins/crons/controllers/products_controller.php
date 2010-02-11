<?php

class ProductsController extends CronsAppController {
    
	var $uses = array('Product');
	
	function beforeFilter() {
		$this->cron_path = APP . 'plugins' . DS . 'crons' . DS . 'files' . DS;
	}
	
	function update() {
		$this->published();
		$this->unpublish();
		$this->Product->query("UPDATE products SET product_price = product_cost*1.95");
		mail('jonathan@passionmansion.com', 'passionmansion.com', 'products were updated');
		mail('anthony@passionmansion.com', 'passionmansion.com', 'products were updated');
	}
	
	function published() {
		$path = $this->cron_path . DS . 'published' . DS;
	    exec('wget http://www.sextoyclub.com/datafeeds/csv/InStock.csv -O ' . $path . 'instock.csv');
	    $this->processCSV($path . 'instock.csv', 0);
	    return true;
	}
	
    function unpublish() {
		$path = $this->cron_path . Ds . 'unpublish' . DS;
	    exec('wget http://www.sextoyclub.com/datafeeds/csv/OutOfStock.csv -O ' . $path . 'outofstock.csv');
	    exec('wget http://www.sextoyclub.com/datafeeds/csv/discontinued.csv -O ' . $path . 'discontinued.csv');
	    $this->processCSV($path . 'outofstock.csv', 1);
	    $this->processCSV($path . 'discontinued.csv', 1);
	    return true;
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