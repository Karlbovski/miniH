<?php

Class Router 
{
    /**
     * Associative Array of routes (the routing table )
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters from matched route
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
        if($this->match($url)){
             $controller = $this->params['controller'];
             $controller = $this->convertToStudlyCaps($controller);

             if(class_exists($controller)){
                 $controller_obj = new $controller();

                 $action = $this->params['action'];
                 $action = $this->convertToCamelCase($action);

                 if(is_callable([$controller_obj, $action])){
                     $controller_obj->$action();
                     
                 }else{echo "Method $action (in controller $controller) not found!";}
             }else{echo "Controller class $controller not found!";}
         }else{echo "No route matched!";}
     }

     protected function convertToStudlyCaps($string){
         return str_replace(' ','', ucwords(str_replace('-',' ', $string)));
     }

     protected function convertToCamelCase($string){
         return lcfirst($this->convertToStudlyCaps($string));
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
       * Match the route to the routes in the routing table, setting the $params
       * property if a route is found.
       *
       * @param string $url The route URL
       * @return boolean true if a match found
       */
       public function match($url)
       {
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
        * Get currently matched parameters
        *
        *@return array
        */
        public function getParams(){
            return $this->params;
        }

}