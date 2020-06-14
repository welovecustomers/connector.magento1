<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 2020-06-12
 * Time: 15:01
 */


class Order
{

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->amount = $data["amount"];
        $this->coupon = $data["coupon"];
        $this->email = $data["email"];
        $this->mobile = $data["mobile"];
    }

    public $id;
    public $name;
    public $amount;
    public $coupon;
    public $email;
    public $mobile;
}