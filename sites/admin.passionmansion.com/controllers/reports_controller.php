<?php
/* SVN FILE: $Id: reports_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class ReportsController extends AppController {
		
	var $name = 'Reports';    
	var $uses = array('Loguser');
    var $components = array('RequestHandler');
    var $helpers = array('Time', 'Xml');
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
    function index() { 
    	
    }
        
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function views() { 
		
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function products_viewed() { 
		
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function categories() { 
		
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function data_products_viewed() { 	
        $this->RequestHandler->respondAs('xml');
        $this->viewPath .= '/xml';
        $this->layoutPath = 'xml';
        
		$fields = array(
			"Loguser.id",
			"Loguser.address",
			"Loguser.created",
	    );
	    
	    $logs = $this->Loguser->findAll("Loguser.url LIKE '%product_info%'", $fields,'Loguser.created ASC',null);
	    
	    for($i=0; $i<=count($logs)-1; $i++) {
	        $date = preg_replace('/\d{1,2}:\d{2}:\d{2}/','',$logs[$i]['Loguser']['created']); 
	        $views[$date]['date'] = $date;
	        $views[$date]['hits'][$i] = $logs[$i]['Loguser']['address'];
	        
	    }
	    
	    foreach($views as $data) {	
	        $date = $data['date'];
	        $stats[$date]['date']  = $data['date'];
	        $stats[$date]['views'] = count($data['hits']);	        
	        $hits   = $data['hits'];
	        $unique = array_unique($hits);	        
	        $stats[$date]['unique'] = count($unique);
	    }
	    $this->set('data', $stats);
  	}	
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function data_views() { 	
        $this->RequestHandler->respondAs('xml');
        $this->viewPath .= '/xml';
        $this->layoutPath = 'xml';
        
		$fields = array(
			"Loguser.id",
			"Loguser.address",
			"Loguser.created",
	    );
	    
	    $logs = $this->Loguser->findAll(null, $fields,'Loguser.created ASC',null);
	    
	    for($i=0; $i<=count($logs)-1; $i++) {
	        $date = preg_replace('/\d{1,2}:\d{2}:\d{2}/','',$logs[$i]['Loguser']['created']); 
	        $views[$date]['date'] = $date;
	        $views[$date]['hits'][$i] = $logs[$i]['Loguser']['address'];
	        
	    }
	    
	    foreach($views as $data) {	
	        $date = $data['date'];
	        $stats[$date]['date']  = $data['date'];
	        $stats[$date]['views'] = count($data['hits']);	        
	        $hits   = $data['hits'];
	        $unique = array_unique($hits);	        
	        $stats[$date]['unique'] = count($unique);
	    }
	    $this->set('data', $stats);
  	}	
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	function data_categories() { 	
        $this->RequestHandler->respondAs('xml');
        $this->viewPath .= '/xml';
        $this->layoutPath = 'xml';
        
		$fields = array("Loguser.id","Loguser.address","Loguser.category");
	    
	    $logs = $this->Loguser->findAll('Loguser.category!=""', $fields,'Loguser.category ASC',null);
	    
	    for($i=0; $i<=count($logs)-1; $i++) {
	        $cat = $logs[$i]['Loguser']['category']; 
	        $views[$cat]['category'] = $cat;
	        $views[$cat]['hits'][$i] = $logs[$i]['Loguser']['address'];
	        
	    }
	    
	    foreach($views as $data) {	
	        $cat = $data['category']; 
	        $stats[$cat]['category']  = $data['category'];
	        $stats[$cat]['views'] = count($data['hits']);	        
	        $hits   = $data['hits'];
	        $unique = array_unique($hits);	        
	        $stats[$cat]['unique'] = count($unique);
	    }
	    
	    $this->set('data', $stats);
  	}	
}
?>
