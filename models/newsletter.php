<?php
/**
 * SVN FILE: $Id: newsletter.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Newsletter Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Newsletter extends AppModel {

	var $name = 'Newsletter';
    
    var $validate = array(
		'email' => array(
            'valid' => array(
                'rule' => array('email', true),
                'message' => 'Error: Please supply a valid email address.'
            ),
            'unique' => array(
                'rule' => array('unique', 'email'),
                'message' => 'Error: Such email address already exists.'
            )
        )
	);
}
?>


