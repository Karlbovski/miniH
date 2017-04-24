<?php

namespace App\Controllers;
use \Core\View;
use \App\Models\User;
use \App\Auth;

/**
 * Login Controller
 *
 */

class Login extends \Core\Controller
{
    /**
     * Show Login Page
     *
     * @return  void
     */
    public function newAction(){
        View::renderTemplate("Login/new.html");
    }

    /**
     * Log in User
     *
     * @return void
     */
    public function createAction(){
        $user = User::authenticate($_POST['email'], $_POST['password']);
        if($user)
        {
            Auth::login($user);
            $this->redirect(Auth::getReturnPage());
        }
        else
        {
            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email']
            ]);
        }
    }

    /**
     * Logout a user and destroy the current session
     *
     * @return void
     */
    public function destroyAction(){
        Auth::logout();
        $this->redirect('/');
    }
}