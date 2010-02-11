<?php
/**
 * SVN FILE: $Id: mailer.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Mailer Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Mailer extends AppModel {

	var $name = 'Mailer';
	var $useTable = '';
	
	function __construct() {		
		$this->Email->smtpOptions = array(
		    'port'=>'25', 
		    'timeout'=>'30',
		    'host' => 'smtp.1and1.com',
		    'username'=>'jonathan@passionmansion.com',
		    'password'=>'m3m0tyh');
		$this->Email->delivery = 'smtp';
	}
	
	function confirmation($data) {		
        $this->Email->to 		= $data['OrderCustomer']['payer_email'];
        $this->Email->subject 	= 'Order Confirmation';
        $this->Email->replyTo 	= 'info@passionmansion.com';
        $this->Email->from 		= 'Passion Mansion <noreply@passionmansion.com>';
        $this->Email->template 	= 'confirmation';
        $this->Email->sendAs 	= 'html';
        $this->set('data', 'SMTP 2 - HTML - Cake and cream is good for you');
       	$this->Email->send();
	}
}
?>
