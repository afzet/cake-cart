<?php
/**
 * SVN FILE: $Id: default_controller.php 167 2008-08-26 17:42:10Z jonathan $
 *
 * Default Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 167 $
 * Last Modified: $Date: 2008-08-26 13:42:10 -0400 (Tue, 26 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class DefaultController extends AppController  {
	
	var $name = 'Default';
	var $uses = array('Product', 'Post');
	
	function index()  {		
		$this->pageTitle = $this->__getTitle();
		// $this->set('newest', $this->BestSeller->getFront());	
		$featured = $this->Product->featured();
		$vibrators = $this->Product->vibrators();
		$post = $this->Post->newest();
		$this->set(compact('featured', 'vibrators', 'post'));
	}	
	
 	function __getTitle() {
 		$title = ClassRegistry::init('Setting')->find(array('Setting.id'=>'main_title'));
 		return $title['Setting']['value'];
 	}
	
}
?>
