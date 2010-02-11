<?php
/**
 * SVN FILE: $Id: faqs_controller.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Faqs Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class FaqsController extends AppController {

	var $name = 'Faqs';
	

	function index() {
		$data = ClassRegistry::init('FaqCategory')->find('all');
		$this->set(compact('data'));
	}
	
	function view($id=null) {
		$this->Faq->id = $id;
		$this->set('data', $this->Faq->read());
	}
}
?>