<?php
/**
 * SVN FILE: $Id: contact_controller.php 43 2008-08-05 16:43:59Z jonathan $
 *
 * Database Configuration
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 43 $
 * Last Modified: $Date: 2008-08-05 12:43:59 -0400 (Tue, 05 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class DATABASE_CONFIG {

    var $default = array(
        'driver' => 'mysql',
        'connect' => 'mysql_connect',
        'host' => 'localhost',
        'login' => 'pmansion',
        'password' => 'm3m0tyh',
        'database' => 'pmansion_production'
    );
    
    
	var $twitter = array(
		'datasource' => 'twitter',
		'username' => 'passionmansion',
		'password' => 'bella1077',
	);
	var $google_contacts = array(
		'datasource'  => 'google_contacts',
		'accounttype' => 'GOOGLE',
		'email'       => 'sale2turst@hotmail.com',
		'passwd'      => 'bella1077',
		'source'      => 'companyName-applicationName-versionID',
		'database'    => ''
	);
}


?>