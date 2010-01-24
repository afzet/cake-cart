<?php
/**
 * SVN FILE: $Id: app_controller.php 489 2008-09-05 01:07:03Z jonathan $
 *
 * App Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 489 $
 * Last Modified: $Date: 2008-09-04 21:07:03 -0400 (Thu, 04 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class AppController extends Controller  {
	
	var $components = array('Auth', 'DebugKit.Toolbar');  
	var $helpers = array('Dojo', 'Html', 'Javascript', 'Form', 'Time', 'Number');
	
	function beforeFilter() {
		$this->Session->write('Settings', ClassRegistry::init('Setting')->getSettings());
		$this->Auth->autoRedirect = false;
	}
	/**
	 * Authentication Action
	 * Validate Affiliate Access Authentication
	 * @access public
	 * @param array $data
	 */
	function beforeRender() {
		$admin = Configure::read('Routing.admin');
		if (!isset($this->params[$admin])) {
			$this->__cats();
			$this->__checkCode();
			$this->set('searched', $this->Session->read('Search.recent'));
		}
	}	
	
	function isAuthorized() {
		if($this->Auth->user()) {
			$this->set('auth_user',$this->Auth->user());
		}
	}
	
	function adminLayout() {
		$admin = Configure::read('Routing.admin');
		if (isset($this->params[$admin]) && $this->params[$admin]) {
			$this->layout = 'admin';
			if($this->Auth->user()) {
				$this->set('auth_user',$this->Auth->user());
			}
		}
	}
	
	function checkBlock() {
		$blocked = ClassRegistry::init('Block')->find('all');
		$info = $this->countryCityFromIP();
		$state = preg_split('|, |',$info['city']);
		if (in_array($blocked,$info) || in_array($state[1],$blocked)) {
			$this->redirect('/blocked');
			exit();
		}
	}
	
	function __checkCode() {
		if (isset($this->params['url']['a'])) $this->Session->write('affiliate_code', $this->params['url']['a']);
		else  $this->Session->write('affiliate_code', '');
	}
	
	function __cats() {
		$cats = ClassRegistry::init('Category')->nav_cats();
		$this->set('navCats', $cats);		
	}
  
	/**
	 * __createCode Action
	 * Create Code
	 * @access public
	 * @param array $data
	 * @return boolean $code
	 */
	function __createCode($data) {		
	    $hash  = sha1($data);  // hash data with sha1
	    $hash2 = substr($hash, 0, 10);  // grab first 10 digits
	    $hash3 = strtoupper($hash2);  // force uppercase of all values
	    return $hash3;  // force uppercase of all values
	}	
  
	/**
	 * __createPassword Action
	 * Generate Random Password
	 * @access public
	 * @param array $data
	 */
	function __createPassword() {
  	$length = rand(6,12); // set length
		$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; // set characters
		$i = 0;
		$password = ""; // 
		while ($i <= $length) {
			$password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}
		return $password;
	}
  
	/**
	 * __isAffiliate Action
	 * Validate Affiliate Access Authentication
	 * @access public
	 * @param array $data
	 */
	function __isAffiliate() {	
    // check if affiliate session information exists
	  if(($affiliate = $this->Cookie->read('Affiliate')) == true) {
  	    return true;
	  }
	  else {
			$this->Session->setFlash('You are not authorized to view this page!');  // set message
  		$this->redirect(array('action'=>'login'), null, true);  // redirect to profile page
	  }
	}
  
	/**
	 * emailInfo Action
	 * Set Default Email Information
	 * @access public
	 * @param array $data
	 */
	function sendEmail($subject, $view, $to=null) {
		if ('CAKE_UNIT_TEST') {
			return 1;
		} else {
			$this->SwiftMailer->from = $this->Session->read('Settings.email.from');
			$this->SwiftMailer->fromName = $this->Session->read('Settings.site.company');
			$this->SwiftMailer->to   = $to;
			$this->SwiftMailer->smtpType = $this->Session->read('Settings.email.smtp_open');
			$this->SwiftMailer->smtpHost = $this->Session->read('Settings.email.smtp_host');
			$this->SwiftMailer->smtpPort = $this->Session->read('Settings.email.smtp_port');
			$this->SwiftMailer->smtpUsername = $this->Session->read('Settings.email.smtp_user');
			$this->SwiftMailer->smtpPassword = $this->Session->read('Settings.email.smtp_pass');
			$method = 'smtp';
			return $this->SwiftMailer->send($view, $subject, $method, true);
		}
	}
	
	function countryCityFromIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else $ipAddr = $_SERVER['REMOTE_ADDR'];
		ip2long($ipAddr) == -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
		$ipDetail=array();
		$xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);
		preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);
		$ipDetail['city'] = $match[2]; 
		preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);
		$ipDetail['country'] = $matches[1];
		preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
		$ipDetail['country_code'] = $cc_match[1];
		return $ipDetail;
	}
}
?>