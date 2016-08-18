<?php
namespace app\controllers;
use \dej\App;
use \app\models\User;


class AuthController extends \dej\mvc\Controller
{

    function __construct()
    {

    }

    public static function showRegisterForm()
    {
        return App::View('register');
    }
    
    public static function register()
    {
        $params = App::Request()->all();

        $user = new User($params);

        $user->setPassword($params['password']);

        $errors = $user->validate();
        
        if (!empty($errors)) return App::Response()->redirect('/register')->withErrors($errors);

        if (User::count()->where('username', '=', $params['username'])->getInt() != 0)
            return App::Response()->redirect('/register')->withErrors(['register' => 'user already exists']);

        if($user->create() == 1) return App::Response()->redirect('/')->withErrors(['headerError' => 'register successful!']);
        else return App::Response()->redirect('/')->withErrors(['headerError' => 'register unsuccessful!']);

    }

    public static function showLoginForm()
    {
        return App::View('login');
    }

    public static function login()
    {
        $params = App::Request()->all();
        
        $user = User::find()->where('username', '=', $params['username'])->getOne();
        
        if (empty($user)) 
            return App::Response()->redirect('/login')
                ->withErrors(['login' => 'user does not exist']);  
        
        if($user->login($params['password'])) return App::Response()->redirect('/dashboard');
        
        return App::Response()->redirect('/login')
            ->withErrors(['login' => 'login unsuccessful! maybe password is wrong.']);
    }

    public static function logout()
    {
        User::logout();
        return App::Response()->redirect('/')->withMessages(['headerMessage' => 'logged out!']);
    }
}
?>
