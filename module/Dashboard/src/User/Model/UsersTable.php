<?php

namespace Dashboard\User\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function get($id)
    {
        if($id === null){ return false;}

        $id = (int)$id;
        $row_set = $this->tableGateway->select(array('id' => $id));
        $row = $row_set->current();
        return $row;
    }

    public function save(Users $obj)
    {
        $data = array(
            'id' => $obj->id,
            'email' => $obj->email,
            'password' => $obj->password,
            'isactive' => $obj->isactive,
            'dt' => $obj->dt,
        );

        $id = (int)$obj->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->get($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                return false;  //  does not exist?
            }
        }
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getFromUsersByEmail($email)
    {
        $row_set = $this->tableGateway->select(array('email' => $email));
        $row = $row_set->current();
        return $row;
    }
}