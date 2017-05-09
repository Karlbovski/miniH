<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 *  Signup Controller
*/

class Signup extends \Core\Controller {

    /**
     * Before Action Filter
     */
     protected function before(){
         
     }

     /**
      * After Action Filter
      */
      protected function after(){
          
      }

    public function newAction(){

        View::renderTemplate('Signup/new.html');
    }

    /**
     * Create/Signup new User
     * 
     * @return void
     */
    public function createAction(){
        
        $user = new User($_POST);

        if($user->save())
        {   
            $user->sendActivationEmail();
            $this->redirect('/signup/success');
        }
        else
        {
            View::renderTemplate('Signup/new.html',[
                'user'=>$user
            ]);
        }
    }

    /**
     * Show the signup success page
     *
     * @return void
     */
    public function successAction(){
        View::renderTemplate('Signup/success.html');
    }

    /**
     * Activate a new account
     *
     * @return void
     */
    public function activateAction(){
        User::activate($this->route_params['token']);
        $this->redirect('/signup/activated');
    }

    /** 
     * Show the activatio success page
     *
     * @return void
     */
    public function activatedAction(){
        View::renderTemplate('Signup/activated.html');
    }

}