<?php

/**
* Google Checkout Component
* @author WalkerHamilton
* @license MIT
* @version 0.1
*/

/* No Shipping, Ship From, or Taxes have been implemented */

class GoogleCheckoutComponent extends Object
{
	var $components = array('Session');

	var $_merchantID;
	var $_merchantKey;
	//1 == log to errorlog only, 2 == log to browser only, 3 == log to browser & error log
	var $_loggingLevel = 1;
	var $_curreny;
	var $_testing;
	var $_approotdir = APP_DIR;
	var $_items = array();
	var $_gserve;
	var $_continueShopping;

	/*
	 * 
	 */
	function startup(&$controller)
	{
		App::import('Vendor', 'gcheckout/googlecart');
		App::import('Vendor', 'gcheckout/googleitem');
		App::import('Vendor', 'gcheckout/googleshipping');
		App::import('Vendor', 'gcheckout/googletax');
		App::import('Vendor', 'gcheckout/googlemerchantcalculations');
		App::import('Vendor', 'gcheckout/googlerequest');
		App::import('Vendor', 'gcheckout/googleresponse');
		App::import('Vendor', 'gcheckout/googleresult');
		App::import('Vendor', 'gcheckout/googlelog');
		
		$this->_merchantID = (isset($controller->merchantID)) ? $controller->merchantID : '';
		$this->_merchantKey = (isset($controller->merchantKey)) ? $controller->merchantKey : '';
		$this->_currency = (isset($controller->currency)) ? $controller->currency : 'USD';
		$this->_continueShopping = (isset($controller->continueShopping)) ? $controller->continueShopping : 'http://example.org';
		$this->_checkoutTesting = ($controller->checkoutTesting) ? true : false;

		$this->_restoreFromSessionArr();

		$this->_gserve = ($this->_checkoutTesting) ? 'sandbox' : 'Production';
	}


	/*
	 * 
	 */
	function setItem($title, $desc, $count, $price, $merchantItemID=null, $data=null) {
		if($data)
		{
			$this->_items[] = array('title'=>$title, 'desc'=>$desc, 'count'=>$count, 'price'=>$price, 'data'=>$data);
		} else {
			$this->_items[] = array('title'=>$title, 'desc'=>$desc, 'count'=>$count, 'price'=>$price);
		}
		return true;
	}

	/*
	 * 
	 */
	function dumpToSessionArr() {
		if(!empty($this->_items))
		{
			$sessR = array($this->_items);
			return $this->Session->write('Google', $sessR);
		}
	}

	/*
	 * 
	 */
	function _restoreFromSessionArr() {
		$sessR = $this->Session->read('Google');
		
		if($sessR==false) {
			return false;
		} else {
			$this->_items = $sessR;
			return true;
		}
	}

	/*
	 * 
	 */
	function getCartItems() {
		return $this->_items;
	}

	/*
	 * 
	 */
	function getCartPreTaxTotal() {
		$total = 0;
		
		foreach($this->_items as $item)
		{
			$total += $item['price'];
		}
		
		return $total;
	}
	
	/*
	 * 
	 */
	function generateCartButton($data=null, $theitems=array()) {
		$cart = new GoogleCart($this->_merchantID, $this->_merchantKey, $this->_gserve, $this->_currency);
		
		$counter = 1;
		if(!empty($theitems)) {
			$thisitems = $theitems;
		} else {
			$thisitems = $this->_items;
		}
		foreach($thisitems as $item)
		{
			$varvar = 'item_'.$counter;
			${$varvar} = new GoogleItem($item['name'], // Item name
									$item['description'], // Item description
									$item['count'], // Quantity
									$item['price'] // Unit price
									);
			if(isset($item['data']))
			{
				${$varvar}->SetMerchantPrivateItemData(new MerchantPrivateItemData($item['data']));
			}
			if(isset($item['id'])) { ${$varvar}->SetMerchantItemId($item['id']); }
			$cart->AddItem(${$varvar});
		}
		// Set the acct. name, etc.
		if($data)
		$cart->SetMerchantPrivateData(new MerchantPrivateData($data));
		
		// Specify "Return to xyz" link
		$cart->SetContinueShoppingUrl($this->_continueShopping);
		
		// Display Google Checkout button
		return $cart->CheckoutButtonCode("SMALL");
	}
	
	/*
	 *	Destruct only automatically saves the cart to the UserÕs session in php5
	 */
	function __destruct() {
		$this->dumpToSessionArr();
	}

	/*
	 * 
	 */
	function ProcessXmlData($xml_data) {
	    $dom_response_obj = domxml_open_mem($xml_data);
	    $dom_data_root = $dom_response_obj->document_element();
	    $message_recognizer = $dom_data_root->tagname();
	    /*
		//requests, errors and problem solving
	     *     <request-received>
	     *     <error>
	     *     <diagnosis>
		//Go to google and pay for it.
	     *     <checkout-redirect> //a redirect
		//Notifications....
	     *     <merchant-calculation-callback>
	     *     <new-order-notification>
	     *     <order-state-change-notification>
	     *     <charge-amount-notification>
	     *     <chargeback-amount-notification>
	     *     <refund-amount-notification>
	     *     <risk-information-notification>
	     */
		switch ($message_recognizer) {
			// <checkout-redirect> received //handled in a predictable manner, so I can integrate this one...
			case "checkout-redirect":
				$this->_ProcessCheckoutRedirect($dom_response_obj);
				break;
			case "request-received":
			case "error":
			case "diagnosis":
			case "merchant-calculation-callback":
				return array($message_recognizer, $dom_response_obj);
				break;
			case "new-order-notification":
			case "order-state-change-notification":
			case "charge-amount-notification":
			case "chargeback-amount-notification":
			case "refund-amount-notification":
			case "risk-information-notification":
				return array($message_recognizer, $dom_response_obj);
				break;
	        default:
				$this->log("GCheckoutComponent: Response '$message_recognizer' unknown.");
				break;
	    }
	}

