<?php
namespace app\controllers;
use \dej\App;
use \app\models\User;


class HomeController extends \dej\mvc\Controller
{

    function __construct()
    {

    }

    public static function showDashboard()
    {
        if (!App::Request()->user()) return 'Unauthorized!';
        return App::View('dashboard');
    }

}
?>
