<?php

namespace Dashboard\Allergen\Model;

use Zend\Db\TableGateway\TableGateway;

class AllergenTable
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

    public function save(Allergen $obj)
    {
        $data = array(
            'name' => $obj->name,
            'type' => $obj->type,
            'regions' => $obj->regions,
            'season' => $obj->season,
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
}