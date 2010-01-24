<?php
/**
 * SVN FILE: $Id: payment.php 330 2008-08-31 07:42:55Z jonathan $
 *
 * Order Item Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 330 $
 * Last Modified: $Date: 2008-08-31 03:42:55 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */ 
class Payment extends AppModel {

	var $name = 'Payment'; 
	var $actsAs = array('Containable','Increment');
	
  var $belongsTo = array('Affiliate');
	
	
	function ttlPaid($id) {
		$conditions = array('Payment.affiliate_id'=>$id);
		$fields     = array('SUM(Payment.amount_paid) as paid');
		return $this->findAll($conditions,$fields);
	}
	
}
?>
