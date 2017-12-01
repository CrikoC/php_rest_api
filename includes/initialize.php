<?php
// Define the core paths
defined("METHOD") ? null : define("METHOD", $_SERVER["REQUEST_METHOD"]);
defined("URL") ? null : define("URL", $_GET["url"]);
defined("INPUT") ? null : define("INPUT", file_get_contents("php://input"));
defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
defined("SERVER_PATH") ? null : define("SERVER_PATH", $_SERVER["DOCUMENT_ROOT"].DS."php_rest_api");

require_once(SERVER_PATH.DS.'includes'.DS.'database.php');
require_once(SERVER_PATH.DS.'includes'.DS.'database_object.php');
require_once(SERVER_PATH.DS.'includes'.DS.'objects'.DS.'users.php');
require_once(SERVER_PATH.DS.'includes'.DS.'objects'.DS.'posts.php');
require_once(SERVER_PATH.DS.'vendor'.DS.'autoload.php');
