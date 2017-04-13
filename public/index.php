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
$route = new Router();

echo get_class($router);
