<?php

namespace Core;

Class Router 
{
    /**
     * Associative Array of routes (the routing table )
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters from matched route
     *
     * @var array
     */
    protected $params = [];

    /**
     * Add route to the routing table
     * @param string $route The route URL
     * @param array  $params Parameters ( controller, action, etc.)
     *
     * @return void
     */
    public function add($route, $params = []){

        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//','\\/',$route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/','(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/','(?P<\1>\2)', $route);

        //Add start and end delimiters to the regex and make it case sensitive
        $route = '/^'.$route.'$/i';

        $this->routes[$route] = $params;
    }

    public function dispatch($url){

        $url = $this->removeQueryStringVariables($url);

        if($this->match($url))
        {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if(class_exists($controller)){
                $controller_obj = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if(is_callable([$controller_obj, $action])){
                    $controller_obj->$action();
                    
                }else{throw new \Exception("Method $action (in controller $controller) not found!");}
            }else{throw new \Exception("Controller class $controller not found!");}
        }else{throw new \Exception("No route matched!", 404);}
    }

    protected function convertToStudlyCaps($string){
        return str_replace(' ','', ucwords(str_replace('-',' ', $string)));
    }

    protected function convertToCamelCase($string){
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables($url){
        if($url != ''){
            $parts = explode('&', $url, 2);
            if(strpos($parts[0],'=') === false){
                $url = $parts[0];
            }else{
                $url = '';
            }

            return $url;
        }
    }
    
    /**
     * Get the namespace for the controller class. The namespace defined in the route parameters is added IF present. 
     *
     * @return string The URL
     */
    protected function getNamespace()
    {
        
        $namespace = 'App\Controllers\\'; // default

        // Check if a namespace has been passed as parameter to the route
        if(array_key_exists('namespace', $this->params))
        {
            $namespace .= $this->params['namespace'] .'\\'; // Add the namespace in case
        }

        return $namespace;
    }

    /**
    * Match the route to the routes in the routing table, setting the $params
    * property if a route is found.
    *
    * @param string $url The route URL
    * @return boolean true if a match found
    */
    public function match($url){
        foreach($this->routes as $route => $params) {
            if(preg_match($route,$url,$matches)) {
                foreach($matches as $key => $match) {
                    if(is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
    * Get all the routes from the routing table
    * 
    * @return array
    */
    public function getRoutes(){
        return $this->routes;
    }

    /**
    * Get currently matched parameters
    *
    *@return array
    */
    public function getParams(){
        return $this->params;
    }

}