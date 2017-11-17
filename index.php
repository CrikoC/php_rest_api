<?php
include "includes/initialize.php";

if(METHOD == "GET") {
    if(URL == "auth") {

    } else if(URL == "users") {
        echo json_encode(print_r(User::find_all()));
    }  else if(URL == "posts") {
        echo json_encode(print_r(Post::find_all()));
    }
    http_response_code(200); // END GET
} else if(METHOD == "POST") {
    if(URL == "auth") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);

        $user = User::find_single_by_column("username", $postBody->username);

        if(password_verify($postBody->password, $user->password)) {
            $cstrong = True;

            $login = new Login();
            $login->token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $login->user_id = $user->id;
            $login->create();

            echo '{ "Token": "$login->token" }';
        } else {
            echo "Incorrect username or password";
            http_response_code(401);
        } //END if(password_verify($postBody->password, $user->password))
    } //END if(URL == "auth")
    //http_response_code(200); // END POST
} else {
    http_response_code(405);
} // END if(METHOD)