<?php 
App::import('Vendor', 'twitter', array('file' => 'twitter'. DS .'twitter.php'));
class TweetsController extends AppController
{
    var $name = 'Tweets';  
    var $uses = array();
    
    function beforeFilter() {
    }
    
    function index() {
        $twitter = new MyTwitter('jonathanbradley', 'm3m0tyh');
        $data['tweets'] = $twitter->userTimeLine();
        
        
        echo '<pre>';
        print_r($data);    
        die;         
    }
    
    function getFollowers() {
    	
    	$followers = $twitter->userFollowers();
    	
    }
}
?> 