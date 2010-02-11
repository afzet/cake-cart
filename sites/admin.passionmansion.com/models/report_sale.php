<?php
/* SVN FILE: $Id: subscriber.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class ReportSale extends AppModel {

	var $name = 'ReportSale';
	
	function search ($terms) {
		$conditions = array(
			'conditions' => $terms 	
		);
		
		$data = $this->find('all', $conditions);
		
		foreach ($data as $key => $value) 
		{
			// count records
			$info['count']['item'][] 	= $value['ReportSale']['id'];
			$info['count']['order'][] 	= $value['ReportSale']['id'];
			
			// calculate cost
			$info['costs']['price'][] 				= $value['ReportSale']['item_price'];
			$info['costs']['cost'][] 				= $value['ReportSale']['item_cost'];
			
			
		}
		// total counts
		$sales['count']['items'] = count($info['count']['item']);
		$sales['count']['order'] = count($info['count']['order']);
		
		// total income
		$sales['total']['profit_gross'] = array_sum($info['count']['item']);
		$sales['total']['profit_net'] 	= $sales['total']['profit_gross'] - array_sum($info['costs']['cost']);
		$sales['total']['cost'] 		= array_sum($info['costs']['cost']);
		
		echo '<pre>'; print_r($sales); die;
				
	}
}
?>