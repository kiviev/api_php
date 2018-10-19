<?php
namespace Wal;
require __DIR__ . "/../bootstrap.php";

$r =  str_replace( basename(__FILE__) ,'' ,  $_SERVER["SCRIPT_NAME"]);
$base_route = substr($r , 0 , strlen($r) -1);
$PUBLIC_PATH = __DIR__ . '/';

require __DIR__ . "/../src/Routes.php";
