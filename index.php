<?php
include "includes/initialize.php";

if(METHOD == "GET") {
    include "requests/get.php";
} else if(METHOD == "POST") {
    include "requests/post.php";
} else if(METHOD == "PUT") {
    include "requests/put.php";
} else if(METHOD == "DELETE") {
    include "requests/delete.php";
} else {
    http_response_code(405);
}