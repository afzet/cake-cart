<?php
    Router::parseExtensions('rss','xml');
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	
    Router::connect('/reports/data_views',array('controller'=>'reports','action'=>'data_views'));
    Router::connect('/reports/data_views.xml',array('controller'=>'reports','action'=>'data_views'));
    
?>