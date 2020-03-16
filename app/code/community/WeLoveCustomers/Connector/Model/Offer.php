<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-10
 * Time: 14:40
 */


class Offer
{
    const TYPE_PERCENT = "percent";
    const TYPE_AMOUNT = "amount";
    const TYPE_FREE_SHIPPING = "freeshipping";

    const COUPON_PREFIX="[WeLoveCustomers]";
    /**
     * Offer constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->id= $data["id"];
        $this->type= $data["type"];
        $this->title= $data["title"];
        $this->description= $data["description"];
        $this->dateFrom= $data["dateFrom"];
        $this->dateTo= $data["dateTo"];
        $this->code = $data["code"];
        $this->minimumAmountToBuy= $data["minimumAmountToBuy"];
        $this->minimumAmountForReward= $data["minimumAmountForReward"];
        $this->maximumDaysDelayForReward= $data["maximumDaysDelayForReward"];
        $this->offerValue= $data["offerValue"];
        $this->offerValueType= $data["offerValueType"];
    }

    static function FromWLC($rule) {
        return substr($rule->getDescription(), 0, strlen(Offer::COUPON_PREFIX)) == self::COUPON_PREFIX;
    }

    public $id;
    public $type;
    public $title;
    public $description;
    public $dateFrom;
    public $dateTo;
    public $code;
    public $minimumAmountForReward;
    public $maximumDaysDelayForReward;
    public $offerValue;
    public $offerValueType;

}