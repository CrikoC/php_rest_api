<?php
// Define the core paths
defined("METHOD") ? null : define("METHOD", $_SERVER["REQUEST_METHOD"]);
defined("URL") ? null : define("URL", $_GET["url"]);
defined("INPUT") ? null : define("INPUT", file_get_contents("php://input"));
defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
defined("LIB_PATH") ? null : define("LIB_PATH", $_SERVER["DOCUMENT_ROOT"].DS."php_rest_api".DS."includes");

require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'database_object.php');
require_once(LIB_PATH.DS.'objects'.DS.'users.php');
require_once(LIB_PATH.DS.'objects'.DS.'posts.php');
