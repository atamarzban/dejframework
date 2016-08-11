<?php

namespace dej\db;
use \dej\App;

/**
* Query Generator Class.
*/
class Query
{	
	//TODO Review & Refactor
	//produced query
	private $query;
	//\dej\db\Connection Instance
	private $connection;
	//The SQL Command to perform.
	private $command;
	//Columns on which to perform the query.
	private $columns;
	//The Table on which to perform the query.
	private $table;
	//Where Conditions.
	private $where = array();
	//Order by.
	private $orderBy;
	//Param data for prepared statements
	private $data = array();
	//LIMIT
	private $limit;
	//OFFSET
	private $offset;
	//Get type: stdClass or model.
	private $getType = "stdClass";

	function __construct($connection, $getType = "stdClass")
	{
		$this->connection = $connection;

		if($getType == null) {
			$this->getType = 'stdClass';
		} else {
			$this->getType = $getType;
		}

	}


/**
* SELECT-Related methods
*/


	public function select($columns = array())
	{
		$this->command = "SELECT";

		if(empty($columns)) {

			$this->columns = "*";

		}
		else{

			$this->columns = $columns;

		}

		return $this;
	}



	public function from($tablename = null)
	{
		if($tablename == null) throw new \Exception("Specify Table Name in from(tablename)");
		$this->table = $tablename;
		return $this;
	}




	public function where($field = null, $operator = null, $value = null)
	{
		if($field == null ||
		   $operator == null) throw new \Exception("Provide Correct Parameters in where(field, operator, value)");

		if ($value == null)
		{
			$value = $operator;
			$operator = '=';
		}
		
		//make the where section of the query and push it into it's array.
		array_push($this->where, "`{$field}` {$operator} :{$field}");
		//add the value in the data to be used in prepared statement.
		$this->data["$field"] = $value;
		return $this;
	}


	public function andWhere($field = null, $operator = null, $value = null)
	{
		if($field == null ||
		   $operator == null ||
		   $value == null) throw new \Exception("Provide Correct Parameters in andWhere(field, operator, value)");

		//make the where section of the query and push it into it's array.
		array_push($this->where, "AND {$field} {$operator} :{$field}");
		//add the value in the data to be used in prepared statement.
		$this->data["$field"] = $value;
		return $this;
	}

	public function orWhere($field = null, $operator = null, $value = null)
	{
		if($field == null ||
		   $operator == null ||
		   $value == null) throw new \Exception("Provide Correct Parameters in orWhere(field, operator, value)");

		//make the where section of the query and push it into it's array.
		array_push($this->where, "OR {$field} {$operator} :{$field}");
		//add the value in the data to be used in prepared statement.
		$this->data["$field"] = $value;
		return $this;
	}

	public function orderBy($column = null, $direction = "ASC")
	{
		if($column == null) throw new \Exception("Provide Correct Parameters in orderBy(column, direction)");
		$this->orderBy = "{$column} {$direction}";
		return $this;
	}

	public function limit($value = null)
	{
		if($value != null) $this->limit = $value;
		return $this;
	}

	public function offset($value = null)
	{
		if($value != null) $this->offset = $value;
		return $this;
	}

	private function buildSelectQuery()
	{
		$query = "SELECT ";

		if($this->columns == "*") {
			$query .= "* ";
		} else {
			if(!empty($this->columns)) $query .= "`" . implode("`, `", $this->columns) . "` ";
		}

		if(!empty($this->table)) $query .= "FROM `{$this->table}` ";

		if(!empty($this->where)) $query .= "WHERE " . implode(" ", $this->where);

		if(!empty($this->orderBy)) $query .= " ORDER BY `" . $this->orderBy . "`";

		if(!empty($this->limit)) $query .= " LIMIT " . $this->limit;

		if(!empty($this->offset)) $query .= " OFFSET " . $this->offset;

		return $query;
	}





/**
* INSERT-Related methods
*/







	public function insertInto($tablename = null)
	{
		if($tablename == null) throw new \Exception("Provide Correct Parameters in insertInto($tablename, columns)");
		$this->command = "INSERT";
		$this->table = $tablename;
		return $this;
	}

	public function values($values = array())
	{
		if(empty($values)) throw new \Exception("Provide Correct Parameters in values(values)");
		$this->columns = array();
		foreach ($values as $column => $value) {
			$this->data[":{$column}"] = $value;
			array_push($this->columns, $column);
		}

		return $this;
	}

