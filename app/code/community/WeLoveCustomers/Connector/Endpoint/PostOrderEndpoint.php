<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-14
 * Time: 16:55
 */

require_once 'WeLoveCustomers/Connector/Endpoint/BaseEndpoint.php';

class PostOrderEndpoint extends BaseEndpoint
{

    /**
     * @return ReferralInfosApiResponse
     */
    public function postOrder($params) {
        $jsonResponse = $this->doAuthRequest('addBuyer', $params);

        if($jsonResponse) {
            return json_decode($jsonResponse);
        }

        return null;
    }


}