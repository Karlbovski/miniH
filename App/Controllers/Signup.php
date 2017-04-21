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
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/signup/success', true,303);
            exit;
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

}