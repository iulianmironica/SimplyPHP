<?php

namespace Framework;

use Framework\Database;

/**
 * Description of Model
 *
 * @author Iulian Mironica
 */
class Model
{

    public $db;
    public $tableName;

    public function __construct()
    {
        $this->db = Database::init();
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    /**
     * @param type $id
     * @param type $tableName
     * @param type $idColumnName
     * @param type $columns
     * @param type $orderBy
     * @return type
     */
    public function getById($id, $tableName, $idColumnName = 'id', $columns = null, $orderBy = null)
    {

        if (is_array($columns)) {
            $columns = implode(',', $columns);
        } else {
            $columns = '*';
        }

        $sql = "SELECT {$columns}
                FROM {$tableName}
                WHERE {$idColumnName} = {$id} ";


        if (!empty($orderBy)) {
            $sql.= " ORDER BY {$orderBy} ";
        }

        $results = $this->db->query($sql);
        return $results->fetch();
    }

    /**
     * @param type $tableName
     * @param type $columns
     * @param type $orderBy
     * @param type $limit
     * @return type
     */
    public function getAll($tableName, $columns = null, $orderBy = null, $limit = 100)
    {
        if (is_array($columns)) {
            $columns = implode(',', $columns);
        } else {
            $columns = '*';
        }

        $sql = "SELECT {$columns}
                FROM {$tableName}
                LIMIT {$limit} ";

        if (!empty($orderBy)) {
            $sql.= " ORDER BY {$orderBy} ";
        }
        $data = array();
        $results = $this->db->query($sql);
        while ($result = $results->fetchAll()) {
            $data[] = $result;
        }
        return $data;
    }

    /**
     * @param type $sql
     * @param type $parameters
     * @param type $fetchMode PDO::FETCH_ASSOC
     * @return null
     */
    public function query($sql, $parameters = null, $fetchMode = PDO::FETCH_ASSOC)
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($parameters ? : $parameters);

            // var_dump($stmt);
            // var_dump($stmt->fetchAll($fetchMode));
            // exit();

            return $stmt->fetchAll($fetchMode);
        } catch (Exception $e) {
            Util::showError($e);
            return null;
        }
    }

}
