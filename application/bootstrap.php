
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
require 'vendor/autoload.php';


use Api\Config\Env;
ini_set('display_errors', Env::DEBUG);