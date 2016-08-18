<?php

namespace dej\mvc;
use \dej\App;
use Philo\Blade\Blade;

/**
* 
*/
class View
{
	private $viewPath;
	private $data = [];
	private $errors = [];
	private $messages = [];

	function __construct($viewPath = null, $data = null)
	{
		if($viewPath == null) throw new \Exception("Please Provide Correct Parameters in View(viewPath, data)");
		$this->viewPath = $viewPath;
		$this->data = $data;
		$this->errors = App::Session()->getFlash('errors');
		$this->messages = App::Session()->getFlash('messages');
		return $this;
	}

    public function withErrors($errors = [])
    {
        $this->errors = array_merge($errors, $this->errors);
    }

	public function withMessages($messages = [])
	{
		$this->messages = array_merge($messages, $this->messages);
	}

	public function paste($partialPath = null){
		$data = (object) $this->data;
		$errors = (object) $this->errors;
		$messages = (object) $this->messages;
        if($partialPath == null) return false;
        include "app/views/{$partialPath}.phtml";
    }

    private function errors($key)
    {
        if (isset($this->errors[$key])){
            if (is_array($this->errors[$key])) return implode(', ', $this->errors[$key]);
            else return $this->errors[$key];
        }
        else return null;
    }

	private function messages($key)
	{
		if (isset($this->messages[$key])){
			if (is_array($this->messages[$key])) return implode(', ', $this->messages[$key]);
			else return $this->messages[$key];
		}
		else return null;
	}

	public function render()
	{
		$rootDir = App::Config()->root_dir;
		if (file_exists($rootDir . "/app/views/{$this->viewPath}.phtml"))
		{
			$this->renderPhtml();
		}
		elseif (file_exists($rootDir . "/app/views/{$this->viewPath}.blade.php")){
			$this->renderBlade();
		} else throw new \Exception("View {$this->viewPath} Not Found!");
	}

	public function renderPhtml()
	{
		$data = (object) $this->data;
		$errors = (object) $this->errors;
		$messages = (object) $this->messages;
		require "app/views/{$this->viewPath}.phtml";
	}

	public function renderBlade()
	{
		$rootDir = App::Config()->root_dir;
		$views = $rootDir . '/app/views';
		$cache = $rootDir . '/app/views/cache';
		$viewData = [
			'errors' => $this->errors,
			'messages' => $this->messages
		];

		$viewData = array_merge($viewData, $this->data);
		
		$blade = new Blade($views, $cache);
		echo $blade->view()->make($this->viewPath, $viewData)->render();
	}
}

?>