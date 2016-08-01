<?php
namespace dej\mvc\controller;
use \dej\App;

class Dispatcher {

    public static function handle($controllerName = null, $actionName = null)
    {
        self::preDispatch($controllerName, $actionName);
    }

    public static function preDispatch($controllerName = null, $actionName = null)
    {
        //Do Pre Dispatch Operations
        self::dispatch($controllerName, $actionName);
    }

    public static function dispatch($controllerName = null, $actionName = null)
    {
        $return = $controllerName::$actionName();
        self::postDispatch($return);
    }

    public static function postDispatch($return = null)
    {

        if ($return instanceof \dej\http\Response){

            $return->toOutput();

        }

        elseif ($return instanceof \dej\mvc\View){
            App::Response()->view($return)->toOutput();
        }

        elseif($return instanceof \dej\mvc\Model)
        {
            App::Response()->body($return)->toOutput();
        }

        elseif(is_array($return) || is_object($return))
        {
            App::Response()->body(json_encode($return))->toOutput();
        }

        elseif (is_string($return))
        {
            App::Response()->body($return)->toOutput();
        }


        //Other Post Dispatch Operations

        exit();

    }
}
