<?php
class TweetsController extends AppController {
	
	var $helpers = array('Text');
	var $pageTitle = 'News &amp; Updates';
	
	function beforeFilter() {
		parent::beforeFilter();
        $this->Twitter = ConnectionManager::getDataSource('twitter');
        $this->user = $this->Twitter->account_verify_credentials();
        $this->Auth->allow('index');
	}
	
	function index(){
        $search_results = $this->Twitter->status_user_timeline($this->user['User']['id']);
        $this->set('tweets', $search_results['Statuses']['Status']);
	}
}
?>