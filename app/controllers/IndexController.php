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

        $result = App::Query()->select()->from('users')->getInt();


        var_dump($result);

    }

}
?>
