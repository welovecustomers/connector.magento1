<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-10
 * Time: 14:36
 */


require_once 'WeLoveCustomers/Connector/Model/Contact.php';
require_once 'WeLoveCustomers/Connector/Model/Offer.php';

class OfferApiResponse
{

    public $res;
    public $burn;
    public $message;

    /** @var Offer */
    public $pOffer;

    /** @var Offer */
    public $fOffer;

    /** @var Contact  */
    public $pContact;

    /** @var Contact  */
    public $fContact;

    public $offerType;

    /**
     * OfferApiResponse constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->res= $data["res"];
        $this->burn= $data["burn"];
        $this->message= $data["message"];
        $this->pOffer= new Offer($data["pOffer"]);
        $this->fOffer= new Offer($data["fOffer"]);
        $this->pContact= new Contact($data["pContact"]);
        $this->fContact= new Contact($data["fContact"]);
        $this->offerType= $data["offerType"];
    }
}