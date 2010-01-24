<?php
/**
 * SVN FILE: $Id: category.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Category Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Category extends AppModel {

	var $name = 'Category';
	var $useTable = 'categories';
	
	var $belongsTo = array(
		'Parent' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
			'fields' => array('Parent.name', 'Parent.id')
		)	
	);
	var $hasMany = array('Product');
	
	function nav_cats () {
			$conditions = array(
				'fields' => array('Category.name','Category.id'),
				'recursive' => -1,
				'conditions' => array('Category.parent_id' => 1),
				'order' => array('Category.name ASC')
			);		
			return $this->find('all', $conditions);
	}
}
?>
