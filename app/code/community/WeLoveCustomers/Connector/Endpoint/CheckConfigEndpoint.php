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

        $jsonResponse = $this->doAuthRequest('checkInstall');
        
        if($jsonResponse) {
            $response = json_decode($jsonResponse);

            return $response->res;
        }

        return false;

    }

}