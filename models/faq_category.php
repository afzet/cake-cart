<?php
/**
 * SVN FILE: $Id: faq_category.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Faq Category Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class FaqCategory extends AppModel 
{
	var $name = 'FaqCategory';
	var $hasMany = array(
			'Faq' => array('className' => 'Faq','foreignKey' => 'faq_category_id')
	);
}
?>