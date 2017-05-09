<?php

namespace App\Models;
use App\Token;
use App\Mail;
use Core\View;
use App\Config;
use PDO;

/**
 * User Model 
 *
 */
class User extends \Core\Model 
{
    /**
     * Form's errors
     * 
     * @var array Contains input's validation errors
     */
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

        if(empty($this->form_errors))
        {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users (name, email, password_hash, activation_hash) 
                    VALUES (:name, :email, :password_hash, :activation_hash)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name,  PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Server Validation method that adds error messages into the $form_errors array
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

        if(static::emailExists($this->email, $this->id ?? null)){
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
     * @param string $ignore_id Return false anyway if the record found has this ID
     *
     * @return boolean TRUE if a record already exists otherwise FALSE
     */
    public static function emailExists($email, $ignore_id = null)
    {
        $user = static::findByEmail($email);
        if($user){
            if($user->id != $ignore_id){
                return true;
            }
        }        

        return false;
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
        if($user && $user->activated)
        {
            if(password_verify($password, $user->password_hash)){
                return $user;
            }
        }

        return false;
    }

    /**
     * Find a user  model by ID
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

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table for  
     * this user record
     * 
     * @return boolean True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin(){
        $token = new Token();
        $hashed_token = $token->getHash();

        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30; // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s',$this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Send Password Reset instruction to the current User
     *
     * @param string $email Email address
     *
     * @return boolean True if PHPMailer->send == true , otherwise False
     */
    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if($user)
        {
            if($user->startPasswordReset())
            {
                $user->sendPasswordResetEmail();
            }
        }
    }

    /**
     * Start password reset process
     *
     * @return void
     */
    protected function startPasswordReset(){

        $token = new Token();
        $hashed_token = $token->getHash();

        $this->password_reset_token = $token->getValue();

        $password_expiry_timestamp = time() + 60 * 60 * 4; // 2hrs from now  <--- WTF ?? if I multiply by 2 is not working !!?

        $sql = 'UPDATE users SET password_reset_hash =:token_hash,
                password_reset_expires_at =:expires_at
                WHERE id =:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $password_expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Send Password reset instructions in an email to thhe user
     * 
     * @return void
     */
    protected function sendPasswordResetEmail()
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/password/reset/'.$this->password_reset_token;

        $text = View::getTemplate('Password/reset_email.txt', ['url' => $url, 'username' => $this->name]);
        $html = View::getTemplate('Password/reset_email.html', ['url' => $url, 'username' => $this->name]);

       Mail::send(Config::BRAND_NAME, $this->email, $this->name, "Password Reset", $text, $html);
    }

    /**
     * Find a user model by password reset token and expiry date
     *
     * @param string $token The password  reset token sent to the user via email
     *
     * @return mixed User object if found and the token hasn´t expired, otherwise false
     */
    public static function findByPasswordReset($token){
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        $user = $stmt->fetch();

        if($user){
            // Check password reset token expire date
            if(strtotime($user->password_reset_expires_at) > time()){
                return $user;
            }
        }
    }

    /**
     * Reset Password
     *
     * @param string $password The new Password
     *
     * @return boolean True if the password was successfully updated, False otherwise     
     */
    public function resetPassword($password){
        $this->password = $password;
        $this->validate();
        if(empty($this->form_errors)){

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users 
                    SET password_hash =:password_hash,
                        password_reset_hash = NULL,
                        password_reset_expires_at = NULL
                    WHERE id=:id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Send an email to the user containing the account activation Link
     *
     * @return void
     */
    public function sendActivationEmail()
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/signup/activate/'.$this->activation_token;

        $text = View::getTemplate('Signup/activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Signup/activation_email.html', ['url' => $url]);

       Mail::send(Config::BRAND_NAME, $this->email, $this->name, "Account Activation", $text, $html);
    }

    /**
     * Activate the user account with the specified activation token
     *
     * @param string $value Activation token from the URL
     *
     * @return void
     */
    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET activated = 1,
                    activation_hash = NULL
                WHERE activation_hash =:hashed_token';
    
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();        
    }
}
