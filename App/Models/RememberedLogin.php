<?php

namespace App\Models;
use App\Token;
use PDO;

/**
 * Remembered login Model
 * 
 */
class RememberedLogin extends \Core\Model
{
    /**
     * Find a remembered login model by token
     *
     */
     public static function findByToken($token)
     {
         $token = new Token($token);
         $token_hash = $token->getHash();

         $sql = 'SELECT * FROM remembered_logins
                 WHERE token_hash = :token_hash';

         $db = static::getDB();

         $stmt = $db->prepare($sql);
         $stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);
         $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
         $stmt->execute();

         return $stmt->fetch();
     }

     /**
      * Get the user model associated with this remembered login
      *
      * @return User The user model
      */
     public function getUser(){
        return User::findByID($this->user_id);
     }

     /**
      * Check if the current remember token has expired, based on current system time
      *
      * @return boolean True if the token has expired
      */
     public function hasExpired(){
        return strtotime($this->expires_at) < time();
     }

     public function deleteToken(){

         $sql = 'DELETE FROM remembered_logins WHERE token_hash =:token_hash';

         $db = static::getDB();
         $stmt = $db->prepare($sql);
         $stmt->bindValue(':token_hash', $this->token_hash, PDO::PARAM_STR);
         $stmt->execute();
     }
}