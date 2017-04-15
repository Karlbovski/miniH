<?php

namespace App\Controllers;

use \Core\View;

/**
 *  Home Controller
*/

class Home extends \Core\Controller {

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

    public  function indexAction(){

        //echo "Welcome to the Home controller Index.";
        View::render('Home/index.php', [
                "name" => "theName",
                "colours" => ['red','green','blue']
            ]
        );

    }

}