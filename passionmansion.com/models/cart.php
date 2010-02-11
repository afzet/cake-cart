<?php
/**
 * SVN FILE: $Id: cart.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Cart Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Cart extends AppModel {

	var $name = 'Cart';
	var $useTable = false;
	
	var $recursive = -1;
	var $cacheQueries = true;
	
	function add($id = null) {	
		print_r($this->param['pass']);
		die;
	}
}
?>
