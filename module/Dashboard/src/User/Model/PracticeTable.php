<?php

namespace Dashboard\User\Model;

use Zend\Db\TableGateway\TableGateway;

class PracticeTable
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
        if($id === null || $id == 0){ return false;}

        $id = (int)$id;
        $row_set = $this->tableGateway->select(array('id' => $id));
        $row = $row_set->current();
        return $row;
    }

    public function save(Practice $obj)
    {
        $data = array(
            'id' => $obj->id,
            'name' => $obj->name,
            'contact' => $obj->contact,
            'email' => $obj->email,
            'address' => $obj->address,
            'address1' => $obj->address1,
            'city' => $obj->city,
            'state' => $obj->state,
            'zip' => $obj->zip,
            'phone' => $obj->phone,
            'region' => $obj->region,
        );

        $id = (int)$obj->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->get($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                return false;  // does not exist
            }
        }
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getPracticeByRegion($region)
    {
        $row_set = $this->tableGateway->select(['region' => $region]);
        $row = $row_set->current();
        return $row;
    }

}