<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-14
 * Time: 16:55
 */

require_once 'WeLoveCustomers/Connector/Endpoint/BaseEndpoint.php';

class ReferralInfosEndpoint extends BaseEndpoint
{

    /**
     * @return ReferralInfosApiResponse
     */
    public function findReferralInfos() {

        $jsonResponse = $this->doAuthRequest('referralInfos');

        if($jsonResponse) {
            return json_decode($jsonResponse);
        }

        return null;
    }


}