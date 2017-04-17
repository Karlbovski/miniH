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
     * Render a view template using Twig
     * 
     * @param string $template The template File
     * @param array $args Associative array of data to display in the view ( optional )
     *
     * @return void
     */
     public static function renderTemplate($template, $args = []){

         static $twig = null;

         if($twig===null){
             $loader = new \Twig_loader_Filesystem(dirname(__DIR__).'/App/Views');
             $twig = new \Twig_Environment($loader);
         }

         echo $twig->render($template, $args);
     }
}
