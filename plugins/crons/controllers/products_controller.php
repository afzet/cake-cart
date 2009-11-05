<?php

class ProductsController extends CronsAppController {
    
	var $uses = array('Product');
	var $components = array('Crons.Download');
	
	function beforeFilter() {
		$this->cron_path = APP . 'plugins' . DS . 'crons' . DS . 'files' . DS;
    	$this->Download->fetch();
	}
	
    function update() {
    	$this->listFiles();
    	die;
    }
    
    function listFiles() {
		if (is_dir($this->cron_path)) {
			if ($dh = opendir($this->cron_path)) {
				while (($file = readdir($dh)) !== false) {
					$type = filetype($this->cron_path . $file);
					switch($type):
						case 'file': 
							$this->processCSV($file);
							break;
					endswitch;
				}
				closedir($dh);
			}
		}
    }
    
    function processCSV($filename) {
    	$handle = @fopen($this->cron_path . $filename, "r");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE):
			$product = $this->Product->find('first', array(
					'conditions' => array('Product.product_code' => $data[0]),
					'fields' => array('Product.id'),
					'recursive' => 1
				));
			$this->Product->id = $product['Product']['id'];
			$this->Product->saveField('out_of_stock', 1);
		endwhile;
    }
}
?>