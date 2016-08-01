<?php
namespace dej;
use \dej\App;
use \dej\mvc\Controller;
use \dej\mvc\controller\Dispatcher as ControllerDispatcher;

/**
* Route Class
*/
class Route
{

	public static function set($method = null, $uri = null, $destination = null){

		if ($destination == null ||
			$uri == null ||
			$method == null)
			throw new \Exception("Please Provide Complete Specification in Route \"$uri\" method $method");

			if(!empty(App::Config()->uri_prefix)){
				$prefix = App::Config()->uri_prefix;
				$uri = $prefix . $uri;
			}


		if (App::Request()->method != $method ||
			App::Request()->uri != $uri) return false;

		if ($destination instanceof \Closure) {
			$destination();

		}
		 else{
		 	$controllerAction = explode('@', $destination);
		 	if (count($controllerAction) != 2) throw new \Exception("ControllerName@ActionName is not provided correctly in Route {$method}:{$uri}");
		 	$controllerName = "\app\controllers\\" . $controllerAction[0];
		 	$actionName = $controllerAction[1];
             //Call The Controller Dispatcher
            ControllerDispatcher::handle($controllerName, $actionName);
		 }

	}

}

?>
