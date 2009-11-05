<?php
/**
 * SVN FILE: $Id: country.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Country Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Country extends AppModel {

	var $name = 'Country';
	var $useTable = 'countries';
	
	function setCountries ($str) {
		$conditions = array('Country.'.$str => 1);		
		return $this->findAll($conditions);
	}
}
?>
