<?php
if(URL == "profile") {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $user = User::find_by_id($id);

        if (!empty($user)) {
            if($user->token == $_COOKIE['LC']) {
                echo json_encode(print_r($user));
                http_response_code(200);
            } else {
                echo '{ "Error" : "Unauthorized Access" }';
                http_response_code(401);
            }
        } else {
            echo '{ "Error" : "User not found" }';
            http_response_code(405);
        }
    }
}  else if(URL == "posts") {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $post = Post::find_by_id( $id);
        if(!empty($post)) {
            echo json_encode(print_r($post));
            http_response_code(200);
        } else {
            echo '{ "Error" : "Post not found" }';
            http_response_code(405);
        }
    } else {
        echo json_encode(print_r(Post::find_all()));
    }
}