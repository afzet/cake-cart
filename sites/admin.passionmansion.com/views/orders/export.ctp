<?

$header = '';
for($a=0; $a < count($data['fields']); $a++):
	$remove = array('customer ','payment ','order ');
	$data['fields'][$a] = str_replace('_',' ',$data['fields'][$a]);
	$data['fields'][$a] = str_replace($remove,'',$data['fields'][$a]);
	$data['fields'][$a] = strtolower($data['fields'][$a]);
	$data['fields'][$a] = ucwords($data['fields'][$a]);
	$header .= '"'. $data['fields'][$a] .'",';
endfor;

$item = substr($header, 0, -1) ."\n";

$number = array(
	'payment_tax',
	'payment_handling',
	'payment_shipping',
	'payment_fee',
	'payment_gross',
);
		
foreach ($data['orders'] as $order):
	if(strpos($order['Order']['store'], 'ioffer')) $order['Order']['store'] = 'iOffer';
	else $order['Order']['store'] = 'Website';
	
	foreach ($order['Order'] as $key => $value):
	
		if (in_array($key, $number)) $value = number_format($value, 2);
		
		if ($key == 'created') $value = date("m/d/y", $value); 
		elseif ($key == 'store') $value = $value; 
		elseif ($key == 'customer_email') $value = strtolower($value); 
		else $value = ucwords($value);				
		
		if ($value == '') $value = 'N/A'; 
		
		$value = str_replace(array('\n','\t','\r',',','  '), ' ', $value);
		$item .= '"'. $value .'",';
	endforeach;
	$item = substr($item, 0, -1) ."\n";

endforeach;

echo $item;
?>