<?php
/* SVN FILE: $Id$ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision$
 * @modifiedby 		$LastChangedBy$
 * @lastmodified	$Date$
 */

Router::parseExtensions('rss','xml','html');
// Default
Router::connect('/', 				array('controller' => 'default', 	'action' => 'index'));

// Blocked
Router::connect('/blocked',         array('controller' => 'pages',    	'action' => 'display', 'blocked'));

// Sitemap
Router::connect('/sitemap',         array('controller' => 'sitemap',    'action' => 'index'));
Router::connect('/sitemap.xml',     array('controller' => 'sitemap',    'action' => 'index'));

// Product View
Router::connect('/p/view'	, 	array('controller' => 'product', 	'action' => 'index'));
Router::connect('/p'	, 	array('controller' => 'product', 	'action' => 'index'));
Router::connect('/p/*', 	array('controller' => 'product', 	'action' => 'view'));
Router::connect('/c/*', 	array('controller' => 'category', 	'action' => 'view'));

// Custom Error Message
Router::connect('/404', 			array('controller' => 'pages', 		'action' => 'display', 'error'));
Router::connect('/error/*', 		array('controller' => 'pages', 		'action' => 'display', 'error'));
Router::connect('/docs/*', 			array('controller' => 'pages', 		'action' => 'display'));


?>