<?php

namespace App\Models;

use PDO;

/**
 * Handshake class to test Database Connection
 *
 */
class Handshake extends \Core\Model 
{
    public static function checkConnection(){
        try{
            $db = static::getDB();

        }
        catch(PDOException $ex){
            echo "Handshake failed! ".$ex.getMessage();
        }
    }
}