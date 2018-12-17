<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-14
 * Time: 16:55
 */

require_once 'WeLoveCustomers/Connector/Endpoint/BaseEndpoint.php';

class CheckConfigEndpoint extends BaseEndpoint
{

    public function checkConfig() {

        $couponCode = "config_check";

        $params = array(
            'inputCode' => $couponCode
        );

        $jsonResponse = $this->doAuthRequest('checkOfferCode', $params);

        if($jsonResponse) {
            return true;
        }

        return null;

    }

}