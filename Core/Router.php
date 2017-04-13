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
     public function add($route, $params){
         $this->routes[$route] = $params;
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
       * property if a route is found.$_COOKIE
       * 
       * @param string $url The route URL
       *
       * @return boolean true if a match found
       */
       public function match($url)
       {
           foreach($this->routes as $route => $params){
               if($url == $route){
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