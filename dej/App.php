<?php

namespace dej;

/**
* The Application Class, Enables Access to services and acts as a Service Provider. Along with Dependency Injection.
*/
class App
{

	public static function __callStatic($name, $arguments)
    {
    	//set the Service Classes here And how you want them to be instantiated.
        switch ($name) {

        	case 'Config':
        		return \dej\Config::getInstance()->config;
        		break;

        	case 'Request':
        		return \dej\Request::getInstance();
        		break;

            case 'Connection':
                return \dej\db\Connection::getInstance();
                break;

            case 'Query':
                return new \dej\db\Query(\dej\db\Connection::getInstance(), $arguments[0]);
                break;

            case 'View':
                return new \dej\mvc\View($arguments[0], $arguments[1]);
                break;

        	default:
        		throw new \Exception("Class not found by App Service Provider");

        		break;
        }
    }

}

?>