	function _ProcessCheckoutRedirect($dom_response_obj) {
	    // Identify the URL to which the customer should be redirected
	    $dom_data_root = $dom_response_obj->document_element();
	    $redirect_url_list = $dom_data_root->get_elements_by_tagname("redirect-url");
	    $redirect_url = $redirect_url_list[0]->get_content();
	    // Redirect the customer to the URL
		$this->controller->redirect($redirect_url);
		exit();
	}
	
	function _SendNotificationAcknowledgment() {
		$acknowledgement = "<notification-acknowledgment xmlns=\"".$GLOBALS["schema_url"] . "\"/>";
		return $acknowledgment;
		
		// Log <notification-acknowledgment> was at least called from cake controller
		LogMessage($GLOBALS["logfile"], $acknowledgment);
	}
	
	/**
	 * The CreateArchiveOrder function is a wrapper function that calls the
	 * ChangeOrderState function. The ChangeOrderState function, in turn,
	 * creates an <archive-order> command for the specified order, which is
	 * identified by its Google Checkout order number ($google_order_number). The 
	 * <archive-order> command moves an order from the Inbox in the Google Checkout
	 * Merchant Center to the Archive folder.
	 *
	 * @param   $google_order_number    A number, assigned by Google Checkout, 
	 *                                      that uniquely identifies an order.
	 * @return  <archive-order> XML
	 */
	function CreateArchiveOrder($google_order_number) {
		return ChangeOrderState($google_order_number, "archive");
	}

	/**
	 * The CreateCancelOrder function is a wrapper function that calls the
	 * ChangeOrderState function. The ChangeOrderState function, in turn,
	 * creates a <cancel-order> command for the specified order, which is
	 * identified by its Google Checkout order number ($google_order_number). The 
	 * <cancel-order> command instructs Google Checkout to cancel an order.
	 *
	 * @param   $google_order_number    A number, assigned by Google Checkout, 
	 *                                      that uniquely identifies an order.
	 * @param   $reason                 The reason an order is being canceled
	 * @param   $comment                A comment pertaining to a canceled order
	 * @return  <cancel-order> XML
	 */
	function CreateCancelOrder($google_order_number, $reason, $comment="") {
		return ChangeOrderState($google_order_number, "cancel", $reason, 0,
			$comment);
	}

	/**
	 * The CreateChargeOrder function is a wrapper function that calls the
	 * ChangeOrderState function. The ChangeOrderState function, in turn,
	 * creates a <charge-order> command for the specified order, which is
	 * identified by its Google Checkout order number ($google_order_number). The
	 * <charge-order> command prompts Google Checkout to charge the customer for an 
	 * order and to change the order's financial order state to "CHARGING".
	 *
	 * @param   $google_order_number    A number, assigned by Google Checkout, 
	 *                                      that uniquely identifies an order.
	 * @param   $amount                 The amount that Google Checkout should 
	 *                                      charge the customer
	 * @return  <charge-order> XML
	 */
	function CreateChargeOrder($google_order_number, $amount="") {
	    return ChangeOrderState($google_order_number, "charge", 0, $amount);
	}

	/**
	 * The CreateProcessOrder function is a wrapper function that calls the
	 * ChangeOrderState function. The ChangeOrderState function, in turn,
	 * creates a <process-order> command for the specified order, which is
	 * identified by its Google Checkout order number ($google_order_number). The
	 * <process-order> command changes the order's fulfillment order state 
	 * to "PROCESSING".
	 *
	 * @param   $google_order_number    A number, assigned by Google Checkout, 
	 *                                      that uniquely identifies an order.
	 * @return  <process-order> XML
	 */
	function CreateProcessOrder($google_order_number) {
	    return ChangeOrderState($google_order_number, "process");
	}

	/**
	 * The CreateRefundOrder function is a wrapper function that calls the
	 * ChangeOrderState function. The ChangeOrderState function, in turn,
	 * creates a <refund-order> command for the specified order, which is
	 * identified by its Google Checkout order number ($google_order_number). The 
	 * <refund-order> command instructs Google Checkout to issue a refund for an 
	 * order.
	 *
	 * @param   $google_order_number    A number, assigned by Google Checkout, 
	 *                                      that uniquely identifies an order.
	 * @param   $reason                 The reason an order is being refunded
	 * @param   $amount                 The amount that Google Checkout should 
	 *                                      refund to the customer.
	 * @param   $comment                A comment pertaining to a refunded order
	 * @return  <refund-order> XML
	 */
	function CreateRefundOrder($google_order_number, $reason, $amount="", 
	    $comment="") {

	    return ChangeOrderState($google_order_number, "refund", $reason, 
	        $amount, $comment);
	}

	/**
	 * The CreateUnarchiveOrder function is a wrapper function that calls the
	 * ChangeOrderState function. The ChangeOrderState function, in turn,
	 * creates an <unarchive-order> command for the specified order, which is
	 * identified by its Google Checkout order number ($google_order_number). The 
	 * <unarchive-order> command moves an order from the Archive folder in the
	 * Google Checkout Merchant Center to the Inbox.
	 *
	 * @param   $google_order_number    A number, assigned by Google Checkout, 
	 *                                      that uniquely identifies an order.
	 * @return  <unarchive-order> XML
	 */
	function CreateUnarchiveOrder($google_order_number) {
	    return ChangeOrderState($google_order_number, "unarchive");
	}
}