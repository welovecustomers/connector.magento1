<?php

require_once 'Mage/Checkout/controllers/CartController.php';
require_once 'Mage/SalesRule/Model/Rule.php';
require_once 'WeLoveCustomers/Connector/Model/Offer.php';
require_once 'WeLoveCustomers/Connector/Endpoint/CartRuleEndpoint.php';

class WeLoveCustomers_Connector_Extra_CouponController extends Mage_Checkout_CartController
{

    /**
     * @var CartRuleEndpoint
     */
    private $endpoint;

    /**
     * @return CartRuleEndpoint
     */
    public function getEndpoint(){
        if(!$this->endpoint) {
            $this->endpoint = new CartRuleEndpoint();
        }

        return $this->endpoint;
    }


    private function log($message){
        Mage::log($message, null, 'wlc.log');
    }

    public function getWclConnectionHelper(){
        return Mage::helper('wlc_connector');
    }


    public function createAction() {
        $this->log(__METHOD__);
        $status = "ko";


        $data = $_POST;
        
        if ($_POST['customerKey'] == $this->getWclConnectionHelper()->getApiKey()
                && $_POST['apiGlue'] == $this->getWclConnectionHelper()->getApiGlue()){

            // getting discount code to create
            $couponCode = $_POST['inputCode'];

            $this->log('Creating coupon code: ' . var_export($couponCode ,true));

            // WELOVECUSTOMERS - Le code n'existe pas, on appelle l'api
            $offerResponse = $this->getEndpoint()->findCartRuleByCode($couponCode);
            
            if($offerResponse){
                
                $offer = $offerResponse->offerType == "F" ? $offerResponse->fOffer : $offerResponse->pOffer;

                $rule = Mage::getModel('salesrule/rule');

                // si l'offre est disponible, on la converti en data Magento
                $postData = $this->getEndpoint()->convertOfferToPostData($couponCode, $offer);

                $rule
                    ->loadPost($postData)
                    ->save();

                $status = "ok";
            }
        }

        header('Content-Type: application/json');

        echo json_encode(array(
            'code' => 200,
            "status" => $status,
        ));

        die;

    }
}