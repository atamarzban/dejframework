<?php

namespace dej;


class Session extends \dej\common\Singleton
{
    public $flash;
    public $expired = false;

    public function __construct()
    {
        session_start();

        
        if($this->isSaved('dej_session_lifetime') &&
            $this->isSaved('dej_session_last_access')) {

            $lifeTime = intval($this->get('dej_session_lifetime'));
            $lastAccess = intval($this->get('dej_session_last_access'));
            if ((time() - $lastAccess) > $lifeTime) {
                $this->destroy();
                $this->expired = true;
            }
        }
        


        if ($this->isSaved('dej_flash'))
        {
            $this->flash = $this->get('dej_flash');
            $this->delete('dej_flash');
        }

        $this->lastAccess(time());
    }

    public function save($keyValues = [])
    {
        if (empty($keyValues)) return false;
        foreach ($keyValues as $key => $value)
        {
            if (!$this->saveHandler($key, $value)) return false;
        }
        return true;
    }

    private function saveHandler($key, $value)
    {
        if(empty($key)) return false;
        $_SESSION[$key] = $value;
        return true;
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

    public function isSaved($key = null)
    {
        if($key == null) return false;
        return isset($_SESSION[$key]);
    }

    public function flash($keyValues = [])
    {
        $alreadyFlashed = $this->get('dej_flash');
        $alreadyFlashed = (!empty($alreadyFlashed))? $alreadyFlashed:[];
        $this->save(['dej_flash' => array_merge($keyValues, $alreadyFlashed)]);
    }

    public function getFlash($key)
    {
        if(isset($this->flash[$key])) return $this->flash[$key];
        else return null;
    }

    public function lifeTime($lifeTime = null)
    {
        $this->save(['dej_session_lifetime' => $lifeTime]);
    }

    public function lastAccess($time = null)
    {
        $this->save(['dej_session_last_access' => $time]);
    }

}