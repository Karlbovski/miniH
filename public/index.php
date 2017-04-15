<?php
/**
 * Front Controller
 *
 * PHP version 7.0
*/

//echo 'Requested URL = "'.$_SERVER['QUERY_STRING'].'"';

// Require the controller class
require '../App/Controllers/Posts.php';

/**
 * Routing
*/
require '../Core/Router.php';
$router = new Router();

//Add routes to the routing table
$router->add('',['controller'=>'Home', 'action'=>'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

// Dispatch Route
$router->dispatch($_SERVER['QUERY_STRING']);

/*
//Display the routing table
echo '<pre>';
echo htmlspecialchars(print_r($router->getRoutes()));
echo '<pre>';

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
*/


