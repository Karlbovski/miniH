<?php

/**
 * Front Controller
 *
 * PHP version 7.0
 *
 * Template Engine : Twig
 *
*/

/**
 * Composer 
 * Autoloader 
 */
require '../vendor/autoload.php';

/**
 * PHP Autoloader 
 * This might be replaced with the Composer Autoloader 
 * for custom classes.
 * 
 */
spl_autoload_register(function($class){
    $root = dirname(__DIR__); //  get parent directory
    $file = $root.'/'.str_replace('\\','/', $class).'.php';
    if(is_readable($file)){
        require $root.'/'.str_replace('\\','/', $class) .'.php';
    }
});

/**
 * Errors and Exceptions handling
 */
 error_reporting(E_ALL);
 set_error_handler('Core\Error::errorHandler');
 set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
*/
$router = new Core\Router();

//Add routes to the routing table
$router->add('',['controller'=>'Home', 'action'=>'index']);
$router->add('{controller}/{action}');
//$router->add('{controller}/{id:\d+}/{action}');
//$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

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


