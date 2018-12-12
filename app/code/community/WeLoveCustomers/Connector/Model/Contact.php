<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 2018-12-10
 * Time: 15:01
 */


class Contact
{

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->email = $data["email"];
        $this->mobile = $data["mobile"];
        $this->phone = $data["phone"];
    }

    public $id;
    public $name;
    public $email;
    public $mobile;
    public $phone;
}