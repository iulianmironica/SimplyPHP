<?php

namespace Framework;

use Application\Settings\Config;

//use Application\Library\Doctrine;

/**
 * Description of Model
 *
 * @author Iulian Mironica
 */
class Model
{

    public $doctrine;
    public $pdo;
    public $tableName;

    public function __construct()
    {
        if (isset(Config::$doctrine['enable']) && Config::$doctrine['enable'] === true) {
            $this->doctrine = \Application\Library\Doctrine::connect();
            $this->pdo = $this->doctrine;
        } else {
            $this->pdo = Database::init();
        }
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
        try {

            if (is_array($columns)) {
                $columns = implode(',', $columns);
            } else {
                $columns = '*';
            }

            $sql = "SELECT {$columns}
                FROM {$tableName}
                WHERE {$idColumnName} = {$id} ";


            if (!empty($orderBy)) {
                $sql .= " ORDER BY {$orderBy} ";
            }

            $results = $this->pdo->prepare($sql);
            $results->execute();
            return $results->fetch();
        } catch (\PDOException $e) {
            Utility::showError('Exit. Exception: ', $e);
            return false;
        }
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
                ";

        if (!empty($orderBy)) {
            $sql .= " ORDER BY {$orderBy} ";
        }

        if ($limit) {
            $sql .= " LIMIT {$limit} ";
        }

        /*var_dump($sql);
        exit();*/

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     *
     * @param type $sql
     * @param type $parameters
     * @param type $fetchMode
     * @param type $fetchAll = true
     * @return null
     */
    public function query($sql, $parameters = null, $fetchMode = \PDO::FETCH_ASSOC, $fetchAll = true)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters ?: $parameters);

            // var_dump($stmt);
            // var_dump($stmt->fetchAll($fetchMode));
            //exit();

            if ($fetchAll) {
                return $stmt->fetchAll($fetchMode);
            } else {
                return $stmt->fetch($fetchMode);
            }
        } catch (\PDOException $e) {
            Utility::showError('Exit. Exception: ', $e->getMessage());
            return false;
        }
    }

}
