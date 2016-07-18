<?php

namespace dej\mvc;

/**
* 
*/
class View
{
	private $viewPath;
	private $data = array();

	function __construct($viewPath = null, $data = null)
	{
		if($viewPath == null || $data == null) throw new \Exception("Please Provide Correct Parameters in View(viewPath, data)");
		$this->viewPath = $viewPath;
		$this->data = $data;
		return $this;
	}

	public function render()
	{
		$data = (object) $this->data;
		require "app/views/{$this->viewPath}.phtml";
	}


}

?>