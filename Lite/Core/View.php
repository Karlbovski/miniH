<?php

namespace Core;

class View 
{    
    /**
     * Render a view file
     * 
     * @param string $view The view File
     * @param array $args Associative array of data to display in the view ( optional )
     *
     * @return void
     */
    public static  function render($view, $args = []){

        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view"; //  relative to the Core directory

        if(is_readable($file)){
            require $file;
        }else{
            throw new \Exception("$file not found!");
        }
    }

    /**
     * Render a view template built using Twig
     * 
     * @param string $template The template File
     * @param array $args Associative array of data to display in the view ( optional )
     *
     * @return void
     */
     public static function renderTemplate($template, $args = []){

         echo static::getTemplate($template, $args);
     }

     /**
     * Get the contents of a view template using Twig
     * 
     * @param string $template The template File
     * @param array $args Associative array of data to display in the view ( optional )
     *
     * @return string
     */
     public static function getTemplate($template, $args = []){

         static $twig = null;

         if($twig===null){
             $loader = new \Twig_Loader_Filesystem(dirname(__DIR__).'/App/Views');
             $twig = new \Twig_Environment($loader);            
             $twig->addGlobal('brand_name', \App\Config::BRAND_NAME);
             $twig->addGlobal('auth_sys_enabled', \App\Config::AUTH_SYS_ENABLED);
             //$twig->addGlobal('is_logged_in', \App\Auth::isLoggedIn());

         }

         return $twig->render($template, $args);
     }
}
