<?php
namespace Api\Config;


class Env extends Config
{
	const TEST_HOST_CONFIG = 'http://localhost/';
	const DB_CONNECTION = [
			        "host" => "127.0.0.1",
			        "dbname" => "your_DB_name",
			        "username" => "your_user",
			        "password" => "your_password",

				];
	const DEBUG = 1;
}
