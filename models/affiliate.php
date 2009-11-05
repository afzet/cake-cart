 <?php
/**
 * SVN FILE: $Id: affiliate.php 499 2008-09-05 01:56:38Z jonathan $
 *
 * Contact Model
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 499 $
 * Last Modified: $Date: 2008-09-04 21:56:38 -0400 (Thu, 04 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
define('VALID_DATE', '/^\d{4}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/');  
class Affiliate extends AppModel {

	var $name = 'Affiliate';

  var $validate = array(
    'contact_name' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your Contact Name.'
      ),
      'valid' => array(
        'rule' => array('maxLength', 30),
        'message' => 'Please enter atlest 3 characters.'
      ),
      'alphaNumeric' => array(
        'rule' => 'alphaNumeric',
        'message' => 'Sorry please only use alphanumeric characters.'
      )
    ),       
    'aff_code' => array(
      'required' => array(
        'rule' => array('custom', '/[a-z0-9]{6,}$/i'), 
        'on' => 'create',
        'message' => 'Only letters and integers, min 3 characters'
      ),
      'valid' => array(
        'rule' => 'isUnique',
        'on' => 'create',
        'message' => 'Sorry this code is already taken.'
      )
    ),   
    'password' => array(
      'required' => array(
        'rule' => array('custom', '/[a-z0-9]{3,}$/i'), 
        'on' => 'create',
        'message' => 'Only letters and integers, min 3 characters'
      )
    ),
    'date_of_birth' => array(
      'required' => array(
        'rule' => VALID_DATE, 
        'on' => 'create',
        'message' => 'Must enter a valid date MM-DD-YYYY'
      )
    ),
    'check_name' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter who to make your check out to.'
      ),
      'alphaNumeric' => array(
        'rule' => 'alphaNumeric',
        'message' => 'Sorry please only use alphanumeric characters.'
      ),
      'valid' => array(
        'rule' => array('maxLength', 30),
        'message' => 'Please enter atlest 3 characters.'
      )
    ),       
    'email' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your email address.'
      ),
      'valid' => array(
        'rule' => array('email', true),
        'message' => 'Please supply a valid email address.'
      )
    ),          
    'email_confirm' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your email address.'
      ),
      'valid' => array(
        'rule' => array('email', true),
        'message' => 'Please supply a valid email address.'
      )
    ),    
    'address' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your address.'
      ),
      'valid' => array(
        'rule' => array('maxLength', 30),
        'message' => 'Please enter atlest 3 characters.'
      )
    ),       
    'city' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your city.'
      ),
      'valid' => array(
        'rule' => array('maxLength', 30),
        'message' => 'Please enter atlest 3 characters.'
      )
    ),          
    'state' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your state.'
      ),
      'valid' => array(
        'rule' => array('maxLength', 30),
        'message' => 'Please enter atlest 3 characters.'
      )
    ),              
    'zip' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your zip.'
      ),
      'valid' => array(
        'rule' => array('postal', null, 'us'),
        'message' => 'Please a valid postal code.'
      )
    ),     
    'phone' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your phone number.'
      ),
      'valid' => array(
        'rule' => array('phone', null, 'us'),
        'message' => 'Please enter a valid phone number.'
      )         
    ),
    'ssn_unsafe' => array(
      'required' => array(
        'rule' => VALID_NOT_EMPTY,
        'message' => 'Please enter your SSN.'
      ),
      'valid' => array(
        'rule' => array('ssn', null, 'us'),
        'message' => 'Please enter a valid ssn number in the format 111-11-1111.'
      )   
    )
  );
}
?>