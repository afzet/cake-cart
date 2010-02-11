<?php
/* SVN FILE: $Id: category.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class Category extends AppModel 
{
	var $name = 'Category';	
	
	var $validate = array(
		'name' => VALID_NOT_EMPTY
	);

	var $hasOne = array(
			'Parent' => array(
				'className' => 'Category',
				'foreignKey' => '', 
				'conditions' => 'Category.parent_id = Parent.id',
				'fields' => 'Parent.name AS parent'
			)
	);

	var $hasMany = array(
			'Product' => array(
				'className' => 'Product',
				'foreignKey' => 'category_id'
			)
	);

}
?>