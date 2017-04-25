<?php

namespace App;

/**
 * Application configuration file
 *
 * !!IMPORTANT!! 
 * In production connect to the database using an user that 
 * only has the Privileges required by the session aKa NOT root!!
*/
 class Config
 {
    /**
     * MVC Framework Version
     */
    const FRAMEWORK_VERSION = '0.0.0.2';

    /**
     * App Version
     */
    const APP_VERSION = '0.0.0.1';

    /**
     * Database Host
     */
    const DB_HOST = 'localhost';
    /**
     * Database Name
     */
    const DB_NAME = "auth";

    /**
     * Database User
     */
    const DB_USER = 'root';

    /**
     * Database Password
     */
    const  DB_PASSWORD = "mysql"; 

    /**
     * Secret key  for hashing
     * 
     */
    const SECRET_KEY = "secret";

    /**
     * Show or hide errors messages on screen 
     * @var boolean
     */   
    const SHOW_ERRORS = true;
 }