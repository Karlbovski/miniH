<?php

namespace Core;

/**
 * Base controller
 *
 */

abstract class Controller  {

    /*
     * Parameter from the  matched route.$_COOKIE
     * @var array
     **/
    protected $route_params = [];

    /**
     * Class constructor
     * @params array $route_params Parameters from the router
     * 
     * @return void
     */
     public function __construct($route_params){
         $this->route_params = $route_params;
     }

     /**
      * _call Magic Function
      *
      */
      public function __call($name,$args)
      {
          $method = $name . "Action";

          if(method_exists($this,$method))
          {
              if($this->before() !== false)
              {
                  call_user_func_array([$this, $method], $args);
                  $this->after();
              }              
          }
          else
          {
              echo "Method $method not found in controller ". get_class($this);
          }

      }

      /**
       * Override in instances
       */
      protected function before(){

      }

      protected function after(){

      }
}