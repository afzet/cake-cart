<?php
/* SVN FILE: $Id: subscribers_controller.php 66 2008-08-08 02:31:37Z jonathan $ */
/**
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * @modifiedby 		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 */
class SubscribersController extends AppController 
{
	var $name = 'Subscribers';
	var $helpers = array('Html', 'Form', 'Session' );
	var $components = array('Auth');
	var $paginate = array('limit' => 15);
	var $uploaddir = 'C:\xampplite\htdocs\sites\admin\app\tmp\uploads';
        
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function index() {
		$this->set('data', $this->paginate());
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function bulk() { 
		
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function add() {
		if (!empty($this->data)) 
		{
			$this->Subscriber->create();
			if ($this->Subscriber->save($this->data))
			{
				$this->Session->setFlash('The Subscriber has been saved!');
				$this->redirect(array('action'=>'index'), null, true);
		} else 
			{
				$this->Session->setFlash('The Subscriber could not be saved. Please, try again!');
			}
		}
	}
	    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function bulk_upload() {
		if (!empty($this->data)) {
			$csv = $this->data['Newsletter']['csv_upload']['tmp_name'];
			
			$handle = fopen ($csv, "r");
			$contents = fread($handle, filesize($csv));
			fclose ($handle); 			
			$lines = explode("\n",$contents); 
			$i = 1;
			foreach ($lines as $line) {
				if(!empty($line)) {
					list($name,$email) = explode(",",$line); 
					$data['Subscriber']['name']  = $name;
					$data['Subscriber']['email'] = $email;
					$this->Subscriber->create();
					if($this->Subscriber->save($data)) {
						$i++;	
					}
				}
			}
			$this->Session->setFlash('Successfully added '.$i.' Subscribers!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function edit($id = null)  {
		if (!$id && empty($this->data))  {
			$this->Session->setFlash('Invalid Subscriber!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data))  {
			if ($this->Subscriber->save($this->data)) {
				$this->Session->setFlash('The Subscriber has been edited!');
				$this->redirect(array('action'=>'index'), null, true);
			}
			else {
				$this->Session->setFlash('The Subscriber could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Subscriber->read(null, $id);
		}
	}
    
	/**
	 * undocumented function
	 *
	 * @return void
	 * @access public
	 */
	public function delete($id = null)  {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Subscriber!');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Subscriber->del($id)) {
			$this->Session->setFlash('Subscriber #'.$id.' deleted!');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
	
}
?>