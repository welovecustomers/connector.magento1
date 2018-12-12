<?php

require_once 'Mage/Checkout/controllers/CartController.php';
require_once 'Mage/SalesRule/Model/Rule.php';
require_once 'WeLoveCustomers/Connector/DTO/OfferApiResponse.php';

class WeLoveCustomers_Connector_Frontendhtml_CartController extends Mage_Checkout_CartController
{

    /**
     * @return Mage_Core_Helper_Abstract|WeLoveCustomers_Connector_Helper_Data
     */
    public function getWclConnectionHelper(){
        return Mage::helper('wlc_connector');
    }

    /**
     * Initialize coupon
     */
    public function couponPostAction()
    {
        /**
         * No reason continue with empty shopping cart
         */
        if (!$this->_getCart()->getQuote()->getItemsCount()) {
            $this->_goBack();
            return;
        }

        $couponCode = (string) $this->getRequest()->getParam('coupon_code');
        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $oldCouponCode = $this->_getQuote()->getCouponCode();

        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            $this->_goBack();
            return;
        }

        try {
            $codeLength = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            if ($isCodeLengthValid && $couponCode != $this->_getQuote()->getCouponCode()) {

                // WELOVECUSTOMERS - Le code n'existe pas, on appelle l'api
                $offerResponse = $this->findCartRuleByCode($couponCode);

                if($offerResponse){
                    $offer = $offerResponse->offerType == "F" ? $offerResponse->fOffer : $offerResponse->pOffer;

                    $rule = Mage::getModel('salesrule/rule');

                    // si l'offre est disponible, on la converti en data Magento
                    $postData = $this->convertOfferToPostData($couponCode, $offer);

                    $rule
                        ->loadPost($postData)
                        ->save();
                }
            }

            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')
                ->collectTotals()
                ->save();

            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == $this->_getQuote()->getCouponCode()) {
                    $this->_getSession()->addSuccess(
                        $this->__('Promo code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                } else {

                    $this->_getSession()->addError(
                        $this->__('Promo code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                }
            } else {
                $this->_getSession()->addSuccess($this->__('Promo code was cancelled.'));
            }

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Cannot apply the promo code.'));
            Mage::logException($e);
        }

        $this->_goBack();
    }

    private function findCartRuleByCode($couponCode) {

        $params = array(
            'customerKey' => $this->getWclConnectionHelper()->getApiGlue(),
            'glue' => $this->getWclConnectionHelper()->getApiKey(),
            'inputCode' => $couponCode
        );

        $jsonResponse = $this->getWclConnectionHelper()->callApi('checkOfferCode', $params);

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
    private function convertOfferToPostData($code, $offer)
    {
        return array (
            'rule_id' => null,
            'name' => $offer->title,
            'description' => strip_tags($offer->description),
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
            'website_ids' =>
                array (
                    0 => '1',
                ),
            'customer_group_ids' =>
                array (
                    0 => '0',
                    1 => '1',
                    2 => '2',
                    3 => '3',
                ),
            'coupon_code' => $code,
            'store_labels' =>
                array (
                    0 => '',
                )
        );
    }
}