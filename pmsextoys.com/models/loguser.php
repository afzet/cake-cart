<?php
/**
 * SVN FILE: $Id: loguser.php 58 2008-08-08 01:51:02Z jonathan $
 *
 * Loguser Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class Loguser extends AppModel {
    
    var $name = 'Loguser'; 
    
    function log_user($params) {
        if ($params['controller'] == 'category') {
            $this->data['Loguser']['category'] = $params['pass'][1];
        }        
        
        $this->data['Loguser']['controller'] = $params['controller'];
        $this->data['Loguser']['action']     = $params['action'];
        $this->data['Loguser']['method']     = $_SERVER['REQUEST_METHOD'];
        $this->data['Loguser']['agent']      = $_SERVER['HTTP_USER_AGENT'];
        $this->data['Loguser']['domain']     = str_replace('www.','',$_SERVER['SERVER_NAME']);
        $this->data['Loguser']['address']    = $_SERVER['REMOTE_ADDR'];
        $this->data['Loguser']['url']        = $_SERVER['REQUEST_URI'];
	    $this->save($this->data);
    }
}
?>

