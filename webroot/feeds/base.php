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

$start = 100000;
$sql = sprintf(
	"SELECT 
	p.id, p.product_code, p.product_name, p.product_list, p.product_price, p.product_image, p.product_manf, 
	c.name as category
	FROM products p
	LEFT JOIN categories c ON p.category_id = c.id 
	WHERE p.category_id != ''  
	LIMIT $start, 50000");
$query = mysql_query($sql) or die(mysql_error());

$product = '';

$head  = 'link' ."\t". 'title' ."\t". 'description' ."\t". 'expiration_date' ."\t";
$head .= 'price' ."\t". 'image_link' ."\t". 'label' ."\t". 'id' ."\t". 'manufacturer' ."\t";
$head .= 'payment_accepted' ."\t". 'condition' ."\n";

while ($row = mysql_fetch_array($query)) {
	$name = htmlspecialchars_decode($row['product_name']);
	$name = str_replace(array('%',', ','&','-'), '', $name);
	$name = urlencode($name);
	$url = sprintf('https://passionmansion.com/p/%s/%s/%s.html', $row['id'], urlencode($row['product_code']), $name);
	
	$product .= $url ."\t";
	$product .= $row['product_name'] ."\t";
	$product .= $row['product_list'] ."\t";
	$product .= date('Y-m-d', time() + 2592000 ) ."\t";
	$product .= $row['product_price'] ."\t";
	$product .= imageFix($row['product_image']) ."\t";
	$product .= $row['category'] ."\t";
	$product .= $row['product_code'] ."\t";
	$product .= $row['product_manf'] ."\t";
	$product .= 'Visa, MasterCard, AmericanExpress, Discover' ."\t";
	$product .= 'New' ."\n";
} 
$myFile = "googlebase-$start.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $head . $product);
fclose($fh);
?>