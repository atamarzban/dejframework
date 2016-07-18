<?php

namespace dej\mvc;
use \dej\App;

/**
* The ORM Model Class
*/
class Model
{
	//TODO Data Validation on Model & Request
	public $id;
	protected static $dbTable;
	protected static $dbFields = [];
	protected static $modelName;

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

		return App::Query()->update(static::$dbTable)->set($updateData)->where('id', '=', $this->id)->do();

	}

	public function delete()
	{
		return App::Query()->deleteFrom(static::$dbTable)->where('id', '=', $this->id)->do();
	}


	/*
	* Static Methods.
	*/

	public static function findById($id = null)
	{
		return App::Query(static::$modelName)->select()->from(static::$dbTable)->where('id', '=', $id)->getOne();
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


}

?>
