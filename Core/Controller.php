<?php

namespace Core;
use App\Auth;
use App\Flash;

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
      * __call Magic Function
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
              throw new \Exception("Method $method not found in controller ". get_class($this));
          }

      }

      /** 
       * Redirect to a different page
       *
       * @param string The relative URL
       *
       * @return  void
       */
      public function redirect($url){
           header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
            exit;
      }

      /**
       * Require the user to be logged in before giving access to the requested page.
       *
       * @return void
       */
      public function requireLogin(){

          if(! Auth::getUser())
          {
              Flash::addMessage("Please Log-in to access the Page.");
              Auth::rememberRequestedPage();
              $this->redirect('/login');
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