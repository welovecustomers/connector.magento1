<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-14
 * Time: 16:55
 */

require_once 'WeLoveCustomers/Connector/DTO/ReferralInfosApiResponse.php';
require_once 'WeLoveCustomers/Connector/Endpoint/BaseEndpoint.php';

class CartRuleEndpoint extends BaseEndpoint
{

    public function findCartRuleByCode($couponCode) {

        $params = array(
            'inputCode' => $couponCode
        );

        $jsonResponse = $this->doAuthRequest('checkOfferCode', $params);

        if($jsonResponse) {
            $offer = new OfferApiResponse(json_decode($jsonResponse, 1));

            return $offer->res ? $offer : null;

        }

        return null;

    }


    /**
     * @param string $code
     * @param Offer $offer
     * @return array
     */
    public function convertOfferToPostData($code, $offer)
    {

        $websitesId = array_map(function($website) {
            return $website->getId();
        }, Mage::app()->getWebsites());

        $groupsId = array_map(function($group) {
            return $group['value'];
        }, Mage::getModel('customer/group')->getCollection()->toOptionArray());

        return array (
            'rule_id' => null,
            'name' => $offer->title,
            'description' => Offer::COUPON_PREFIX." ".strip_tags($offer->description),
            'from_date' => $offer->dateFrom,
            'to_date' => $offer->dateTo,
            'uses_per_customer' => 1,
            'is_active' => '1',
            'stop_rules_processing' => '0',
            'is_advanced' => '1',
            'sort_order' => '0',
            'simple_action' => $offer->offerValueType == Offer::TYPE_PERCENT ? Mage_SalesRule_Model_Rule::BY_PERCENT_ACTION: Mage_SalesRule_Model_Rule::BY_FIXED_ACTION,
            'discount_amount' => $offer->offerValueType == Offer::TYPE_FREE_SHIPPING ? 0 : $offer->offerValue,
            'discount_qty' => 0,
            'discount_step' => '0',
            'apply_to_shipping' => '0',
            'times_used' => '0',
            'is_rss' => '0',
            'coupon_type' => Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC,
            'use_auto_generation' => '0',
            'uses_per_coupon' => 1,
            'simple_free_shipping' => $offer->offerValueType == Offer::TYPE_FREE_SHIPPING ? 1 : 0,
            'code' => $code,
            'website_ids' => $websitesId,
            'customer_group_ids' => $groupsId,
            'coupon_code' => $code,
            'store_labels' =>
                array (
                    0 => '',
                )
        );
    }

}