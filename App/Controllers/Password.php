<?php

namespace App\Controllers;
use \Core\View;
use \App\Models\User;

/**
 * Password reset controller
 *
 */
class Password extends \Core\Controller
{
    /**
     *  Show the forgot password page
     *
     * @return void
     */
    public function forgotAction(){
        View::renderTemplate('Password/forgot.html');
    }

    /**
     * Send the password reset link
     *
     * @return void
     */
    public function requestResetAction(){
        User::sendPasswordReset($_POST['email']);

        View::renderTemplate('Password/email_sent.html');
    }
}