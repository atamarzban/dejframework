<?php

namespace dej\db;
use \dej\Config;
use \dej\App;
use \PDO;

/**
* Database Connection Adapter
*/
class Connection extends \dej\common\Singleton
{
	private $connection;
	private $dbType;
	private $dbHost;
	private $dbName;
	private $dbUsername;
	private $dbPassword;
	
	function __construct()
	{
		$this->dbType = App::Config()->db_type;
		$this->dbHost = App::Config()->db_host;
		$this->dbName = App::Config()->db_name;
		$this->dbUsername = App::Config()->db_username;
		$this->dbPassword = App::Config()->db_password;
		
		switch ($this->dbType) {

			case 'mysql':
			$this->connectToMysql();
			break;

			default:
			throw new \Exception("db_type in config file is unsupported.");		
			break;

		}
		
	}

	private function connectToMysql()
	{	
							
		//Create Connection
		$this->connection = 
		new PDO("mysql:host={$this->dbHost};dbname={$this->dbName};charset=utf8",
			$this->dbUsername, $this->dbPassword);

   		// set the PDO error mode to exception and Default Fetch Mode to Object and Prepared Statement Emulation to FALSE
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		
	}


	public function executeQuery($query = null, $data = null, $fetchAll = true)
	{
		if ($query == null) throw new \Exception("Provide executeQuery(query, data) with the right parameters");		
		$statement = $this->connection->prepare($query);
		$statement->execute($data);
		if ($fetchAll == true) return $statement->fetchAll();	
		return $statement->fetch();
	}

	public function executeNonQuery($query = null, $data = null)
	{
		if ($query == null) throw new \Exception("Provide executenonQuery(query, data) with the right parameters");		
		$statement = $this->connection->prepare($query);
		$statement->execute($data);
		return $statement->rowCount();
	}

}



?>

