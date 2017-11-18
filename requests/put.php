<?php
if(URL == "users") {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $user = User::find_by_id($id);
        if (!empty($user)) {
            $postBody = file_get_contents("php://input");
            $postBody = json_decode($postBody);
            $user->username = $postBody[0]->username;
            $user->password = password_hash($postBody[1]->password, PASSWORD_BCRYPT, ['cost'=>10]);

            echo "User updated";
            http_response_code(200);
        }
    } else {
        echo "User not found";
        http_response_code(405);
    }
} else if(URL == "posts") {
    if(isset($_COOKIE['LC'])) {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $post = Post::find_by_id($id);
            if (!empty($post)) {
                $postBody = file_get_contents("php://input");
                $postBody = json_decode($postBody);
                $post->title = $postBody[0]->title;
                $post->body = $postBody[1]->body;

                echo "Post updated";
                http_response_code(200);
            }
        } else {
            echo "Post not found";
            http_response_code(405);
        }
    } else {
        echo '{ "Error" : Not authorized" }';
        http_response_code(401);
    }
}