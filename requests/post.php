<?php
switch (URL) {
    case "auth":
        $postBody = json_decode(INPUT);

        $user = User::find_single_by_column("username", $postBody[0]->username);

        if (password_verify($postBody[1]->password, $user->password)) {
            $cstrong = True;

            $user->token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $user->update();

            setcookie("LC", $user->token, time()+3600);

            echo '{ "Token": "' . $user->token . '" }';
        } else {
            echo "Incorrect username or password";
            http_response_code(401);
        }
        break;
    case "users":
        $postBody = json_decode(INPUT);

        $user = new User();
        $user->username = $postBody[0]->username;
        $user->password = password_hash($postBody[1]->password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        if ($user->create()) {
            echo '{ "Status" : "User added" }';
            http_response_code(200);
        } else {
            echo '{ "Error" : "Mal-formed request" }';
            http_response_code(405);
        }
        break;
    case "posts":
        if(isset($_COOKIE['LC'])) {
            $login_cookie = $_COOKIE['LC'];
            $author = User::find_single_by_column('token', $login_cookie);

            $postBody = json_decode(INPUT);

            $post = new Post();
            $post->title = $postBody[0]->title;
            $post->body = $postBody[1]->body;
            $post->author = $author->username;

            if ($post->create()) {
                echo '{ "Status" : "Post added" }';
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