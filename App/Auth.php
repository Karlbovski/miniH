<?php

namespace App;
use App\Models\User;
/**
 * Authentication class
 *
 */
class Auth
{
    /**
     * Login the user
     * 
     * @param User $user The user model
     *
     * @return void
     */
    public static function login($user, $remember_me){
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        if($remember_me){
            if($user->rememberLogin()){
                setcookie("remember_me", $user->remember_token, $user->expiry_timestamp,'/');
            }
        }
    }

    /**
     * Logout the user and destroy the current session
     *
     * @return void
     */
    public static function logout(){
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) 
        {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],                
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }

    /**
     * Store the last request to a $_SESSION variable
     *
     * @return void
     */
    public static function rememberRequestedPage(){
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    /**
     * Return a page to redirect to if any, otherwise return /home as default
     * 
     */
    public static function getReturnPage(){
        return $_SESSION['return_to'] ?? '/';
    }

    /**
     * Get the current logged-in user from the session or remember-me cookie
     *
     * @return mixed The user model or null if not logged in 
     */
    public static function getUser(){
        if(isset($_SESSION['user_id'])){
            return User::findByID($_SESSION['user_id']);
        }
    }
}