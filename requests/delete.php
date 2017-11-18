<?php
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