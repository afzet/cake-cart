<?php
/**
 * SVN FILE: $Id: contact.php 445 2008-09-01 03:33:56Z jonathan $
 *
 * Contact Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 445 $
 * Last Modified: $Date: 2008-08-31 23:33:56 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Contact extends AppModel {

  var $name = 'Contact';
  
  var $validate = array(
    'name' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your name.'
      ),,
      'minLength' => array(
        'rule' => array('minLength', 3),
        'message' => 'Please enter atlest 5 characters.'
      )
    ),       
    'email' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your email address.'
      ),
      'valid' => array(
        'rule' => array('email', true),
        'message' => 'Please supply a valid email address.'
      )
    ),
    
    'topic' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your A Topic.'
      ),
      'minLength' => array(
        'rule' => array('minLength', 3),
        'message' => 'Please enter atlest 5 characters.'
      )
    ),        
    'body' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter a message so we know what to contact you about.'
      ),
      'valid' => array(
        'rule' => array('maxLength', 20),
        'message' => 'Please enter atlest 20 characters.'
      )
    )
  );

}
?>


