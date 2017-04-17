<?php

namespace App\Controllers;

use \Core\View;

/*
 * Posts controller
 **/

class About extends \Core\Controller
{
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
        // echo "Hello from the index action in Posts controller";
        // echo "<p>Query string parameters:  <pre>". htmlspecialchars(print_r($_GET, true)) ."</pre></p>";
        View::renderTemplate('About/index.html', [
            "frameworkVersion" => \App\Config::FRAMEWORK_VERSION
                ] 
            );
    }

}