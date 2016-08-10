<?php

use \dej\Route;
use \dej\App;

/*
* Set the routes of your application here.
*/




Route::set("GET", "/", "IndexController@index");

Route::set("POST", "/register", "IndexController@register");

Route::set("POST", "/login", "IndexController@login");

Route::set('GET', '/logout', 'IndexController@logout');

Route::set('GET', '/dashboard', 'IndexController@dashboard');



//if no routes caught, give a 404 response.
App::Response()->code(404)->header("HTTP/1.0 404 Not Found")->body("404 Not Found")->toOutput();

exit();

?>
