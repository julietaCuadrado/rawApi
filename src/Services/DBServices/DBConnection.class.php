<?php

namespace Services\DBServices;

use Exceptions\DBConnectionException;
use mysqli;

/**
 * DBConnection will be an implemetation of the Singelton pattern,
 * therefore we will avoid the creation of unwanted open connections to the
 * db service
 *
 * Class DBConnection
 */
class DBConnection
{
    private static $dbConnectionObject;
    /** @var mysqli $dbConnectionHandler */
    private $dbConnectionHandler;

    /**
     * DBConnection constructor.
     */
    private function __construct()
    {
        $this->dbConnectionHandler = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
        $this->dbConnectionHandler->set_charset("utf8");
        if ( $this->dbConnectionHandler -> connect_errno) {
            throw new DBConnectionException('Connection not successful');
        }
    }

    /**
     * @return DBConnection
     */
    public static function getConnection()
    {
        if (!isset(self::$dbConnectionObject)) {
            self::$dbConnectionObject = new DBConnection();
        }

        return self::$dbConnectionObject;
    }

    /**
     * @throws DBConnectionException
     */
    public function __clone()
    {
        throw new DBConnectionException('Clonning Singelton Object is forbidden');
    }

    /**
     * @return mysqli
     */
    public function getHandler ()
    {
        if (!isset(self::$dbConnectionObject))
        {
            self::$dbConnectionObject = new DBConnection();
        }

        return $this->dbConnectionHandler;
    }
}
