<?php
/**
 * SVN FILE: $Id: affiliates_controller.php 437 2008-09-01 03:11:58Z jonathan $
 *
 * Orders Controller
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 437 $
 * Last Modified: $Date: 2008-08-31 23:11:58 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
class AffiliatesController extends AppController {

	/**
	 * View Name
	 * @var boolean 
	 */
	var $name = 'Affiliates'; 
	
	/**
	 * Models Used
	 * @var array 
	 */ 
	var $uses = array('Affiliate','OrderItem','Payment');
	
	/**
	 * Helpers Used
	 * @var array 
	 */ 
	var $helpers = array('Form','Html');
	
	/**
	 * Components Used
	 * @var array 
	 */ 
	var $components = array('Email','Crypter','Cookie'); 

	/**
	 * Index Action
	 */
  public function index() {
    // render add action
    $this->render('add');
  }

	/**
	 * Add Action
	 * Add an affiliate
	 * @access public
	 * @param object $this->data
	 */
  public function add() {
    // check if data submitted
    if(!empty($this->data)) {      
		  $this->Affiliate->set($this->data); // set Affiliate Data           
		  // validate AffiliateDetail Data      
			if ($this->Affiliate->validates($this->data['Affiliate'])) {	    
		    // check if tax_unsafe is empty
		    if (!empty($this->data['Affiliate']['tax_unsafe'])) {	
		       // encrypt tax
			    $this->data['Affiliate']['tax'] = $this->Crypter->enCrypt($this->data['Affiliate']['tax_unsafe']);	    
			  } 	  
			   // encrypt ssn
			  $this->data['Affiliate']['ssn'] = $this->Crypter->enCrypt($this->data['Affiliate']['ssn_unsafe']);
			  
			  $sha1 = sha1(serialize($this->data));
			  $hash = $this->__createCode($sha1);
			  $this->data['Affiliate']['verification'] = $sha1;  // set validation code
			  $this->data['Affiliate']['password_hashed'] = sha1($this->data['Affiliate']['password']);  // set validation code
			  $this->data['Affiliate']['customer_code'] = ucwords(substr($sha1, 12, 6) . '-'.substr($hash, 8, 4));  // set aff code
			 
			  $this->Affiliate->save($this->data['Affiliate']);  // save Affiliate
			  $this->__confirmation($this->data);  // send validation information email
			  $this->Session->setFlash('Signup Successfull!');  // set message
			  $this->redirect('/affiliates/success');  // redirect to success page
			  exit();
			} 
			else {
		    $errors = $this->Affiliate->invalidFields();  // invalidate Affiliate
				$this->Session->setFlash('Sorry please try again!');  // set message
			}
    }
  }
  
	/** * Success Action */
  public function success() {
  }
  
	/**
	 * Confirm Action
	 * Confirm Email Address
	 * @access public
	 * @param object $this->data
	 */
	public function confirm() {
    // check if data submitted
		if (!empty($this->data))  {		
		  // find affiliate information
			if ($data = $this->Affiliate->find($this->data['Affiliate'])) {
			  if ($data['Affiliate']['active'] == 0) {			        
    		  $data['Affiliate']['active'] = 1;  // set to address
    		  $this->Affiliate->save($data['Affiliate']);  // save Affiliate update
    			$this->Session->setFlash('Success! Your email address has been validated');  // set message
			  $this->redirect('/affiliates/login');  // redirect to success page
			  }
			  else $this->Session->setFlash('This email has already been validated!');  // set message
		  } 
		  else $this->Session->setFlash('Sorry invalid confirmation code, please try again!');  // set message
	  }
	}
  
	/**
	 * Login Action
	 * Login Affiliate
	 * @access public
	 * @param array $this->data
	 */
	public function login() {			
    // check if data submitted
	  if(!empty($this->data)) {
	    $this->data['Login']['password_hashed'] = sha1($this->data['Login']['password']);  // set to address
	    unset($this->data['Login']['password']);
	    // check if the user exists
	    if(($user = $this->Affiliate->find($this->data['Login'])) == true) {
	      
				$cookie = array();  
				$cookie['aff_code'] = $this->data['Login']['aff_code'];  
				$cookie['password_hashed'] = $this->data['Login']['password_hashed'];  
				$this->Cookie->write('Affiliate', $cookie, true, '+2 weeks');  
					
	      $this->Session->write('Affiliate',$user['Login']);  // set the Affiliate Information
  			$this->redirect(array('action'=>'profile'), null, true);  // redirect to profile page
	    }
	    else $this->Session->setFlash('Invalid Username or Password!');  // set message
	  } 
	  else {
	    if(($cookie = $this->Cookie->read('Affiliate')) == true) {
	      $user = $this->Affiliate->find($cookie);
	    }
	  }
	}
  
	/**
	 * Profile Action
	 * Affiliate Account View
	 * @access public
	 * @param array $this->data
	 */
	public function profile() {			
	  $this->__isAffiliate();
	  $aff = $this->Cookie->read('Affiliate');
	  $affiliate = $this->Affiliate->find($aff);
	  $payments = $this->Payment->findAllByAffiliate_id($affiliate['Affiliate']['id'],null,'Payment.created ASC');	
	  $paid = $this->Payment->ttlPaid($affiliate['Affiliate']['id']);	  
	  $sales = $this->OrderItem->getSales($affiliate['Affiliate']['aff_code']);	
	  $this->set('affiliate',$affiliate['Affiliate']);  
	  $this->set('payments',$payments);
	  $this->set('paid',$paid[0][0]['paid']);
	  $this->set('sales',$sales[0][0]);
	}
  
	/**
	 * Confirmation Action
	 * Send Confirmation Email
	 * @access public
	 * @param array $data
	 */
	private function __confirmation($data) {			
	  $this->__emailInfo(); // get email information
    $this->Email->to  = $data['Affiliate']['email'];  // set to address
    $this->Email->subject = 'Affiliate Confirmation';  // set subject
    $this->Email->template = 'affiliate/confirmation';  // invalidate Affiliate
    $this->Email->layout   = 'ticket';
    $this->set('data', $data);  // set data
   	$this->Email->send(); // send email
	}
}
?>