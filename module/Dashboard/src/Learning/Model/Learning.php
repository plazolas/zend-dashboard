<?php

namespace Dashboard\Learning\Model;

use Zend\Db\Adapter\Adapter;

class Learning
{
    private $table;

    public function __construct(Adapter $dbAdapter)
    {
        $class_name = explode('\\',__CLASS__);
        $this->table = strtolower($class_name[count($class_name) - 1]);
        $sql = "EXPLAIN {$this->table}";
        $resultSet = $dbAdapter->query($sql,Adapter::QUERY_MODE_EXECUTE);
        $result = $resultSet->toArray();
        if(empty($result)) {
            throw new \Exception(__METHOD__.": cant get column names!");
        }
        foreach ($result as $column => $field) {
            foreach($field as $k => $field_name) {
                if($k == 'Field') {
                    $this->{$field_name}=null;
                }
            }
        }
    }

    public function exchangeArray($data)
    {
        foreach ($data as $k => $v) {
            switch ($v){
                case (is_numeric($v) === true) :
                    $this->{$k} = (int) $v;
                    break;
                default:
                    $this->{$k} = $v;
            }
        }
    }
}