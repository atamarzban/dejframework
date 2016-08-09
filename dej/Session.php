<?php

namespace dej;


class Session extends \dej\common\Singleton
{
    public function __construct()
    {
        session_start();
    }

    public function save($keyValues = [])
    {
        if (empty($keyValues)) return false;
        foreach ($keyValues as $key => $value)
        {
            $this->saveHandler($key, $value);
        }
    }

    private function saveHandler($key, $value)
    {
        if(empty($key)) return false;
        $_SESSION[$key] = $value;
    }

    public function get($key = null)
    {
        if (empty($key)) return false;
        return $this->getHandler($key);
    }

    private function getHandler($key)
    {
        if (!isset($_SESSION[$key])) return false;
        return $_SESSION[$key];
    }

    public function regenerateId()
    {
        return $this->regenerateIdHandler();
    }

    private function regenerateIdHandler()
    {
        return session_regenerate_id();
    }

    public function all()
    {
        return $this->allHandler();
    }

    private function allHandler()
    {
        return $_SESSION;
    }

    public function destroy()
    {
        return $this->destroyHandler();
    }

    private function destroyHandler()
    {
        return session_destroy();
    }

    public function delete($key = null)
    {
        if (empty($key)) return false;
        $this->deleteHandler($key);
    }

    private function deleteHandler($key = null)
    {
        if (empty($key)) return false;
        unset($_SESSION[$key]);
    }
}