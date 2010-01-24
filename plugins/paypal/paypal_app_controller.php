<?php
class PaypalAppController extends AppController {
  
	function beforeFilter() {
		parent::beforeFilter();
		parent::adminLayout();
	}
}
?>