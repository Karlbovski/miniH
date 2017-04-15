<?php

namespace App\Controllers;

/*
 * Posts controller
 **/

class Posts extends \Core\Controller
{

    public function indexAction(){
        echo "Hello from the index action in Posts controller";
        echo "<p>Query string parameters:  <pre>". htmlspecialchars(print_r($_GET, true)) ."</pre></p>";
    }

    public function addNewAction(){
        echo "Hello from the addNew action in Posts controller";
    }

    public function editAction(){
        echo "Hello from the edit action in Posts controller";
        echo "<p>Route parameters:  <pre>". 
                htmlspecialchars(print_r($this->route_params, true)) ."</pre></p>";
    }
}