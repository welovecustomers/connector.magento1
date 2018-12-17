<?php

require_once 'Mage/Checkout/controllers/CartController.php';
require_once 'Mage/SalesRule/Model/Rule.php';
require_once 'WeLoveCustomers/Connector/DTO/OfferApiResponse.php';
require_once 'WeLoveCustomers/Connector/Endpoint/CheckConfigEndpoint.php';

class WeLoveCustomers_Connector_Extra_ConfigController extends Mage_Checkout_CartController
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
            $this->endpoint = new CheckConfigEndpoint();
        }

        return $this->endpoint;
    }

    public function checkAction() {
        $status = "ko";
        $isValidConfig = $this->getEndpoint()->checkConfig();

        if($isValidConfig) {
            $status = "ok";
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'code' => 200,
            "status" => $status
        ));

        die;
    }
}