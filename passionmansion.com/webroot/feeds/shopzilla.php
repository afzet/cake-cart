#!/usr/bin/php -q

<?php

define('DS', DIRECTORY_SEPARATOR);
define('URL', 'http://passionmansion.com');
define('APP', '/var/www/vhosts/passionmansion.com/httpdocs/app/');
define('WEBROOT_DIR', 'webroot');
define('PRODUCT_IMAGES', APP . WEBROOT_DIR . DS . 'img' . DS . 'products' . DS);
define('URL_IMAGES', URL . DS . 'img' . DS . 'products' . DS);

function imageFix($image) {		
	$image = str_replace('http://images.sextoysex.com/', '', $image);
	$large = PRODUCT_IMAGES . $image;
	if (file_exists($large)): 
		return URL_IMAGES . $image;
	else: 
		return URL_IMAGES . 'notfound_thumb.jpg';
	endif;
}


mysql_connect('localhost','pmansion','m3m0tyh');
mysql_select_db('pmansion_production');

$sql = sprintf(
	"SELECT 
	p.id, p.product_code, p.product_name, p.product_list, p.product_price, p.product_image, p.product_manf, product_weight, product_upc,
	c.name as category
	FROM products p
	LEFT JOIN categories c ON p.category_id = c.id 
	WHERE p.category_id != '' LIMIT 200000");
$query = mysql_query($sql) or die(mysql_error());

$product = '';


$head  = 'Category ID' ."\t". 'Manufacturer' ."\t". 'Title' ."\t". 'Description' ."\t". 'Product URL' ."\t". 'Image URL' ."\t". 'SKU' ."\t". 'Availability' ."\t". 'Condition' ."\t". 'Ship Weight' ."\t". 'Ship Cost' ."\t". 'Bid' ."\t". 'Promotional Code' ."\t". 'UPC' ."\t". 'Price' ."\n";

while ($row = mysql_fetch_array($query)) {
	$name = htmlspecialchars_decode($row['product_name']);
	$name = str_replace(array('%',', ','&','-'), '', $name);
	$name = urlencode($name);
	$url = sprintf('http://passionmansion.com/p/%s/%s/%s.html', $row['id'], urlencode($row['product_code']), $name);
	
	$product .= '23,000,200' ."\t";
	$product .= $row['product_manf'] ."\t";
	$product .= $row['product_name'] ."\t";
	$product .= $row['product_list'] ."\t";
	$product .= $url ."\t";
	$product .= imageFix($row['product_image']) ."\t";
	$product .= $row['product_code'] ."\t";
	$product .= 'In Stock' ."\t";
	$product .= 'New' ."\t";
	$product .= $row['product_weight'] ."\t";
	$product .= '7.00' ."\t";
	$product .= '' ."\t";
	$product .= '' ."\t";
	$product .= '' ."\t";
	$product .= $row['product_price'] ."\n";
} 
$myFile = "shopzilla.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $head . $product);
fclose($fh);
?>