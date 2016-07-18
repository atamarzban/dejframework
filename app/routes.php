<?php

use \dej\Route;
use \dej\App;

/*
* Set the routes of your application here.
*/




Route::set("GET", "/", "IndexController@index");


//if no routes caught, give a 404 response.
echo "404: Not Found";
exit();
?>
