<?php
namespace app\controllers;
use \dej\App;
use \app\models\User;

/**
*
*/
class IndexController extends \dej\mvc\Controller
{

	function __construct()
	{

	}

	public static function index()
	{

			App::View('index', ["message" => "Welcome To dejframework!"])->render();

	}

}
?>
