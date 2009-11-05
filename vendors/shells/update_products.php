<?php 
class UpdateProductShell extends Shell {
	
    var $tasks = array('Download');
    
    var $Download;
    
    var $stc = array(
    	'outofstock' => 'http://www.sextoyclub.com/datafeeds/csv/OutOfStock.csv',
    	'discontinued' => 'http://www.sextoyclub.com/datafeeds/csv/discontinued.csv'
    );

    function startup() {
    	$this->path = APP . 'vendors' . DS . 'shells' . DS . 'files';
    }
    
    function update() {
    	$this->Download->getCSV();
    }
    
    function checkProducts() {
    	$feeds = $this->stc['feeds'];
    	foreach ($feeds as $key => $value):
	    	$handle = @fopen($this->csv_path . DS . $key . ".csv", "r");
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE):
			
				$product = $this->Product->find('first', array(
						'conditions' => array('Product.product_code' => $data[0]),
						'fields' => array('Product.id'),
						'recursive' => 1
					));
				echo '<pre>'; print_r($product); die;
				$this->Product->id = $product['Product']['id'];
				$this->Product->saveField('out_of_stock', 1);
			endwhile;
		endforeach;
    }
}
?>
