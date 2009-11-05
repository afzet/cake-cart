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
	
	// var $components = array('DebugKit.Toolbar');  
	/**
	 * Authentication Action
	 * Validate Affiliate Access Authentication
	 * @access public
	 * @param array $data
	 */
	public function beforeFilter() {
		$this->__cats();
		$this->__checkCode();
		// ClassRegistry::init('Product')->cats();
		$this->Session->write('Settings', ClassRegistry::init('Setting')->getSettings());
	}
	
	public function checkBlock() {
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
		$file = new File(CACHE.'/left_nav_cats.txt',true);
		if ($file->exists()):
			$data = $file->read();
			$file->close();
			$this->Session->write('NavCats', unserialize($data));
		else:
			$this->Session->write('NavCats', ClassRegistry::init('Setting')->getSettings());
		endif;
	}
  
	/**
	 * __createCode Action
	 * Create Code
	 * @access public
	 * @param array $data
	 * @return boolean $code
	 */
	public function __createCode($data) {		
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
	private function __createPassword() {
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
	public function __isAffiliate() {	
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
	public function __emailInfo() {	  
    $this->Email->replyTo = 'info@passionmansion.com';  // set replyTo address
    $this->Email->from 		= 'Passion Mansion <info@passionmansion.com>';  // set from address		
    $this->Email->smtpOptions = array(
  		'port'=>'25', // set port
		  'timeout'=>'30', // set timeout
		  'host' => 'smtp.1and1.com', // set smtp host
		  'username'=>'jonathan@passionmansion.com', // set smtp auth username
		  'password'=>'m3m0tyh' // set smtp auth password
		);		
    $this->Email->sendAs 	= 'html'; // set email format
	$this->Email->delivery = 'smtp'; // set email send method
	}
  
	/**
	 * countryCityFromIP action
	 * Get the True IP Address
	 * @access public
	 * @param array $data
	 */
	private function countryCityFromIP() {
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

