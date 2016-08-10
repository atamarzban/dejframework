<?php

namespace dej\mvc;
use \dej\App;

/**
* 
*/
class View
{
	private $viewPath;
	private $data = array();
	private $errors = [];

	function __construct($viewPath = null, $data = null)
	{
		if($viewPath == null || $data == null) throw new \Exception("Please Provide Correct Parameters in View(viewPath, data)");
		$this->viewPath = $viewPath;
		$this->data = $data;
		$this->errors = App::Session()->getFlash('errors');
		return $this;
	}

    public function withErrors($errors = [])
    {
        $this->errors = array_merge($errors, $this->errors);
    }

    private function errors($key)
    {
        if (isset($this->errors[$key])){
            if (is_array($this->errors[$key])) return implode(', ', $this->errors[$key]);
            else return $this->errors[$key];
        }
        else return null;
    }

	public function render()
	{
		$data = (object) $this->data;
		require "app/views/{$this->viewPath}.phtml";
	}


}

?>