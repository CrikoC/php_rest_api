<?php
if(URL == "users") {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $user = User::find_by_id( $id);
        if(!empty($user)) {
            echo json_encode(print_r($user));
            http_response_code(200);
        } else {
            echo "User not found";
            http_response_code(405);
        }
    } else {
        echo json_encode(print_r(User::find_all()));
    }
}  else if(URL == "posts") {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $post = Post::find_by_id( $id);
        if(!empty($post)) {
            echo json_encode(print_r($post));
            http_response_code(200);
        } else {
            echo "Post not found";
            http_response_code(405);
        }
    } else {
        echo json_encode(print_r(Post::find_all()));
    }
}