<?php

namespace Dashboard\User\Model;

class User
{
    public $id;
    public $email;
    public $pid;
    public $name;
    public $role;
    public $dt;
    public $password;
    public $user;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : 0;
        $this->email = (!empty($data['email'])) ? $data['email'] : '';
        $this->pid = (!empty($data['pid'])) ? $data['pid'] : 1;
        $this->name = (!empty($data['name'])) ? $data['name'] : '';
        $this->role = (!empty($data['role'])) ? $data['role'] : '';
        $this->dt = (!empty($data['dt'])) ? $data['dt'] : date('Y-m-d H:i:s');
        $this->password = (!empty($data['password'])) ? $data['password'] : '';
        $this->user = (!empty($data['user'])) ? $data['user'] : '';
    }
}