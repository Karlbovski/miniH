<?php

namespace App\Controllers;

use \Core\View;

/**
 * Restricted Controller ( example )
 *
 * If we have any Controller that we  want to restrict to Logged-in users only,
 * we have to extends the Authenticated abstract class.
 * 
 * PHP version 7.0
 */
class Restricted extends Authenticated
{
    /**
     * Restricted Page index
     */
    public function indexAction(){
        View::renderTemplate('Restricted/index.html');
    }
}