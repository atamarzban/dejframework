<?php
namespace app\controllers;
use \dej\App;
use \app\models\User;


class IndexController extends \dej\mvc\Controller
{

    function __construct()
    {

    }

    public static function index()
    {

        //return App::View('index', ["message" => "Welcome To dejframework!"]);

        $users = User::count()->where('city', '=', 'Sari')->getInt();

        var_dump($users);

    }

}
?>
