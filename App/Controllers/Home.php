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

    public function indexAction(){
        
        \App\Mail::send("STL","dev@sixteenleft.com","Subject","Name","Text","HTML"); // <-- TEST TO REMOVE
        
        // Using Twig
        View::renderTemplate('Home/index.html');

        // Not using Twig
        // View::render('Home/index.php', [
        //         "name" => "theName",
        //         "colours" => ['red','green','blue']
        //     ]
        // );        
    }

}