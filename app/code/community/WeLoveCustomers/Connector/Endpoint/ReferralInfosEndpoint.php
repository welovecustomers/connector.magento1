<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-14
 * Time: 16:55
 */

require_once 'WeLoveCustomers/Connector/DTO/ReferralInfosApiResponse.php';
require_once 'WeLoveCustomers/Connector/Endpoint/BaseEndpoint.php';

class ReferralInfosEndpoint extends BaseEndpoint
{

    /**
     * @return ReferralInfosApiResponse
     */
    public function findReferralInfos() {

        $response = new ReferralInfosApiResponse();

        $response->infos = $this->findReferral();

        if($response->infos) {
            $response->availableCodesMaster = $this->countAvailableCodesForOffer($response->infos->masterOfferId);
            $response->availableCodeSlave = $this->countAvailableCodesForOffer($response->infos->slaveOfferId);
        }

        $response->scoreNps = $this->findNpsScore();
        $response->codes = $this->findCodes();
        return $response;
    }

    public function findCodes() {
        $jsonResponse = $this->doAuthRequest('getListCodes');

        if($jsonResponse) {
            return json_decode($jsonResponse);
        }
    }

    /**
     * @return mixed|null
     */
    private function findReferral() {
        $jsonResponse = $this->doAuthRequest('referralInfos');

        if($jsonResponse) {
            return json_decode($jsonResponse);
        }

        return null;
    }

    private function countAvailableCodesForOffer($offerId) {
        $jsonResponse = $this->doAuthRequest('countAvailableCodesForOffer', array(
            'offerId' => $offerId
        ));

        if($jsonResponse) {
            $result =json_decode($jsonResponse);
            return $result->nbCodes;
        }

        return null;
    }

    /**
     * @return integer|string
     */
    private  function findNpsScore() {
        $result = $this->doAuthRequest('getNPS');

        if($result) {
            $scoreNPS = json_decode($result);
            return $scoreNPS->score;
        }

        return $result;
    }



}