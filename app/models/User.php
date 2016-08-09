<?php

namespace app\models;

/**
 * User Class
 */
class User extends \dej\mvc\Model
{
	use \dej\traits\IsStateful;

	//TODO Optional Auth
	protected static $dbTable = "users";
    protected static $primaryKey = ["id" => "id"];
	protected static $dbFields = ["username" => "username",
		"password" => "password",
		"city" => "city",
		"id" => "id"];

	protected static $modelName = "User";

	protected static $validationRules = ["username" => "required|string|min:5|max:20",
										"password" => "required|string|min:5|max:255",
										"city" => "string|max:10"];

	public $username;
	public $password;
	public $city;

	function __construct()
	{

	}

    public function login()
    {
        $this->remember('logged_in_user');
    }
    
    public function logout()
    {
        $this->forget('logged_in_user');
    }
}

?>
