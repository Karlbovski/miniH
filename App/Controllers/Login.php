<?php

namespace App\Controllers;
use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

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
    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if($user)
        {
            Auth::login($user, $remember_me);

            //Remember the login here
            // ...

            Flash::addMessage('Login successful');

            $this->redirect(Auth::getReturnPage());
        }
        else
        {

            Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);

            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
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
        $this->redirect('/login/show-logout-message');
    }

    /**
     *  Show a "logged out" message. This is necessary since the Auth::logout() destroys the session,
     *  so a new action needs to be called in order to use the session
     */
    public function showLogoutMessageAction(){
        Flash::addMessage('Logout successful');
        $this->redirect('/');
    }
}