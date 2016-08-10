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
	protected static $dbFields = [
        "username" => "username",
		"password" => "password",
		"id" => "id",
    ];

	protected static $modelName = "User";

	protected static $validationRules = ["username" => "required|string|min:5|max:20",
										"password" => "required|string|min:5|max:255"];

	protected static $rememberKey = 'logged_in_user';

	public $username;
	protected $password;

    public function setPassword($password = null)
    {
        if ($password == null) return false;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this->password;
    }

    public function verifyPassword($password = null)
    {
        if($password == null) return false;
        return password_verify($password, $this->password);
    }

    public function register()
    {
        return $this->create();
    }

    public function login($password = null)
    {
        if ($password == null) return false;
        if (!$this->verifyPassword($password)) return false;
        return $this->remember(static::$rememberKey);
    }

    public static function isLoggedIn()
    {
        return self::isRemembered(self::$rememberKey);
    }

    public static function getLoggedIn()
    {
        return self::retrieve(self::$rememberKey);
    }
    
    public function logout()
    {
        self::forget(static::$rememberKey);
    }
}

?>
