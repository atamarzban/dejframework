<?php

namespace dej\traits;

use \dej\App;

trait IsStateful
{
    public function remember($key = null)
    {
        return $this->saveToSession($key);
    }

    private function saveToSession($key = null)
    {
        if (empty($key)) return false;

        $pKeyField = array_keys(static::$primaryKey)[0];
        $pKeyProp = static::$primaryKey[$pKeyField];

        $dataHash = $this->hashInstance();
        $dataPkey = $this->$pKeyProp;

        App::Session()->save([
            "dej_obj_{$key}_pkey" => $dataPkey,
            "dej_obj_{$key}_datahash" => $dataHash
        ]);
        return true;
    }

    public static function retrieve($key, $checkChange = false)
    {
        return static::getFromSession($key, $checkChange);
    }

    private static function getFromSession($key, $checkChange = false)
    {
        if (empty($key)) return false;

        $modelName = "\\app\\models\\" . static::$modelName;

        $instance = $modelName::findByPKey(App::Session()->get("dej_obj_{$key}_pkey"));
        $sessionDataHash = App::Session()->get("dej_obj_{$key}_datahash");
        if($checkChange == true && $instance->hashInstance() != $sessionDataHash) return false;
        else return $instance;

    }

    public static function hasChanged($key)
    {
        $modelName = "\\app\\models\\" . static::$modelName;
        $instance = $modelName::findByPKey(App::Session()->get("dej_obj_{$key}_pkey"));
        $hashedInstance = $instance->hashInstance();
        $sessionDataHash = App::Session()->get("dej_obj_{$key}_datahash");
        if($hashedInstance != $sessionDataHash) return $instance;
        else return false;
    }

    private function hashInstance()
    {
        return md5(json_encode($this));
    }

    public function forget($key = null)
    {
        if (empty($key)) return false;
        $this->deleteFromSession($key);
    }

    private function deleteFromSession($key = null)
    {
        App::Session()->delete("dej_obj_{$key}_pkey");
        App::Session()->delete("dej_obj_{$key}_datahash");
    }
}