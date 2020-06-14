<?php
 
require_once 'WeLoveCustomers/Connector/DTO/OfferApiResponse.php';
require_once 'WeLoveCustomers/Connector/Endpoint/PostOrderEndpoint.php';

class WeLoveCustomers_Connector_Model_Observer
{

	private function log($message){
		Mage::log($message, null, 'wlc.log');
	}

	/**
	* Event Hook: hookToOrderStatusChange
	* @param $observer Varien_Event_Observer
	*/
	 
	public function hookToOrderStatusChange($observer)
	{
        $order = $observer->getEvent()->getOrder();
		
		$this->log('Status:' . var_export($order->getStatus() ,true));

        if ($order->getStatus() == Mage_Sales_Model_Order::STATE_PROCESSING){

            $apikey         = Mage::helper('wlc_connector')->getApiKey();
            $apiglue        = Mage::helper('wlc_connector')->getApiGlue();
            $orderid        = $order->getId();
            $customername   = $order->getBillingAddress()->getName();
            $firstname      = $order->getBillingAddress()->getFirstname();
            $lastname       = $order->getBillingAddress()->getLastname();
            $customer_name  = $firstname.' '.$lastname ;
            $email          = $order->getCustomerEmail();
            $createdate     = date(strtotime($order->getCreatedAt()));
            $currency       = $order->getOrderCurrencyCode();
            $priceHelper    = Mage::app()->getStore()->getCurrentCurrencyCode();
            $subtotal       = number_format($order->subtotal + $order->discount_amount, 2); 
            $coupencode     = $order->getCouponCode();
            $telephone      = $order->getBillingAddress()->getTelephone();
            $params         = array($apiglue,$customername,$email,$telephone,$subtotal,$coupencode,$createdate,$orderid);
            $hash           = md5(implode("", $params));
            $lang           = Mage::app()->getLocale()->getLocaleCode();


			$this->log('Data:' . var_export($order->getData() ,true));


            $orderParams = array();
            $orderParams['customer-key']     = $apikey;
            $orderParams['data-name']        = $customername;
            $orderParams['data-firstname']   = $firstname;
            $orderParams['data-lastname']    = $lastname;
            $orderParams['data-email']       = $email; 
            $orderParams['data-mobile']      = $telephone;
            $orderParams['data-amount']      = $subtotal;
            $orderParams['data-coupons']     = $coupencode;
            $orderParams['data-timestamp']   = $createdate;
            $orderParams['data-purchase-id'] = $orderid; 
            $orderParams['data-hash']        = $hash;
            $orderParams['data-lang']        = $lang;
            $orderParams['data-currency']    = $currency;


			$this->log('Status:' . var_export($orderParams ,true));

            $endpoint = new PostOrderEndpoint();
            $endpoint->postOrder($orderParams);

        }


		$this->log('****Out****');
	}
}