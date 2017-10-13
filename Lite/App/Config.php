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
    const FRAMEWORK_VERSION = '1.0.3.1 Lite';

    /**
     * App Version
     */
    const APP_VERSION = '0.0.0.0';

    /**
     * Brand Name 
     * The name of the website or website owner
     *
     * @var string
     */
    const BRAND_NAME = "BrandName";

    /**
     * Auth System
     * Enable/Disable authentication system features
     *
     * @var boolean
     */
    const AUTH_SYS_ENABLED = false;


    /**
     * Show or hide errors messages on screen 
     * @var boolean
     */   
    const SHOW_ERRORS = true;

 }