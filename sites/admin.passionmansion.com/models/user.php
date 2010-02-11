<?php
/* SVN FILE: $Id: user.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class User extends AppModel 
{
	var $name = 'User';
	var $belongsTo = array('Group');
	
	function checkLogin($username, $passhash) {  
		$user = $this->find(array('username' => $username, 'password' => $passhash), array(), null, 0);  		
		if ($user) {  
			$this->data = $user;  
			$this->id = $user['User']['id'];  
			return true;  
		}  		
		return false;  
	}  
}
?>