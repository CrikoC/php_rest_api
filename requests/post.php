<?php
switch (URL) {
    case "auth":
        use \Firebase\JWT\JWT;

        define('SECRET_KEY','Your-Secret-Key');
        define('ALGORITHM','HS512');

        $postBody = json_decode(INPUT);

        if($postBody->username && $postBody->password) {
            $user = User::find_single_by_column("username", $postBody->username);

            if (password_verify($postBody->password, $user->password)) {

                $tokenId    = base64_encode(openssl_random_pseudo_bytes(32));
                $issuedAt   = time();
                $notBefore  = $issuedAt + 10;  //Adding 10 seconds
                $expire     = $notBefore + 7200; // Adding 60 seconds
                $serverName = 'http://localhost/php_rest_api/'; /// set your domain name

                // Create the token as an array
                $data = [
                    'iat'  => $issuedAt,            // Issued at: time when the token was generated
                    'jti'  => $tokenId,             // Json Token Id: an unique identifier for the token
                    'iss'  => $serverName,          // Issuer
                    'nbf'  => $notBefore,           // Not before
                    'exp'  => $expire,              // Expire
                    'data' => [                     // Data related to the logged user you can set your required data
                        'id'   => $user->id,        // id from the users table
                        'name' => $user->username,  //  name
                    ]
                ];
                $secretKey = base64_decode(SECRET_KEY);
                /// Here we will transform this array into JWT:
                $jwt = JWT::encode(
                    $data, //Data to be encoded in the JWT
                    $secretKey, // The signing key
                    ALGORITHM
                );
                $unencodedArray = ['jwt' => $jwt];

                echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}";
            } else {
                echo "Incorrect username or password";
                http_response_code(401);
            }
        }
        break;
    case "register":
        $postBody = json_decode(INPUT);

        $user = new User();
        $user->username = $postBody->username;
        $user->password = password_hash($postBody->password, PASSWORD_BCRYPT, ['cost' => 10]);

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
            $post->title = $postBody->title;
            $post->body = $postBody->body;
            $post->author = $author->username;

            if ($post->create()) {
                echo '{ "Status" : "Post added" }';
                http_response_code(200);
            } else {
                echo '{ "Error" : "Mal-formed request" }';
                http_response_code(405);
            }
        } else {
            echo '{ "Error" : "Not authorized" }';
            http_response_code(401);
        }
        break;
}
