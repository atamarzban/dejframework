<?php

namespace dej\mvc;
use \dej\App;

/**
* The ORM Model Class
*/
class Model
{
	public $id;
	protected static $dbTable;
	protected static $primaryKey = [];
	protected static $dbFields = [];
	protected static $modelName;
    protected static $validationRules = [];

    function __construct()
	{

	}

	public function create()
	{
		$insertData = [];
		foreach (static::$dbFields as $field => $property) {
			$insertData[$field] = $this->$property;
		}

		return App::Query()->insertInto(static::$dbTable)->values($insertData)->do();
	}

	public function update()
	{
		$updateData = [];
		foreach (static::$dbFields as $field => $property) {
			$updateData[$field] = $this->$property;
		}

		$pKeyField = array_keys(static::$primaryKey)[0];
		$pKeyProp = static::$primaryKey[$pKeyField];

		return App::Query()->update(static::$dbTable)->set($updateData)->where($pKeyField, '=', $this->$pKeyProp)->do();

	}

	public function delete()
	{
        $pKeyField = array_keys(static::$primaryKey)[0];
        $pKeyProp = static::$primaryKey[$pKeyField];
		return App::Query()->deleteFrom(static::$dbTable)->where($pKeyField, '=', $this->$pKeyProp)->do();
	}


	/*
	* Static Methods.
	*/

	public static function findByPKey($pKey = null)
	{
		$pKeyField = array_keys(static::$primaryKey)[0];
		return App::Query(static::$modelName)->select()->from(static::$dbTable)->where($pKeyField, '=', $pKey)->getOne();
	}

	public static function find()
	{
		return App::Query(static::$modelName)->select()->from(static::$dbTable);
	}

	public static function getAll()
	{
		return App::Query(static::$modelName)->select()->from(static::$dbTable)->getAll();
	}

	public static function wipe(){
		return App::Query()->deleteFrom(static::$dbTable);
	}

	public static function countAll(){
		return App::Query()->count(static::$dbTable)->getInt();
	}

	public static function count()
	{
		return App::Query()->count(static::$dbTable);
	}



	public function castToThis($source = null, $propertiesMap = null)
	{
		if($source != null){
			if($propertiesMap == null) $propertiesMap = static::$dbFields;
			foreach ($propertiesMap as $sourceProperty => $thisProperty) {
			$this->$thisProperty = $source->$sourceProperty;

			}
		}

	}

    public function validate()
    {
        if (empty(static::$validationRules)) throw new \Exception("Please Provide ".static::$modelName." with validationRules");
        return App::Validator()->validate($this, static::$validationRules);
    }

	public function isValid()
    {
        if (empty($this->validate())) return true;
        else return false;
    }

	public function __toString()
	{
		return json_encode($this);
	}


}

?>
