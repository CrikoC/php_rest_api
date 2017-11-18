<?php
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
if (URL == "users") {
    $postBody = file_get_contents("php://input");
    $postBody = json_decode($postBody);

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
}
if (URL == "posts") {
    $postBody = file_get_contents("php://input");
    $postBody = json_decode($postBody);

    $post = new Post();
    $post->title = $postBody[0]->title;
    $post->body = $postBody[1]->body;

    if ($post->create()) {
        echo '{ "Status" : "Post added" }';
        http_response_code(200);
    } else {
        echo '{ "Error" : "Mal-formed request" }';
        http_response_code(405);
    }
}