<?php

namespace App;

/**
 * Flash notification messages using the session to store them between requests
 */
class Flash
{
    /**
     * Add a message
     * @param string Message content
     */
    public static function addMessage($message){
        if(! isset($_SESSION['flash_notifications'])){
            $_SESSION['flash_notifications'] = [];
        }

        $_SESSION['flash_notifications'] [] = $message;
    }

    /**
     * Get all messages
     *
     * @return mixed An array with all the messages or NULL if not set.
     */
    public static function getMessages(){
        if(isset($_SESSION['flash_notifications']))
        {
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);
            
            return $messages;
        }
    }
}