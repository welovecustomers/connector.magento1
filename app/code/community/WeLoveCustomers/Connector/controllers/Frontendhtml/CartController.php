<?php

require_once 'Mage/Checkout/controllers/CartController.php';
require_once 'Mage/SalesRule/Model/Rule.php';
require_once 'WeLoveCustomers/Connector/DTO/OfferApiResponse.php';
require_once 'WeLoveCustomers/Connector/Endpoint/CartRuleEndpoint.php';

class WeLoveCustomers_Connector_Frontendhtml_CartController extends Mage_Checkout_CartController
{

    /**
     * @var CartRuleEndpoint
     */
    private $endpoint;

    /**
     * @return CartRuleEndpoint
     */
    public function getEndpoint(){
        if(!$this->endpoint) {
            $this->endpoint = new CartRuleEndpoint();
        }

        return $this->endpoint;
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

            $coupon = Mage::getModel('salesrule/coupon')->load($couponCode, 'code');
            $rule = Mage::getModel('salesrule/rule')->load($coupon->getRuleId());

            // si le coupon existe est que le coupon vient de WLC
            if($rule->getId() && Offer::FromWLC($rule)) {

                // on verifie si le coupon est tjrs valide
                $offerResponse = $this->getEndpoint()->findCartRuleByCode($couponCode);

                if(!$offerResponse) {
                    $rule->delete();
                }
            }

            if (!$rule->getId()) {

                // WELOVECUSTOMERS - Le code n'existe pas, on appelle l'api
                $offerResponse = $this->getEndpoint()->findCartRuleByCode($couponCode);
                
                if($offerResponse){
                    $offer = $offerResponse->offerType == "F" ? $offerResponse->fOffer : $offerResponse->pOffer;

                    $rule = Mage::getModel('salesrule/rule');

                    // si l'offre est disponible, on la converti en data Magento
                    $postData = $this->getEndpoint()->convertOfferToPostData($couponCode, $offer);

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
}