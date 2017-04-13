<?php
/**
 *Front Controller
 *
 *PHP version 7.0
*/

//echo 'Requested URL = "'.$_SERVER['QUERY_STRING'].'"';

/**
 * Routing
*/
require '../Core/Router.php';
$router = new Router();
// echo get_class($router);

//Add routes to the routing table
$router->add('',['controller'=>'Home', 'action'=>'index']);
$router->add('posts',['controller'=>'Posts', 'action'=>'index']);
$router->add('posts/new',['controller'=>'Posts', 'action'=>'new']);

// Display the routing table
// echo '<pre>';
// var_dump($router->getRoutes());
// echo '<pre>';

// Match the requested route
$url = $_SERVER['QUERY_STRING'];

if($router->match($url)){
    echo '<pre>';
    var_dump($router->getParams());
    echo '<pre>';
}
else{
    // 404
    echo  "No route found for URL '$url'";
}



