<?php

namespace Dashboard\User\Model;

use Zend\Db\TableGateway\TableGateway;
// use Zend\Db\Sql\Select;


class UserTable
{
    protected $tableGateway;
    protected $table2 = 'learning';

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * @return array for objects;
     */
    public function fetchJoinUser()
    {
        /***** THIS DID NOT WORK BECAUSE user and learning table have a column name `name`  --> user column name gets overwritten
        $select = new Select($this->tableGateway->table);
        $select->join(
            learning,
            'user.pid = learning.id',
            ['*'],
            Select::JOIN_INNER
        );

        $sql = $select->getSqlString();
        $sql = preg_replace('/"/','', $sql);
        */

        $sql = "SELECT user.*, {$this->table2}.region AS region, {$this->table2}.name AS practice FROM user INNER JOIN {$this->table2} ON user.pid = {$this->table2}.id";

        $resultSet = $this->tableGateway->adapter->query($sql)->execute();
        while ($resultSet->current()) {
            $tmp_user = $resultSet->current();
            $tmp_user['pid'] = $tmp_user['practice'];
            $result[] = (object) $tmp_user;
            $resultSet->next();
        }

        return $result;
    }

    public function fetchPractices()
    {
        $sql = "SELECT {$this->table2}.* FROM {$this->table2}";

        $result = $this->tableGateway->adapter->query($sql)->execute();

        return $result;
    }

    public function get($id)
    {
        if($id === null){ return false;}

        $id = (int)$id;
        $row_set = $this->tableGateway->select(array('id' => $id));
        $row = $row_set->current();
        return $row;
    }

    public function save(User $obj)
    {
        $data = array(
            'email' => $obj->email,
            'pid' => $obj->pid,
            'name' => $obj->name,
            'role' => $obj->role,
            'dt' => $obj->dt,
            'password' => $obj->password,
            'user' => $obj->user,
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

    public function getUserByEmail($email)
    {
        $row_set = $this->tableGateway->select(array('email' => $email));
        $row = $row_set->current();
        return $row;
    }
}