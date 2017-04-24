<?php

namespace App\Models;

use PDO;

/**
 * Handshake class to test Database Connection
 *
 */
class User extends \Core\Model 
{
    /*
     * Form's errors
     * 
     * @var array Contains input's validation errors
     **/
    public $form_errors = [];

    /**
     * Class base constructor method
     *
     * @param array $data Initial property values
     */
    public function __construct($data = []){
        foreach($data as $key=>$value){
            $this->$key = $value;
        };
    }


    /**
     * Save the user model with current properties
     * 
     * @return void
     */
    public function save(){

        $this->validate();
        if(empty($this->form_errors)){
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->name,  PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

        return $stmt->execute();
        }

        return false;
    }

    /**
     * Validation method that adds error messages into the $form_errors array
     *
     * @return void
     */
    public function validate(){

        // Name
        if($this->name == ''){
            $this->form_errors[]='Name is required';
        }

        // Email Address
        if(filter_var($this->email, FILTER_VALIDATE_EMAIL) ===  false){
            $this->form_errors[]='Invalid email';
        }

        if(static::emailExists($this->email)){
            $this->form_errors[]='Email already taken';
        }

        // Password
        if(strlen($this->password)<6){
            $this->form_errors[]='Password must be at least 6 characters long';
        }

        if(preg_match('/.*[a-z]+.*/i', $this->password) == 0){
            $this->form_errors[]= 'Password needs at least one letter';
        }

         if(preg_match('/.*\d+.*/i', $this->password) == 0){
            $this->form_errors[]= 'Password needs at least one number';
        }
    }

    /**
     * Check if $this->email is available or if it is already been recorded in the database
     * 
     * @param string $email Email to check 
     *
     * @return boolean TRUE if a record already exists otherwise FALSE
     */
    public static function emailExists($email){
        return static::findByEmail($email) !== false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email The email address to search
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email){
        $sql = 'SELECT * FROM users WHERE email =:email';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Authenticate the user by email and password
     *
     * @param string $email
     * @param string $password
     *
     * @return mixed The User object or false if the authentication fails
     */
    public static function authenticate($email, $password){
        $user = static::findByEmail($email);
        if($user){
            if(password_verify($password, $user->password_hash)){
                return $user;
            }
        }

        return false;
    }

    /**
     * 
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id =:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
}