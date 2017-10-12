<?php

namespace Dashboard\User\Model;

class Practice
{
    public $id;
    public $name;
    public $contact;
    public $email;
    public $address;
    public $address1;
    public $city;
    public $state;
    public $zip;
    public $phone;
    public $region;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : '';
        $this->contact = (!empty($data['contact'])) ? $data['contact'] : 0;
        $this->email = (!empty($data['email'])) ? $data['email'] : '';
        $this->address = (!empty($data['address'])) ? $data['address'] : '';
        $this->address1 = (!empty($data['address1'])) ? $data['address1'] : '';
        $this->city = (!empty($data['city'])) ? $data['city'] : '';
        $this->state = (!empty($data['state'])) ? $data['state'] : '';
        $this->zip = (!empty($data['zip'])) ? $data['zip'] : '';
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : '';
        $this->region = (!empty($data['region'])) ? $data['region'] : '';
    }
}