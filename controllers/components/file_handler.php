<?php

/***************************
* File Handler Component
*************************** 
* Author: Chris Partridge
* License: MIT
* Release Date: 09/03/2006
* Version: 1.0
* Revision: 2
*
* Description: This class is designed to be extended for
* a specific purpouse. However, it can act 
* as a file repository component "out of the box".
***************************/ 
class FileHandlerComponent extends Object {
	
	var $handlerType = 'array'; 
	var $dbModel = null;
	var $dbFields = array( 
		'dir' => 'dir', 			// The directory the file was uploaded to
		'file_name' => 'file_name', // The file name it was saved with
		'mime_type' => 'mime_type', // The mime type of the file
		'file_size' => 'file_size', // The size of the file
	);
	var $allowedMime = array( 
		'image/jpeg', // images
		'image/pjpeg', 
		'image/png', 
		'image/gif', 
	);
	var $maxFileSize = 3584;
	var $errorMessage = null;
	var $isError = false;
	var $lastUploadData;
	
	function __construct() 	{
		if(!in_array($this->handlerType, array('db','array'))) {
			$this->setError('The specified handler type is invalid.');
		}
		if($this->handlerType == 'db') {
			if(!class_exists($this->dbModel)) {
				$this->setError('The specified database model does not exist.');
			}
			$this->{$this->dbModel} = &new $this->dbModel;
			if(!is_subclass_of($this->{$this->dbModel}, 'AppModel')) {
				unset($this->{$this->dbModel});
				$this->setError('The specified database model is not a cake database model.');
			}
		}
		parent::__construct();
	}
	
	function getLastUploadInfo() {
		if(!is_array($this->lastUploadData)) {
			$this->setError('No upload detected.');
		}
		else {
			return $this->lastUploadData;
		}
	}
	
	function getMime($file) {
		if (!function_exists('mime_content_type')) {
			return system(trim('file -bi ' . escapeshellarg ($file)));
		}
		else {
			return mime_content_type($file);
		}
	}
	
	/* 
	upload
	Passed a form field and dir,
	the class will check and attempt
	to upload the file.
	If uploaded, the details are stored
	and the id is returned.
	*/
	function upload($file, $field, $dir) {
		$_FILES = $file;
		// Check that the two method variables are set
		if(empty($field) || empty($dir)) {
			$this->setError('You must supply a file field name and a directory on the server.');
			return false;
		}
		// Check that the upload file field exists
		if(!isset($_FILES[$field])) {
			$this->setError('No file supplied.');
			return false;
		}
		// Check that the file upload was not errornous
		if($_FILES[$field]['error'] != 0) {
			switch($_FILES[$field]['error']) {
				case 1: $this->setError('The file is too large (server).');
				break;
				case 2: $this->setError('The file is too large (form).');
				break;
				case 3: $this->setError('The file was only partially uploaded.');
				break;
				case 4: $this->setError('No file was uploaded.');
				break;
				case 5: $this->setError('The servers temporary folder is missing.');
				break;
				case 6: $this->setError('Failed to write to the temporary folder.');
				break;
			}
			return false;
		}
		// Check that the supplied dir ends with a DS
		if($dir[(strlen($dir)-1)] != DS) {
			$dir .= DS;
		}
		
		// Check that the given dir is writable
		if(!is_dir($dir) || !is_writable($dir)) {
			$this->setError('The supplied upload directory does not exist or is not writable.');
			return false;
		}
		// Check that the file is of a legal mime type
		if(!in_array($_FILES[$field]['type'], $this->allowedMime)) {
			$this->setError('The file upload is of an illegal mime type.');
			return false;
		}
		// Check that the file is smaller than the maximum filesize.
		if((filesize($_FILES[$field]['tmp_name'])/1024) > $this->maxFileSize) {
			$this->setError('The file is too large (application).');
			return false;
		}
		// Get the mime type for the file
		$mime_type = $_FILES[$field]['type'];
		
		// Update the database is using db
		if($this->handlerType == 'db') {
			// Create database update array
			$file_details = array(
				$this->dbModel => array( 
					$this->dbFields['dir'] => $dir,
					$this->dbFields['file_name'] => basename($_FILES[$field]['name']),
					$this->dbFields['mime_type'] => $file_type,
					$this->dbFields['file_size'] => (filesize($_FILES[$field]['tmp_name'])/1024)
				)
			);
			// Update database, set error on failure 
			if(!$this->{$this->dbModel}->save($file_details)) {
				$this->setError('There was a database error');
				return false;
			}
			// Get the database id
			$file_id = $this->{$this->dbModel}->getLastInsertId();
			$dir = $dir;
		}
		// Generate dir name if using handler type of array
		if($this->handlerType == 'array') {
			$dir = $dir;
		}
		// Move the uploaded file to the new directory
		if(!move_uploaded_file($_FILES[$field]['tmp_name'], $dir . basename($_FILES[$field]['name']))) {
			// Remove db record if using db
			if($this->handlerType == 'db') {
				$this->{$this->dbModel}->del($file_id);
			}
			// Set the error and return false
			$this->setError('The uploaded file could not be moved to the created directory');
			return false;
		}
		// Set the data for the lastUploadData variable
		$this->lastUploadData = array( 
			'dir' => $dir,
			'file_name' => basename($_FILES[$field]['name']),
			'mime_type' => $mime_type,
			'file_size' => (filesize($_FILES[$field]['tmp_name'])/1024)
		);
		return $this->saveImage($this->lastUploadData);
		// Add the id if using db
		if($this->handlerType == 'db') {
			$this->_lastUploadData['id'] = $file_id;
		}
		// Return true
		return true;
	}
	
	function saveImage($data) {
		$path = str_replace(PRODUCT_IMAGES, '', $data['dir']);
		$data[$this->dbModel]['filename'] = $path . $data['file_name'];
		ClassRegistry::init($this->dbModel)->save($data);
		return ClassRegistry::init($this->dbModel)->id;
	}
	
	function setError($error) {
		$this->isError = true;
		$this->errorMessage = $error;
	}
}
?>