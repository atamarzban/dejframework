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
       $errors = App::Request()->validate(['email' => 'required|string|email',
                                    'password' => 'required|string|min:10|max:100']);

        var_dump($errors);

    }

}
?>
