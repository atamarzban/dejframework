<?php

//Main Entry Point for Requests
//Bootstraps and Runs the App

//set include path to project root. This allows to autoload anything easily.
set_include_path(dirname(__DIR__));

//Lightweight PSR-0 Autoloader
 function autoload( $class ) {
    preg_match('/^(.+)?([^\\\\]+)$/U', ltrim( $class, '\\' ), $match);
    require str_replace( '\\', '/', $match[ 1 ] )
        . str_replace( [ '\\', '_' ], '/', $match[ 2 ] )
        . '.php';
}

//Register the Autoloader
spl_autoload_register('autoload');

//Load Routes
require "app/routes.php";

?>
