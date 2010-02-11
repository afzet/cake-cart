<?php
/**
 * SVN FILE: $Id: ticket_response.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Ticket Response Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class TicketResponse extends AppModel {

	var $name = 'TicketResponse'; 
	var $actsAs = array('Containable');
    
    var $validate = array(   
		'body' => array(
            'valid' => array(
                'rule' => VALID_NOT_EMPTY,
                'message' => 'Error: Please enter a message so we know what to contact you about.'
            )
        )
	); 
	
	var $belongsTo = array(
		'Ticket' => array(
			'className' => 'Ticket',
			'foreignKey' => 'ticket_id'
		)
	);

}
?>
