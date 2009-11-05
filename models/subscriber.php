<?php
/**
 * SVN FILE: $Id: subscriber.php 461 2008-09-01 04:03:54Z jonathan $
 *
 * Subscriber Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 461 $
 * Last Modified: $Date: 2008-09-01 00:03:54 -0400 (Mon, 01 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Subscriber extends AppModel {

	var $name = 'Subscriber';
    
  var $validate = array(     
    'email' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your email address.'
      ),
      'valid' => array(
        'rule' => array('email', true),
        'message' => 'Please supply a valid email address.'
      ),
      'unique' => array(
        'rule' => array('unique', 'email'),
        'message' => 'Such email address already exists.'
      )
    )
	);
}
?>


