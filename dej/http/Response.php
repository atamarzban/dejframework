<?php

namespace dej\http;
use \dej\App;

class Response
{
    private $headers = [];
    private $code;
    private $view;
    private $body;

    public function __construct()
    {

    }

    public function code($code = null)
    {
        $this->code = (int) $code;
        return $this;
    }

    public function header($header = null)
    {
        if($header != null) array_push($this->headers, $header);
        return $this;
    }

    public function view($view = null)
    {
        $this->view = $view;
        return $this;
    }

    public function body($body = null)
    {
        $this->body = (string) $body;
        return $this;
    }

    public function redirect($location)
    {
        $this->header("Location: {$location}");
        return $this;
    }

    public function withErrors($errors)
    {
        App::Session()->flash(['errors' => $errors]);
        return $this;
    }

    public function withMessages($messages)
    {
        App::Session()->flash(['messages' => $messages]);
        return $this;
    }

    public function toOutput()
    {
        
        if(isset($this->code)) http_response_code($this->code);

        foreach ($this->headers as $header)
        {
            header($header, true);
        }

        if(isset($this->view) && $this->view instanceof \dej\mvc\View)
        {
            $this->view->render();
            return true;
        }

        if(isset($this->body)){
            echo $this->body;
            return true;
        }
        
        

    }

}
