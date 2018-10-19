<?php
namespace Api\Config;


class Config
{
	const TEST_HOST_CONFIG = 'http://localhost/';
	const DB_CONNECTION = [
			        "host" => "127.0.0.1",
			        "dbname" => "my_db",
			        "username" => "root",
			        "password" => "root",

			    ];
	const DEBUG = 0;
	const DB_CREATE_TABLES_FILE = 'Database/scripts/migrations.sql';
	const DB_INSERT_DATA_TEST_FILE = 'Database/scripts/seeders.sql';


	public static function getConfig(){
		return static::CONFIG;
	}

	public static function getDbConfig(){
		return static::DB_CONNECTION;
	}


	public function __get($name)
	   {
	      if(defined("self::$name"))
	      {
	         return constant("self::$name");

	      }
	      trigger_error ("$name  isn't defined");
	   }

}




