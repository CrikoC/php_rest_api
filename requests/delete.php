<?php
switch (URL) {
    case "auth":
        if(isset($_GET['token'])) {
            $token = $_GET['token'];
            $user = User::find_single_by_column('token', $token);
            $user->token = "";
            $user->update();

            setcookie("LC", "", time()-3600);

            echo '{ "Status" : "Logged out" }';
            http_response_code(200);
        } else {
            echo '{ "Error" : "Mal-formed request" }';
            http_response_code(405);
        }
        break;
    case "users":
        if(isset($_COOKIE['LC'])) {
            $login_cookie = $_COOKIE['LC'];

            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $user = User::find_by_id($id);

                if($user->token == $login_cookie) {
                    setcookie("LC", "", time()-3600);
                }
                $user->delete();
                echo '{ "Status" : "User deleted" }';
                http_response_code(200);
            } else {
                echo '{ "Error" : "Mal-formed request" }';
                http_response_code(405);
            }
        } else {
            echo '{ "Error" : Not authorized" }';
            http_response_code(401);
        }
        break;
    case "posts":
        if(isset($_COOKIE['LC'])) {
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
        } else {
            echo '{ "Error" : Not authorized" }';
            http_response_code(401);
        }
        break;
}