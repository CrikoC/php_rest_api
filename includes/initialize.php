<?php
/* Constants */
define("METHOD",       $_SERVER["REQUEST_METHOD"]);
define("URL",          $_GET["url"]);
define("INPUT",        file_get_contents("php://input"));        
define("DS",           DIRECTORY_SEPARATOR);
define("SERVER_PATH",  $_SERVER["DOCUMENT_ROOT"].DS."php_rest_api");

/* Security */
define("SECRET_KEY", "ar@nd0ms3tofstr1ngs");

/* Data Types */
define("BOOLEAN",   "1");
define("INTEGER",   "2");
define("STRING",    "3");

/* Error Codes */
define("REQUEST_METHOD_NOT_VALID",          100);
define("REQUEST_CONTENT_TYPE_NOT_VALID",    101);
define("REQUESTNOT_VALID",                  102);
define("VALIDATE_PARAMETER_DATATYPE",       104);
define("API_DOES_NOT_EXIST",                107);
define("INVALID_USER_PASS",                 108);

define("SUCCESS_RESPONSE",                  200);
define("NO_CONTENT",                        204);      

/* Server Errors */
define("AUTHORIZATION_HEADER_NOT_FOUND",    300);
define("ACCESS_TOKEN_ERRORS",               301);

define("UNAUTHORIZED",                      401);
define("NOT_FOUND",                         404);


/* Include JWT */
require(SERVER_PATH.DS."vendor".DS."autoload.php");

/* Include Classes */
require_once(SERVER_PATH.DS.'includes'.DS.'database.php');
require_once(SERVER_PATH.DS.'includes'.DS.'database_object.php');
require_once(SERVER_PATH.DS.'includes'.DS.'users.php');
require_once(SERVER_PATH.DS.'includes'.DS.'posts.php');
require_once(SERVER_PATH.DS.'includes'.DS.'rest.php');
require_once(SERVER_PATH.DS.'includes'.DS.'api.php');

