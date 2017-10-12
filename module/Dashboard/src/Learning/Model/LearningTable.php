<?php

namespace Dashboard\Learning\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\ResultSet\ResultSet;

class LearningTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }
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

    public function save(Learning $obj)
    {
        $class = explode('\\', get_class($obj));
        $table = strtolower($class[count($class) - 1]);
        $sql = "EXPLAIN {$table}";
        $resultSet = $this->tableGateway->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $result = $resultSet->toArray();
        if(empty($result)) {
            throw new \Exception(__METHOD__.": cant get column names!");
        }
        foreach ($result as $column => $field) {
            foreach($field as $k => $field_name) {
                if($k == 'Field') {
                    $data[$field_name] = $obj->$field_name;
                }
            }
        }

        /*
         * STATIC WAY WOULD BE:
        $data = array(
            'name' => $obj->name,
            'type' => $obj->type,
            'regions' => $obj->regions,
            'season' => $obj->season,
        );
        */

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

    private function fetchPaginatedResults()
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Learning($this->tableGateway->adapter));

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
        // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->adapter,
            // the result set to hydrate:
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

}