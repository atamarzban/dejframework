<?php

namespace dej;

/**
* Request Class
*/
class Request extends \dej\common\Singleton
{

	public $headers;
	public $host;
	public $uri;
	public $isAjax;

	protected function __construct()
	{
		$this->headers = getallheaders();
		$this->host = $_SERVER["HTTP_HOST"];
		$this->uri = $this->getRouteFromUri($_SERVER["REQUEST_URI"]);
		$this->method = $_SERVER["REQUEST_METHOD"];
	}

	private function getRouteFromUri($uri){
		if(strpos($uri, '?') == false){
			return $uri;
		} else {
		return substr($uri, 0, strpos($uri, '?'));
		}
	}

	public static function isAjax(){

    return isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

	}

	public function get($name = null, $filter = null)
	{
		if($name == null) return null;

		$parameter = $_GET[$name];

		if (empty($filter)) {
			return $parameter;
		} else {
			# code...
		}


	}

	public function post($name = null, $filter = null)
	{
		if($name == null) return null;

		$parameter = $_POST[$name];

		if (empty($filter)) {
			return $parameter;
		} else {
			# code...
		}

	}

	public function filterParameter($parameter = null, $filters = null)
	{

	}

}

?>
