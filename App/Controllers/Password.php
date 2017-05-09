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
    public function requestResetAction()
    {
        User::sendPasswordReset($_POST['email']);
        View::renderTemplate('Password/email_sent.html');
    }

    /**
     * Show the reset password form
     *
     */
    public function resetAction()
    {
        $token = $this->route_params['token'];
        
        $user = $this->getUserOrExit($token);
        
        View::renderTemplate('Password/reset.html',[
            'token' => $token
        ]);
        
    }

    /**
     * User's Password reset action
     *
     * @return void
     */
    public function resetPasswordAction(){
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        if($user->resetPassword($_POST['password']))   {
            //echo "Password validated on server: OK!";
            View::renderTemplate('Password/reset_success.html');
        }
        else
        {
            //echo "Password validated on server: FALSE!!";
            View::renderTemplate('Password/reset.html',[
            'token' => $token,
            'user' => $user
            ]);
        }
    }

    /**
     * Find the user model associated with the password reset token, or end the requestwith a message  
     *
     * @param string $token Password reset token ssent to the user
     *
     * @return mixed USer  object if found and the token hasn´t expired, null otherwise
     */
    protected function getUserOrExit($token){
        $user = User::findByPasswordReset($token);
        if($user){
            return $user;
        }else{
            View::renderTemplate('Password/token_invalid.html');
            exit;
        }        
    }
}