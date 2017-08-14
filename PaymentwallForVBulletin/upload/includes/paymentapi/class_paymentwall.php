<?php

if (!isset($GLOBALS['vbulletin']->db))
{
	exit;
}

/**
* Class that provides payment verification and form generation functions
*
* @package	vBulletin
*/
class vB_PaidSubscriptionMethod_paymentwall extends vB_PaidSubscriptionMethod
{
	/**
	* The variable indicating if this payment provider supports recurring transactions
	*
	* @var	bool
	*/
	var $supports_recurring = true;

	/**
	* Display feedback via payment_gateway.php when the callback is made
	*
	* @var	bool
	*/
	var $display_feedback = false;
	
	/**
	* The variable indicating if this payment provider supports recurring transactions
	*
	* @var	bool
	*/
	var $ipwhitelistcheck = false;

	/**
	* Perform verification of the payment, this is called from the payment gateway
	*
	* @return	bool	Whether the payment is valid
	*/
	function verify_payment() {
		
		$userId = isset( $_GET [ 'uid' ] ) ? $_GET [ 'uid' ] : null;
		$goodsId = isset( $_GET [ 'goodsid' ] ) ? $_GET [ 'goodsid' ] : null;
		$length = isset( $_GET [ 'slength' ] ) ? $_GET [ 'slength' ] : null;
		$period = isset( $_GET [ 'speriod' ] ) ? $_GET [ 'speriod' ] : null;
		$type = isset( $_GET [ 'type' ] ) ? $_GET [ 'type' ] : null;
		$reason = isset( $_GET [ 'reason' ] ) ? $_GET [ 'reason' ] : null;
		$refId = isset( $_GET [ 'ref' ] ) ? $_GET [ 'ref' ] : null;
		$sign_version = isset( $_GET ['sign_version' ] ) ? $_GET [ 'sign_version' ] : null;
		$amount = isset( $_GET ['amount' ] ) ? $_GET [ 'amount' ] : null;
		$currency = isset( $_GET ['currencyCode' ] ) ? $_GET [ 'currencyCode' ] : null;
		$signature = isset( $_GET ['sig' ] ) ? $_GET [ 'sig' ] : null;

		/**  
		 *  The IP addresses below are Paymentwall's
		 *  servers. Make sure your pingback script
		 *  accepts requests from these addresses ONLY.
		 *
		 */
		$ipsWhitelist = array(
			'174.36.92.186',
			'174.36.96.66',
			'174.36.92.187',
			'174.36.92.192',
			'174.37.14.28'
		);
		
		/**  
		 *  If there are any errors encountered, the script will list them
		 *  in an array.
		 */
		$errors = array ();
		
		if (!empty($userId) && !empty($goodsId) && isset($type) && !empty($refId) && !empty($signature)) {

			$settings =& $this->settings;

			// $checkhash = 
			$signatureParams = array();
			foreach ($_GET as $param => $value) {    
				$signatureParams[$param] = $value;
			}
			unset($signatureParams['sig']);
			
			/**
			 *  check if signature matches    
			 */
			$secret = $settings ['secret_key'];
			$signatureCalculated = $this->calculateSignature ( $signatureParams, $secret );
			
			/**
			 *  Run the security check -- if the request's origin is one
			 *  of Paymentwall's servers
			 */
			if ( ! $this->ipwhitelistcheck || in_array($_SERVER['REMOTE_ADDR'], $ipsWhitelist)) {
				
				if ($signature == $signatureCalculated) {

					
					$this->paymentinfo = $this->registry->db->query_first("
						SELECT paymentinfo.*, user.username
						FROM " . TABLE_PREFIX . "paymentinfo AS paymentinfo
						INNER JOIN " . TABLE_PREFIX . "user AS user USING (userid)
						WHERE hash = '" . $this->registry->db->escape_string( $goodsId ) . "'
					");
					
					// lets check the values
					if (!empty($this->paymentinfo)) {
						$this->paymentinfo['currency'] = $currency;
						$this->paymentinfo['amount'] = $amount;
						$this->transaction_id = $refId;
						
						if ($type == 2) {
							$this -> type = 2;
						} else {
							$this -> type = 1;
						}

					} else {
						$this->error = 'Invalid or duplicate transaction.';
					}
					
					$status_code = '200 OK';
					header('HTTP/1.1 ' . $status_code);
					echo 'OK';
					return true;
					
				} else {
					$this->error = 'Invalid Paymentwall signature.';
				}
				
			} else {
				$this->error = 'Request\'s IP address unknown';
			}
			
		} else {
			$this->error = 'Missing Paymentwall parameters';
		}
		
		$status_code = '503 Service Unavailable';
		header('HTTP/1.1 ' . $status_code);
		echo $this->error;
		return false;
	}

	/**
	* Test that required settings are available, and if we can communicate with the server (if required)
	*
	* @return	bool	If the vBulletin has all the information required to accept payments
	*/
	function test() {
		return (!empty($this->settings['app_key']) AND !empty($this->settings['secret_key']));
	}

	/**
	* Generates HTML for the subscription form page
	*
	* @param	string		Hash used to indicate the transaction within vBulletin
	* @param	string		The cost of this payment
	* @param	string		The currency of this payment
	* @param	array		Information regarding the subscription that is being purchased
	* @param	array		Information about the user who is purchasing this subscription
	* @param	array		Array containing specific data about the cost and time for the specific subscription period
	*
	* @return	array		Compiled form information
	*/
	function generate_form_html($hash, $cost, $currency, $subinfo, $userinfo, $timeinfo) {
		global $vbphrase, $vbulletin, $stylevar, $show;

		$item = $hash;

		$form['action'] = 'https://wallapi.com/api/subscription/';
		$form['method'] = 'get';
		
		// load settings into array so the template system can access them
		$settings =& $this->settings;
		
		$recurring = $timeinfo['recurring'];
		
		$period_type = $timeinfo [ 'units' ];
		
		switch ( $period_type ) {
			
			case 'D':
				$period_type = 'day';
				break;
			case 'M':
				$period_type = 'month';
				break;
			case 'W':
				$period_type = 'week';
				break;
			case 'Y':
				$period_type = 'year';
				break;
		}
		
		$params = array(
			'key' => $settings ['app_key'] ,
			'uid' => $userinfo ['email'] ,
			'widget' => $settings [ 'widget_code' ] ,
			'sign_version' => 2,
			'amount' => $cost,
			'currencyCode' => $currency,
			'ag_name' => $subinfo [ 'title' ],
			'ag_external_id' => $item,
			'ag_type' => 'subscription',
			'ag_period_length' => $timeinfo [ 'length' ],
			'ag_period_type' => $period_type,
			'ag_recurring' => $recurring,
		);
		
		$signature = $this -> calculateSignature ( $params, $settings [ 'secret_key' ] );
		$params['sign'] = $signature;
		
		$form['hiddenfields'] = '';
		
		foreach ($params as $k => $v) {
			$form['hiddenfields'] .= '<input type="hidden" name="' . $k . '" value="' . $v . '">';
		}
		
		return $form;
	}
	
	public function calculateSignature ($params, $secret) {
        // work with sorted data
        ksort($params);
        // generate the base string
        $baseString = '';
        foreach($params as $key => $value) {
            $baseString .= $key . '=' . $value;
        }
        $baseString .= $secret;
        return md5($baseString);
    }
}

?>
