<?php

namespace Dashboard\User\Model;

class Users
{
    public $id;
    public $email;
    public $password;
    public $isactive;
    public $dt;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : '';
        $this->password = (!empty($data['password'])) ? $data['password'] : 0;
        $this->isactive = (!empty($data['isactive'])) ? $data['isactive'] : '';
        $this->dt = (!empty($data['dt'])) ? $data['dt'] : date('Y-m-d H:i:s');
    }
}