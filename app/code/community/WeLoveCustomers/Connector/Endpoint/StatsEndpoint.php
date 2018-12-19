<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-13
 * Time: 15:31
 */


require_once 'WeLoveCustomers/Connector/Endpoint/BaseEndpoint.php';
require_once 'WeLoveCustomers/Connector/Model/Stats.php';

class StatsEndpoint extends BaseEndpoint
{

    /**
     * @return Stats|null
     */
    public function findStats() {

        $jsonResponse = $this->doAuthRequest('getStats');

        if($jsonResponse) {
            $result = json_decode($jsonResponse, 1);
            if($result['stats']) {
                return new Stats($result['stats']);
            }

        }

        return null;
    }

}