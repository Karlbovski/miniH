<?php

namespace App\Controllers;

/**
 * Authenticated base class/controller
 *
 */
abstract class Authenticated extends \Core\Controller
{
    /**
     * Require the user to be authenticated before ggivingg access to all methods in thhe controller
     *
     * @return void
     */
    protected function before(){
        $this->requireLogin();
    }
}