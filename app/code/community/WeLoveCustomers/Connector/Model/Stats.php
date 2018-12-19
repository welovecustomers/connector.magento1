<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-10
 * Time: 14:40
 */

class Stats
{

    /**
     * Offer constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->member = $data["member"];
        $this->father = $data["father"];
        $this->contact = $data["contact"];
        $this->slave = $data["slave"];
        $this->pending_validation = $data["pending_validation"];
        $this->npsScore = $data["nps"]["score"];
    }

    public $member;
    public $father;
    public $contact;
    public $slave;
    public $pending_validation;
    public $npsScore;
}