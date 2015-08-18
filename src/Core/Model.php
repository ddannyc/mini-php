<?php
/**
 * Created by PhpStorm.
 * User: wayne 
 * Date: 2015/7/3
 * Time: 11:04
 */

namespace App\Core;
use PDO;

class Model {
    protected $_table = 'null';
    protected $_db;

    public function __construct()
    {
        $this->_db = self::getDb();
    }

    protected static function getDb()
    {
        static $db = null;
        if(null === $db) {
            $db = new PDO(DSN, DB_USER, DB_PASSWD);
        }
        return $db;
    }

    /**
     * Custom query
     * Warning: plainText conditions will not be sanitized for query
     * @param $fields
     * @param $conditions
     * @param string $plainText
     * @param string $limit
     * @return array
     */
    public function getDataByParams($fields, $conditions, $plainText='', $limit = '')
    {
        if(is_array($fields)) {
            $strFields = implode(', ', $fields);
        } else {
            $strFields = '*';
        }
        $strWhere = $this->buildConditions($conditions);
        if(strlen($plainText) > 0) {

            if(strlen($strWhere) > 0) {
                $strWhere .= ' AND '. $plainText;
            } else {
                $strWhere = $plainText;
            }
        }

        $sql = "SELECT $strFields FROM " . $this->_table. " WHERE $strWhere $limit";
        $sth = $this->_db->prepare($sql);

        $sth->execute(array_values($conditions));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($data, $conditions)
    {
        $arrFields = array_keys($data);
        foreach($arrFields as &$val) {
            $val = '`'. $val. '` =?';
        }
        $strFields = implode(', ', $arrFields);
        $strConditions = $this->buildConditions($conditions);

        $sql = "UPDATE ". $this->_table. " SET $strFields WHERE $strConditions";
        $sth = $this->_db->prepare($sql);

        $sth->execute(array_merge(array_values($data), array_values($conditions)));
        $affected_rows = $sth->rowCount();

        return $affected_rows;
    }

    public function save($data)
    {
        $fields = array_keys($data);
        $strFields = implode('`, `', $fields);
        $strFields = strlen($strFields) > 0 ? ('`'. $strFields. '`') : '';
        $strValues = str_repeat('?, ', count($fields));
        $strValues = substr($strValues, 0, strlen($strValues) - 2);

        $sql = "INSERT INTO ". $this->_table. " ($strFields) VALUES ($strValues)";
        $sth = $this->_db->prepare($sql);
        $sth->execute(array_values($data));

        return $this->_db->lastInsertId();
    }

    protected function buildConditions($conditions)
    {
        $strWhere = '';

        if(!empty($conditions)) {

            $conditions_key = array_keys($conditions);
            foreach($conditions_key as $key=>$val) {

                $val = trim($val);
                if(strpos($val, ' ') === false) {
                    $conditions_key[$key] = $val. '=?';
                } else {
                    $arr_val = explode(' ', $val);

                    if(in_array($arr_val[1], array('>', '>=', '<', '<=', 'like'))) {
                        $conditions_key[$key] = $val. ' ?';
                    } else {
                        $conditions_key[$key] = '-1=?';
                    }
                }
            }
            $strWhere = implode(' AND ', $conditions_key);
        }

        return $strWhere;
    }

    public function getRowCount($conditions)
    {
        $strWhere = $this->buildConditions($conditions);

        $sql = "SELECT COUNT(Id) as cnt FROM `{$this->_table}` WHERE $strWhere";
        $sth = $this->_db->prepare($sql);
        $sth->execute(array_values($conditions));

        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return isset($result['cnt'])? intval($result['cnt']): 0;
    }
}
