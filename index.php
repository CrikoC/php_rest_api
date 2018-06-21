<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "includes/initialize.php";

$api = new Api;

switch (METHOD) {
    case "GET":
        include "requests/get.php";
        break;
    case "POST":
        include "requests/post.php";
        break;
    case "PUT":
        include "requests/put.php";
        break;
    case "DELETE":
        include "requests/delete.php";
        break;
    case "OPTIONS":
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    default:
        http_response_code(405);
}
