<?php
/**
 * SVN FILE: $Id: rating.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Rating Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Rating extends AppModel {

	var $name = 'Rating';	

	var $belongsTo = array(
			'Product' => array(
				'className' => 'Product',
				'foreignKey' => '', 
				'conditions' => 'Rating.product_code = Product.product_code',
			)
	);
}
?>
