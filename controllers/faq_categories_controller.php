<?php
/* SVN FILE: $Id: faq_categories_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class FaqCategoriesController extends AppController {

	var $name = 'FaqCategories';
	var $helpers = array('Html', 'Form', 'Session' );
	var $components = array('Auth');		
	var $scaffold = 'admin';

    
	function beforeFilter() {
		parent::adminLayout();
	}
}
?>
