<?php
 
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
        'rule' => VALID_NOT_EMPTY, 
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