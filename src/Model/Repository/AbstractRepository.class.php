<?php

namespace Model\Repository;

use Exceptions\SqlException;
use Exceptions\SqlMissingFieldsException;
use Exceptions\SqlMissingTableNameException;
use Exceptions\SqlMissingValuesException;
use Exceptions\SqlMissmatchFieldsValuesException;
use Services\DBServices\DBConnection;

abstract class AbstractRepository
{
    /** @var  DBConnection $dbConnection */
    protected $dbConnection;

    /**
     * AbstractRepository constructor.
     * @param $dbConnection
     */
    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @param $tableName
     * @param $fieldsArray
     * @param array $whereArray
     * @param array $whereValues
     * @return mixed
     * @throws SqlException
     * @throws SqlMissingFieldsException
     * @throws SqlMissingTableNameException
     * @throws SqlMissmatchFieldsValuesException
     */
    public function select ($tableName, $fieldsArray, $whereArray=[], $whereValues=[])
    {
        //validate
        $this->validateParamsSelect($tableName, $fieldsArray, $whereArray, $whereValues);
        //clean fields names
        $escapedFieldsArray = array_map(function($field) {
            return mysqli_real_escape_string($this->dbConnection->getHandler(), $field);
        }, $fieldsArray);
        //getting sql
        $sql = 'SELECT %s FROM %s ';
        $sql = sprintf($sql, implode(', ',$escapedFieldsArray), $tableName);
        if (count($whereArray))
        {
            $sql .= sprintf(' WHERE %s', implode(' AND ', $whereArray));
        }
        //prepare statment
        $stmt = $this->dbConnection->getHandler()->prepare($sql);
        //bind params
        if (count($whereArray)) {
            $paramsType = array_map(function($param) { return gettype($param)[0]; }, $whereValues);
            $stmt->bind_param(implode('', $paramsType), ...$whereValues);
        }
        try {
            $stmt->execute();
        } catch (\Exception $e) {
            throw new SqlException ($e->getMessage(), $e->getCode());
        }
        $result = $stmt->get_result();
        $fetchedResult = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $fetchedResult ;
    }

    /**
     * @param $tableName
     * @param array $fieldsArray
     * @param array $valuesArray
     * @return mixed
     * @throws SqlException
     * @throws SqlMissingFieldsException
     * @throws SqlMissingTableNameException
     * @throws SqlMissingValuesException
     * @throws SqlMissmatchFieldsValuesException
     */
    public function insert ($tableName, $fieldsArray, $valuesArray)
    {
        //validate
        $this->validateParamsInsertUpdate($tableName, $fieldsArray, $valuesArray);
        //get sql
        $sql = 'INSERT INTO %s (%s) VALUES (%s)';
        $valuesSqlString = array_fill(0, count($fieldsArray), '?');
        $escapedFieldsArray = array_map(function ($field) {
            return mysqli_real_escape_string($this->dbConnection->getHandler(), $field);
        }, $fieldsArray);
        $sql = sprintf($sql, $tableName, implode (',', $escapedFieldsArray), implode (',', $valuesSqlString));
        //prepate sql
        $stmt = $this->dbConnection->getHandler()->prepare($sql);
        //bind params & execute
        try {
            foreach ($valuesArray as $row)
            {
                $paramsType = array_map(function($param) { return gettype($param)[0]; }, $row);
                $stmt->bind_param(implode('', $paramsType), ...$row);
                $stmt->execute();
                if (''!== $stmt->error)
                {
                    throw new SqlException ($stmt->error, $stmt->errno);
                }
            }
        } catch (\Exception $e) {
            throw new SqlException ($e->getMessage(), $e->getCode());
        }
        return count($valuesArray);
    }

    /**
     * @param $tableName
     * @param $fieldsArray
     * @param $valuesArray
     * @param $whereArray
     * @param $whereValuesArray
     * @return int
     * @throws SqlException
     * @throws SqlMissingFieldsException
     * @throws SqlMissingTableNameException
     * @throws SqlMissingValuesException
     * @throws SqlMissmatchFieldsValuesException
     */
    public function update ($tableName, $fieldsArray, $valuesArray, $whereArray, $whereValuesArray=[])
    {
        $this->validateParamsInsertUpdate($tableName, $fieldsArray, $valuesArray);
        //add ? to fields -> set fieldA = ?, fieldB = ?
        $setArray = array_map(function ($field) {
            return sprintf('%s = ?', mysqli_real_escape_string($this->dbConnection->getHandler(), $field));
        }, $fieldsArray);
        // get sql
        $sql = 'UPDATE %s SET %s WHERE %s' ;
        $sql = sprintf($sql, $tableName, implode(', ', $setArray), implode(' AND ', $whereArray));
        //prepare
        $stmt = $this->dbConnection->getHandler()->prepare($sql);
        //bind params
        $allParamsValueArray = array_merge($valuesArray, $whereValuesArray);
        $allParamsTypeArray = array_map(function($param) { return gettype($param)[0]; }, $allParamsValueArray);
        $stmt->bind_param($allParamsTypeArray, ...$allParamsValueArray);
        //execute
        try {
            $stmt->execute();
        } catch (\Exception $e) {
            throw new SqlException ($e->getMessage(), $e->getCode());
        }
        if (''!== $stmt->error)
        {
            throw new SqlException ($stmt->error, $stmt->errno);
        }
        return $stmt->affected_rows;
    }

