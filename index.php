<?php
include "includes/initialize.php";

if(METHOD == "GET") {
    if(URL == "users") {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            echo json_encode(print_r(User::find_by_id( $id)));
        } else {
            echo json_encode(print_r(User::find_all()));
        }
    }  else if(URL == "posts") {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            echo json_encode(print_r(Post::find_by_id( $id)));
        } else {
            echo json_encode(print_r(Post::find_all()));
        }
    }
    http_response_code(200); // END GET
} else if(METHOD == "POST") {
    if (URL == "auth") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);

        $user = User::find_single_by_column("username", $postBody[0]->username);

        if (password_verify($postBody[1]->password, $user->password)) {
            $cstrong = True;

            $login = new Login();
            $login->token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $login->user_id = $user->id;
            $login->create();

            echo '{ "Token": "' . $login->token . '" }';
        } else {
            echo "Incorrect username or password";
            http_response_code(401);
        }
    }
    if(URL == "users") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);

        $user = new User();
        $user->username = $postBody[0]->username;
        $user->password = password_hash($postBody[1]->password, PASSWORD_BCRYPT, ['cost'=>10]);

        if($user->create()) {
            echo '{ "Status" : "User added" }';
            http_response_code(200);
        } else {
            echo '{ "Error" : "Mal-formed request" }';
            http_response_code(405);
        }
    }
    if(URL == "posts") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);

        $post = new Post();
        $post->title = $postBody[0]->title;
        $post->body = $postBody[1]->body;

        if($post->create()) {
            echo '{ "Status" : "Post added" }';
            http_response_code(200);
        } else {
            echo '{ "Error" : "Mal-formed request" }';
            http_response_code(405);
        }
    }
} else if(METHOD == "DELETE") {
    if(URL == "auth") {
        if(isset($_GET['token'])) {
            $token = $_GET['token'];
            $login = Login::find_single_by_column('token', $token);
            $login->delete();
            echo '{ "Status" : "Logged out" }';
            http_response_code(200);
        } else {
            echo '{ "Error" : "Mal-formed request" }';
            http_response_code(405);
        }
    } else if(URL == "users") {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $user = User::find_by_id($id);
            $user->delete();
            echo '{ "Status" : "User deleted" }';
            http_response_code(200);
        } else {
            echo '{ "Error" : "Mal-formed request" }';
            http_response_code(405);
        }
    } else if(URL == "posts") {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $post = Post::find_by_id($id);
            $post->delete();
            echo '{ "Status" : "Post deleted" }';
            http_response_code(200);
        } else {
            echo '{ "Error" : "Mal-formed request" }';
            http_response_code(405);
        }
    }
} else {
    http_response_code(405);
}