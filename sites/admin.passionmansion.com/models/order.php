<?php
/* SVN FILE: $Id: order.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class Order extends AppModel 
{
	var $name = 'Order';
	
	var $hasMany = array(
			'OrderItem' => array(
					'className' => 'OrderItem',
					'foreignKey' => 'order_id',
			),
			'OrderNote' => array(
					'className' => 'OrderNote',
					'foreignKey' => 'order_id',
			)
	);
}
?>