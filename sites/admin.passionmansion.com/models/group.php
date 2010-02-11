<?php
/* SVN FILE: $Id: group.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class Group extends AppModel {
	
	var $name = 'Group';
	var $validate = array('name' => VALID_NOT_EMPTY);
	var $hasMany = array('User' => array('className' => 'User','foreignKey' => 'group_id'));

}
?>