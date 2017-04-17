<?php

namespace Core;

use App\Config;
use PDO;

/**
 * Base Model class
 *
 */
 abstract class Model
 {
    /**
    * Get the PDO database connection
    *
    * @return mixed
    */
    protected static function getDB()
    {
        static $db = null;

        if($db === null){
            try{
                //DataSourceName
                $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            }
            catch(PDOException $ex){
                echo "Error connectingto the databbase : ".$ex->getMessage();
            }
        }
        return $db;
    }      
 }