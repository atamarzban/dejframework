<?php

//Main Entry Point for Requests
//Bootstraps and Runs the App

//set include path to project root. This allows to autoload anything easily.
set_include_path(dirname(__DIR__));

//Composer Autoloader
require 'vendor/autoload.php';

use \dej\App;
App::Config()->root_dir = dirname(__DIR__);


//Load Routes
require "app/routes.php";

?>
