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

// Product View
Router::connect('/p/view'	, 	array('controller' => 'product', 	'action' => 'index'));
Router::connect('/p'	, 	array('controller' => 'product', 	'action' => 'index'));
Router::connect('/p/*', 	array('controller' => 'product', 	'action' => 'view'));
Router::connect('/c/*', 	array('controller' => 'category', 	'action' => 'view'));
Router::connect('/product_info/*', 	array('controller' => 'product', 	'action' => 'view'));



Router::connect('/cart/add_country/*'	, 	array('controller' => 'cart', 	'action' => 'add_country'));
Router::connect('/cart/add/*'	, 	array('controller' => 'cart', 	'action' => 'add'));
Router::connect('/cart'	, 	array('controller' => 'cart', 	'action' => 'index'));
// Custom Error Message
Router::connect('/404', 			array('controller' => 'pages', 		'action' => 'display', 'error'));
Router::connect('/error/*', 		array('controller' => 'pages', 		'action' => 'display', 'error'));
Router::connect('/docs/*', 			array('controller' => 'pages', 		'action' => 'display'));

Router::connect('/sitemap',array('controller'=>'sitemaps','action'=>'index','url'=>array('ext'=>'xml'))); 
?>