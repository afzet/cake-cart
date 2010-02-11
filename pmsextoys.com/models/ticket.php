<?php
/**
 * SVN FILE: $Id: ticket.php 448 2008-09-01 03:37:59Z jonathan $
 *
 * Ticket Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 448 $
 * Last Modified: $Date: 2008-08-31 23:37:59 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Ticket extends AppModel {

	var $name = 'Ticket'; 
	var $actsAs = array('Containable');

	var $hasMany = array(
		'TicketResponse' => array(
			'className' => 'TicketResponse',
			'foreignKey' => 'ticket_id'
		)
	);
    
  var $validate = array(
    'customer' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your name.'
      ),
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
