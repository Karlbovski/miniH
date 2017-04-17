<?php

namespace  Core;

class Error
{
    /**
     * Error Handler. Converts all errors to Exceptions.$_COOKIE
     *
     * @param int $level Error Level
     * @param string $message Error Message
     * @param string $file Name of the file that raised the error
     * @param int $line Line number in the file
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line){
        if(error_reporting() != 0){
            throw new \ErrorException($message, 0, $level, $file, $line );
        }
    }

    /**
     * Exception Handler.
     *
     * @param Exception $exception The Exception
     * 
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        // Code 404(not found)or 500 (general error)
        $code =  $exception->getCode();
        if($code != 404){
            $code = 500;
        }
        http_response_code($code);

        if(\App\Config::SHOW_ERRORS)
        {
            echo "<h1>Fatal Error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->GetMessage() . "'</p>";
            echo "<p>Stack Trace:<pre> " .  $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in: '" . $exception->getFile()."' on line  " . $exception->getLine() . "</p>";
        }
        else
        {
            $log =  dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .=  "Message: '" . $exception->GetMessage() . "'";
            $message .= "\nStack Trace: "  .  $exception->getTraceAsString();
            $message .= "Thrown in: '" . $exception->getFile()."' on line  " . $exception->getLine();

            error_log($message);
            // if($code == 404){
            //      echo "<h1>Page Not Found</h1>";
            // }
            // else{
            //      echo "<h1>Ops, an Error occurred!</h1>";
            // }           
            View::renderTemplate("$code.html");
        }
    }
}