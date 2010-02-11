<?php
/**
 * SVN FILE: $Id: product.php 89 2008-08-19 08:58:53Z jonathan $
 *
 * Product Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 89 $
 * Last Modified: $Date: 2008-08-19 04:58:53 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class iOffer extends AppModel {

	var $name = 'iOffer'; 
	var $actsAs = array('Containable','Increment');
	var $belongsTo = array('Product');
}
?>