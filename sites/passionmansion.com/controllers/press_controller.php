<?php
/**
 * SVN FILE: $Id: press_controller.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Press Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class PressController extends AppController 
{	
	var $name = 'Press';  
	var $paginate;
	
	function index() 
	{
		$this->pageTitle = 'Press'; 
		$this->paginate = array('limit' => 12, 'order by' => 'Press.created');
		$data = $this->paginate('Press');
		$this->set(compact('data'));
	}
}
?>