<?php

namespace App\Controllers\Admin;

/**
 * User admin controller
 *
 */

class Users extends \Core\Controller
{
    /**
     * Before Action Filter
     */
     protected function before(){
         echo "(before) ";
         // e.g.  Use to check if an admin is logged in
         // otherwise 
         // return false;
     }

     /**
      * After Action Filter
      */
      protected function after(){
          echo " (after)";
      }

    public  function indexAction(){

        echo "Welcome to the Users Admin controller Index.";

    }
}