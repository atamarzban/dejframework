<?php
namespace dej;

/**
* Simple Singleton Configuration Manager
*/

class Config extends \dej\common\Singleton
{
   
	public $config;
    
    /**
     * get the stdClass representing the config JSON
     */
    
    protected function __construct()
    {
    	$json = file_get_contents('config.json', FILE_USE_INCLUDE_PATH);
		$this->config = json_decode($json);
    }
  
}
?>