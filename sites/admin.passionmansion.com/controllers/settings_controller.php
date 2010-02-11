<?php
/* SVN FILE: $Id: settings_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class SettingsController extends AppController 
{
	var $name = 'Settings';
	var $helpers = array('Html', 'Form', 'Session' );
	var $uses = array('Setting' );
	var $components = array('Auth');		
	var $scaffold;
        
	public function beforeFilter() {
		if($user = $this->Auth->user()) {
			if ($user['User']['group_id']!=1) {
				$this->redirect('/pages/noaccess');
			}
		}
	}
}
?>