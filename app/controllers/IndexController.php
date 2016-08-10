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
        return App::View('index', ['message' => 'Welcome to dejframework!']);
    }

    public static function register()
    {
        $params = App::Request()->all();

        $user = new User($params);

        $user->setPassword($params['password']);

        $errors = $user->validate();
        
        if (!empty($errors)) return App::Response()->redirect('/')->withErrors($errors);

        if (User::count()->where('username', '=', $params['username'])->getInt() != 0)
            return App::Response()->redirect('/')->withErrors(['register' => 'user already exists']);

        if($user->create() == 1) return 'register successful!';
        else return 'register unsuccessful!';

    }

    public static function login()
    {
        $params = App::Request()->all();
        
        $user = User::find()->where('username', '=', $params['username'])->getOne();
        
        if (empty($user)) 
            return App::Response()->redirect('/')
                ->withErrors(['login' => 'user does not exist']);  
        
        if($user->login($params['password'])) return 'login successful!';
        
        return App::Response()->redirect('/')
            ->withErrors(['login' => 'login unsuccessful! maybe password is wrong.']);
    }

    public static function dashboard()
    {
        if (!App::Request()->user()) return 'Unauthorized!';
        return 'Hello ' .App::Request()->user()->username. '!';
    }

    public static function logout()
    {
        User::logout();
    }
}
?>
