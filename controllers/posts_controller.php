<?php

class PostsController extends AppController 
{
	var $name = 'Posts';
	var $scaffold = 'admin';
	
	function beforeFilter() {
		parent::adminLayout();
	}
}
?>