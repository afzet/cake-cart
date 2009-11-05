<?php
/**
 * SVN FILE: $Id: setting.php 80 2008-08-19 08:26:34Z jonathan $
 *
 * Setting Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 80 $
 * Last Modified: $Date: 2008-08-19 04:26:34 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Setting extends AppModel {

	var $name = 'Setting';	
	
	function getSettings() {
		$settings = $this->findAll();
		$var = array();
		foreach ($settings as $setting) {
 			$var[$setting['Setting']['id']] = $setting['Setting']['value'];
		}
		return $var;
	}
}
?>