    /**
     * @param $tableName
     * @param $whereArray
     * @param array $whereValuesArray
     * @return int
     * @throws SqlException
     * @throws SqlMissingTableNameException
     * @throws SqlMissmatchFieldsValuesException
     */
    public function delete ($tableName, $whereArray, $whereValuesArray=[])
    {
        //validate
        $this->validateDelete($tableName, $whereArray, $whereValuesArray);
        //get sql
        $sql = 'DELETE FROM %s WHERE %s ';
        $sql = sprintf($sql, $tableName, implode(' AND ', $whereArray));
        //prepare
        $stmt = $this->dbConnection->getHandler()->prepare($sql);
        //bind
        $paramsType = array_map(function($param) { return gettype($param)[0]; }, $whereValuesArray);
        $stmt->bind_param(implode('', $paramsType), ...$whereValuesArray);
        //execute
        try {
            $stmt->execute();
        } catch (\Exception $e) {
            throw new SqlException ($e->getMessage(), $e->getCode());
        }
        if (''!== $stmt->error)
        {
            throw new SqlException ($stmt->error, $stmt->errno);
        }
        return $stmt->affected_rows;
    }

    /**
     * @param $tableName
     * @param $fieldsArray
     * @param array $valuesArray
     * @return bool
     * @throws SqlMissingFieldsException
     * @throws SqlMissingTableNameException
     * @throws SqlMissingValuesException
     * @throws SqlMissmatchFieldsValuesException
     */
    private function validateParamsInsertUpdate($tableName, $fieldsArray, $valuesArray)
    {
        if (''===$tableName)
        {
            throw new SqlMissingTableNameException();
        }
        if (0 === count($fieldsArray))
        {
            throw new SqlMissingFieldsException();
        }
        if (1>$valuesArray)
        {
            throw new SqlMissingValuesException();
        }
        $numberOfFields = count($fieldsArray);
        foreach ($valuesArray as $rowArray)
        {
            if (count($rowArray) !== $numberOfFields)
            {
                throw new SqlMissmatchFieldsValuesException();
            }
        }
        return true;
    }

    /**
     * @param $tableName
     * @param $fieldsArray
     * @param array $whereArray
     * @param $whereParamsArray
     * @return bool
     * @throws SqlMissingFieldsException
     * @throws SqlMissingTableNameException
     * @throws SqlMissmatchFieldsValuesException
     */
    private function validateParamsSelect($tableName, $fieldsArray, $whereArray, $whereParamsArray)
    {
        if (''===$tableName)
        {
            throw new SqlMissingTableNameException();
        }
        if (0 === count($fieldsArray))
        {
            throw new SqlMissingFieldsException();
        }
        $countWhereParams = 0;
        foreach ($whereArray as $whereString)
        {
            if (false !== strpos($whereString, '?'))
            {
                $countWhereParams++;
            }
        }
        if ($countWhereParams !== count($whereParamsArray))
        {
            throw new SqlMissmatchFieldsValuesException();
        }

        return true;
    }

    /**
     * @param $tableName
     * @param array $whereArray
     * @param $whereParamsArray
     * @return bool
     * @throws SqlMissingTableNameException
     * @throws SqlMissmatchFieldsValuesException
     */
    private function validateDelete($tableName, $whereArray, $whereParamsArray)
    {
        if (''===$tableName)
        {
            throw new SqlMissingTableNameException();
        }
        $countWhereParams = 0;
        foreach ($whereArray as $whereString)
        {
            if (false !== strpos($whereString, '?'))
            {
                $countWhereParams++;
            }
        }
        if ($countWhereParams !== count($whereParamsArray))
        {
            throw new SqlMissmatchFieldsValuesException();
        }

        return true;
    }
}