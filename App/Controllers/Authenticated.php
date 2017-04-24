<?php

namespace App\Controllers;

/**
 * Authenticated base class/controller
 *
 */
abstract class Authenticated extends \Core\Controller
{
    protected function before(){
        $this->requireLogin();
    }
}