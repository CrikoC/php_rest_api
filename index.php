<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "includes/initialize.php";

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
    default:
        http_response_code(405);
}
