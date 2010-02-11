<?php
/* SVN FILE: $Id: product.php 141 2008-08-19 13:32:57Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 141 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-19 08:32:57 -0500 (Tue, 19 Aug 2008) $
 */
class Product extends AppModel 
{

	var $name = 'Product';
	var $belongsTo = array('Category');
	
	var $hasMany = array(
			'Product' => array(
					'className' => 'Product',
					'foreignKey' => 'category_id',
			)
	);
}
?>