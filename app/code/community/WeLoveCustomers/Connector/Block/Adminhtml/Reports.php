<?php
/**
 * Created by PhpStorm.
 * User: gabrieljadeau
 * Date: 16/11/2017
 * Time: 07:37
 */

require_once 'WeLoveCustomers/Connector/Endpoint/ReferralInfosEndpoint.php';
require_once 'WeLoveCustomers/Connector/Model/Offer.php';

class WeLoveCustomers_Connector_Block_Adminhtml_Reports extends Mage_Checkout_Block_Onepage_Success {


    /**
     * @var ReferralInfosEndpoint
     */
    private $endpoint;

    /**
     * @var \WeLoveCustomers\Connector\DTO\ReferralInfosApiResponse
     */
    private $referral;

    /**
     * Reports constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param ReferralInfosEndpoint $endpoint
     */
    public function __construct()
    {
        $this->endpoint = new ReferralInfosEndpoint();
        $this->referral = $this->endpoint->findReferralInfos();
    }

    /**
     * @return string
     */
    public function getMasterInfo()
    {
        $offer = $this->referral->infos->masterOffer;
        return $this->getOfferInfo($offer);
    }

    /**
     * @return string
     */
    public function getSlaveInfo() {
        $offer = $this->referral->infos->slaveOffer;
        return $this->getOfferInfo($offer);
    }

    /**
     * @param $offer
     * @return string
     */
    private function getOfferInfo($offer) {
        switch($offer->offerValueType) {
            case Offer::TYPE_AMOUNT:
                return $offer->offerValue."€";
            case Offer::TYPE_PERCENT:
                return $offer->offerValue."%";
            case Offer::TYPE_FREE_SHIPPING:
                return "Free shipping";

        }
        return "<p></p>";
    }

    /**
     * @return string
     */
    public function getNPSScore() {
        $score = $this->referral->scoreNps;
        return $score ? $score : "-";
    }


    /**
     * @return string
     */
    public function getSponsoringResult() {
        $result = "-";

        $nbSlave = $this->referral->availableCodeSlave;
        $nbMaster = $this->referral->availableCodesMaster;

        if($nbSlave) {
            $result = $nbSlave;

            if($nbMaster) {
                $result.= " (".$this->getSponringPercent()."%)";
            }
        }

        return $result;
    }

    /**
     * @return float|null
     */
    private function getSponringPercent() {
        $nbSlave = $this->referral->availableCodeSlave;
        $nbMaster = $this->referral->availableCodesMaster;

        if($nbMaster && $nbSlave) {
            return ($nbMaster / $nbSlave) * 100;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getSponsoringClass() {
        $percent = $this->getSponringPercent();

        if($percent) {
            if($percent > 30) {
                return "success";
            }

            if($percent > 10) {
                return "warning";
            }

            return "danger";
        }

        return "info";
    }

    /**
     * @return string
     */
    public function getNPSClass() {
        $score = $this->referral->scoreNps;

        if($score) {
            if($score > 20) {
                return "success";
            }

            if($score > -20) {
                return "warning";
            }

            return "danger";
        }

        return "info";
    }

    /**
     * @return string
     */
    public function getLinkUrl(){
        return Mage::helper("adminhtml")->getUrl('*/wlc_connector/createAccount');
    }

}
