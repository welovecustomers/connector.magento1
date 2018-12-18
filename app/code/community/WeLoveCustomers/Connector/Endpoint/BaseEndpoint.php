<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-13
 * Time: 15:32
 */

class BaseEndpoint
{

    public function getWclConnectionHelper(){
        return Mage::helper('wlc_connector');
    }

    protected function doAuthRequest($serviceName, $params = array()) {
        $authParams = array_merge(array(
            'customerKey' => $this->getWclConnectionHelper()->getApiKey(),
            'apiGlue' => $this->getWclConnectionHelper()->getApiGlue(),
        ), $params);

        return $this->getWclConnectionHelper()->callApi($serviceName, $authParams);
    }

}