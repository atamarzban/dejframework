<?php

use \dej\Route;
use \dej\App;

/*
* Set the routes of your application here.
*/




Route::set("GET", "/", "IndexController@index");

Route::set("GET", "/register", "AuthController@showRegisterForm");

Route::set("POST", "/register", "AuthController@register");

Route::set("GET", "/login", "AuthController@showLoginForm");

Route::set("POST", "/login", "AuthController@login");

Route::set('GET', '/logout', 'AuthController@logout');

Route::set('GET', '/dashboard', 'HomeController@showDashboard');



//if no routes caught, give a 404 response.
App::Response()->code(404)->header("HTTP/1.0 404 Not Found")->body("404 Not Found")->toOutput();

exit();

?>
