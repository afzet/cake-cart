<?php
require_once APP.'config'.DS.'croogo_router.php';
CroogoRouter::plugins();

// Installer
if (!file_exists(APP.'config'.DS.'database.php')) {
    CroogoRouter::connect('/', array('plugin' => 'install' ,'controller' => 'install'));
}

Router::parseExtensions('json', 'rss','xml','html');

Router::connect('/contact', array('plugin' => 'contact', 'controller' => 'contacts', 'action' => 'add'));
Router::connect('/contact/thanks', array('plugin' => 'contact', 'controller' => 'contacts', 'action' => 'thanks'));

// Default
Router::connect('/', array('controller' => 'default', 	'action' => 'index'));

// Blocked
Router::connect('/blocked', array('controller' => 'pages',    	'action' => 'display', 'blocked'));

// Product View
Router::connect('/p/view'	, 	array('controller' => 'product', 	'action' => 'index'));
Router::connect('/p'	, 	array('controller' => 'product', 	'action' => 'index'));
Router::connect('/p/*', 	array('controller' => 'product', 	'action' => 'view'));
Router::connect('/c/*', 	array('controller' => 'category', 	'action' => 'view'));
Router::connect('/product_info/*', 	array('controller' => 'product', 	'action' => 'view'));

// Custom Error Message
Router::connect('/404', 			array('controller' => 'pages', 		'action' => 'display', 'error'));
Router::connect('/error/*', 		array('controller' => 'pages', 		'action' => 'display', 'error'));
Router::connect('/docs/*', 			array('controller' => 'pages', 		'action' => 'display'));

Router::connect('/sitemap',array('controller'=>'sitemaps','action'=>'index','url'=>array('ext'=>'xml'))); 

Router::connect('/admin', array('controller'=>'users','action'=>'login', 'prefix' => 'admin', 'admin' => true));


Router::connect('/newsletter/subscribe', array('plugin' => 'newsletter', 'controller' => 'subscriptions', 'action' => 'subscribe'));


Router::connect('/admin/categories/:action/*', array('admin' => 'true', 'controller' => 'category'));

/* Paypal IPN plugin */
Router::connect('/gateway/paypal/ipn', array('plugin' => 'paypal', 'controller' => 'orders', 'action' => 'process'));
Router::connect('/admin/orders/:action/*', array('admin' => 'true', 'plugin' => 'paypal', 'controller' => 'orders', 'action' => 'index'));
Router::connect('/admin/contacts/:action/*', array('admin' => 'true', 'plugin' => 'contact', 'controller' => 'contacts', 'action' => 'index'));
/* End Paypal IPN plugin */
?>