	private function buildInsertQuery()
	{
		$query = "INSERT INTO ";
		if(!empty($this->table)) $query .= " `{$this->table}` ";
		if (!empty($this->data) && !empty($this->columns)) {
			$commaSeperatedColumns = implode("`, `", $this->columns);
			$query .= "(`{$commaSeperatedColumns}`) ";
			$query .= "VALUES ";
			$commaSeperatedParams = implode(", ", array_keys($this->data));
			$query .= "({$commaSeperatedParams})";
		}
		return $query;
	}





/**
* UPDATE-Related methods
*/






	public function update($tablename = null)
	{
		if($tablename == null) throw new \Exception("Provide Correct Parameters in update(tablename)");

		$this->command = "UPDATE";
		$this->table = $tablename;

		return $this;
	}

	public function set($values = array())
	{
		if(empty($values)) throw new \Exception("Provide Correct Parameters in set(values)");
		$this->columns = array();
		foreach ($values as $column => $value) {
			array_push($this->columns, $column);
			$this->data[":dejUpdate{$column}"] = $value;
		}

		return $this;
	}

	private function buildUpdateQuery()
	{
		if(!empty($this->table)) $query = "UPDATE `{$this->table}` SET ";

		if (!empty($this->columns)) {
			$setKeyValuePairs = array();
			foreach ($this->columns as $column) {
				array_push($setKeyValuePairs, "`{$column}` = :dejUpdate{$column}");
			}
			$query .= implode(", ", $setKeyValuePairs);
		}

		if (!empty($this->where)) {
			$query .= " WHERE " . implode(" ", $this->where);
		} else {
			throw new \Exception("WARNING: No Where Clause provided in UPDATE query. This will result in updating of all records.
									Thus, dejframework will not run it. If you really want to update all records, run the Query manually.");
		}
		return $query;

	}






/**
* DELETE-Related methods
*/





	public function deleteFrom($tablename = null)
	{
		if($tablename == null) throw new \Exception("Provide Correct Parameters in deleteFrom(tablename)");

		$this->command = "DELETE";
		$this->table = $tablename;

		return $this;
	}

	private function buildDeleteQuery()
	{
		$query = "DELETE FROM `{$this->table}`";
		if (!empty($this->where)) {
			$query .= " WHERE " . implode(" ", $this->where);
		} else {
			throw new \Exception("WARNING: No Where Clause provided in DELETE query. This will result in deletion of all records.
									Thus, dejframework will not run it. If you really want to delete all records, run the Query manually.");
		}
		return $query;
	}





public function count($tablename = null)
{
	if($tablename == null) throw new \Exception("Provide Correct Parameters in count(tablename)");
	$this->command = "COUNT";
	$this->table = $tablename;
	return $this;

}

private function buildCountQuery()
{

	$query = "SELECT COUNT(*) AS count FROM `{$this->table}` ";
	if(!empty($this->where)) $query .= "WHERE " . implode(" ", $this->where);
	return $query;
}






/**
* Query Build And Execution Related methods
*/






	private function buildQuery()
	{
		switch ($this->command) {
			case 'SELECT':
				$this->query = $this->buildSelectQuery();
				break;
			case 'INSERT':
				$this->query = $this->buildInsertQuery();
				break;
			case 'UPDATE':
				$this->query = $this->buildUpdateQuery();
				break;
			case 'DELETE':
				$this->query = $this->buildDeleteQuery();
				break;
			case 'COUNT':
				$this->query = $this->buildCountQuery();
				break;
		}
	}

	public function getQuery()
	{
		if(empty($this->query)) $this->buildQuery();
		return $this->query;
	}

	public function getOne()
	{
		$this->buildQuery();
		$result = $this->connection->executeQuery($this->query, $this->data, false);
		if ($this->getType == "stdClass") {
			return $result;
		} else if (!empty($result)){
			$className = "\app\models\\" . $this->getType;
			$model = new $className();
			$model->castToThis($result);
			return $model;
		} else return false;
	}

	public function getAll()
	{
		$this->buildQuery();
		$results = $this->connection->executeQuery($this->query, $this->data);
		if ($this->getType == "stdClass") {
			return $results;
		} else if (!empty($result)){
			$className = "\app\models\\" . $this->getType;
			$resultsModels = [];
			foreach ($results as $result) {
				$model = new $className();
				$model->castToThis($result);
				array_push($resultsModels, $model);
			}
			return $resultsModels;
		} else return [];
	}

	public function getInt()
	{
		return intval($this->getOne()->count);
	}

	public function getJson($value='')
	{
		return json_encode($this->getAll(), JSON_PRETTY_PRINT);
	}

	public function execute()
	{
		$this->buildQuery();
		return $this->connection->executeNonQuery($this->query, $this->data);
	}

}

?>
