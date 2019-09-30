<?php

// Display all Errors
ini_set('display_errors', 1); //Устанавливает значение настройки конфигурации
error_reporting(E_ALL);

// Sessions
session_start();

ini_set("pcre.jit", 0);
define('ROOT_PATH', dirname(__FILE__));

use app\core\Router;
use app\db_workers\Users;

spl_autoload_register(function ($class) {
	$path = str_replace('\\', '/', $class.".php");
	if (file_exists($path)) {
	  require $path;
	}
});

require ROOT_PATH.'/vendor/autoload.php';

if (isset($_SESSION["user_id"]) && !Users::userIdExist($_SESSION["user_id"])) {
	header("location: /logout");
} else if (isset($_SESSION["user_id"])) {
	define("USER_LOGGED", true);
} else {
	define("USER_LOGGED", false);
}

$router = new Router;

$router->match();
$router->run();

