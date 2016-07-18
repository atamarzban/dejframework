<?php

namespace app\models;

/**
* User Class
*/
class User extends \dej\mvc\Model
{
	//TODO Optional Auth
	protected static $dbTable = "users";
	protected static $dbFields = ["username" => "username",
																"password" => "password",
																	"city" => "city",
																"id" => "id"];
	protected static $modelName = "User";

	public $username;
	public $password;
	public $city;

	function __construct()
	{

	}
}

?>
