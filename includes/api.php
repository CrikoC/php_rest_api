<?php

class Api extends Rest {
    public function __construct() {
        parent::__construct();
    }
    
    /*************************************/
    /*               USERS               */
    /*************************************/
    public function register() {
        /*
         * @param username
         * @param password
         * 
         * @return new user  
         */
        
        $username = $this->validateData("username", $this->data->username, STRING);
        $password = $this->validateData("password", $this->data->password, STRING);
        
        $user = new User;
        
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        if($user->create()) {
            $this->response(SUCCESS_RESPONSE, "User registered.");
            http_response_code(SUCCESS_RESPONSE);
        } else {
            $this->throwError(NOT_FOUND, "Error while creating user.");
            http_response_code(NOT_FOUND);
        }
    }
    
    public function authorize() {
        /*
         * @param username
         * @param password
         *
         * @return token
         */
        
        // Validate user fields
        $username = $this->validateData("username", $this->data->username, STRING);
        $password = $this->validateData("password", $this->data->password, STRING);
        
        //Search user by given username
        $user = User::find_single_by_column("username", $username);
        
        //Check if given password matches the input password
        if(!password_verify($password, $user->password)) {
            $this->response(UNAUTHORIZED, "Invalid username or password.");
            http_response_code(UNAUTHORIZED);
        } else {
            $payload = [
                'iat'       => time(),
                'iss'       => 'localhost',
                'exp'       => time() + 3600,
                'userId'    => (int)$user->id 
                /*
                 * If user id will not converted to int, it will be decoded as a string,
                 * Making it hard to search for the user when we search it's profile or
                 * add the username as an author for each new post we add
                 * 
                 */
            ];
            
            $token = \Firebase\JWT\JWT::encode($payload, SECRET_KEY);
            
            $data = ['token' => $token];
            $this->response(SUCCESS_RESPONSE, $data);
            http_response_code(SUCCESS_RESPONSE);
        }
    }
    
    public function viewProfile() {
        /*
         * @return user
         */
        $user = User::find_by_id($this->userId);
        if(!empty($user)) {
            echo json_encode($user);
            http_response_code(SUCCESS_RESPONSE);
        } else {
            $this->throwError(NOT_FOUND, "Could not find user.");
            http_response_code(NOT_FOUND);
        }
    }
    
    public function EditProfile() {
        /*
         * @param username
         * @param password
         *
         * @return updated user
         */
        $username = $this->validateData("username", $this->data->username, STRING);
        $password = $this->validateData("password", $this->data->password, STRING);
        
        $this->validateToken();
        $user = User::find_by_id($this->userId);
        
        if($user == "") {
            $this->throwError(INVALID_USER_PASS, "Cannot find user in the database.");
        } else {
            if($user == null) {
                $this->throwError(NOT_FOUND, "User not found");
                http_response_code(NOT_FOUND);
            } else {
                $user->username = $username;
                $user->password = $password;
                
                if($user->update()) {
                    $this->response(SUCCESS_RESPONSE, "Account updated.");
                    http_response_code(SUCCESS_RESPONSE);
                    
                } else {
                    $this->throwError(NOT_FOUND, "Error while updating account");
                }
            }
        }
    }
    
    public function deleteUser() {
        $this->validateToken();
        $user = User::find_by_id($this->userId);
        
        if($user == "") {
            $this->response(INVALID_USER_PASS, "Cannot find user in the database.");
        } else {
            if($user->delete()) {
                $this->response(SUCCESS_RESPONSE, "Account deleted.");
                http_response_code(SUCCESS_RESPONSE);
            } else {
                $this->throwError(NOT_FOUND, "Error while deleting user");
                http_response_code(NOT_FOUND);
            }
        }
    }
    /*************************************/
    
    
    /*************************************/
    /*               POSTS               */
    /*************************************/
    public function ViewPosts() {
        /*
         * @return posts
         */
        echo json_encode(Post::find_all());
    }
    
    public function ViewPost($id) {
        /*
         * @return post
         */
        $post = Post::find_by_id( $id);
        if(!empty($post)) {
            echo json_encode($post);
            http_response_code(SUCCESS_RESPONSE);
        } else {
            $this->throwError(NOT_FOUND, "Could not find post.");
            http_response_code(NOT_FOUND);
        }
    }
    
    public function addPost() {
        /*
         * @param title
         * @param body
         *
         * @return new post
         */
        $title = $this->validateData("title", $this->data->title, STRING);
        $body = $this->validateData("body", $this->data->body, STRING);
        
        $this->validateToken();
        $user = User::find_by_id($this->userId);
            
        if($user == "") {
            $this->throwError(INVALID_USER_PASS, "Cannot find user in the database.");  
        } else {
            $post = new Post;
            
            $post->title = $title;
            $post->body = $body;
            $post->author = $user->username;
            
            if($post->create()) {
                $this->response(SUCCESS_RESPONSE, "Post created.");
                http_response_code(SUCCESS_RESPONSE);
            } else {
                $this->throwError(NOT_FOUND, "Error while inserting post");
                http_response_code(NOT_FOUND);
            }
        }
    }
    
    public function EditPost($id) {
        /*
         * @param title
         * @param body
         *
         * @return upddated post
         */
        $title = $this->validateData("title", $this->data->title, STRING);
        $body = $this->validateData("body", $this->data->body, STRING);

        $this->validateToken();
        $user = User::find_by_id($this->userId);
        
        if($user == "") {
            $this->throwError(INVALID_USER_PASS, "Cannot find user in the database.");
        } else {
         
            
            if($id == null) {
                $this->throwError(NOT_FOUND, "post not found");
                http_response_code(NOT_FOUND);
            } else {
                $post = Post::find_by_id($id);
                
                $post->title = $title;
                $post->body = $body;
                $post->author = $user->username;
                
                if($post->update()) {
                    $this->response(SUCCESS_RESPONSE, "Post updated.");
                    http_response_code(SUCCESS_RESPONSE);
                    
                } else {
                    $this->throwError(NOT_FOUND, "Error while updating post");
                }
            }
        }
    }
    
    public function deletePost($id) {
        $this->validateToken();
        $user = User::find_by_id($this->userId);
        
        if($user == "") {
            $this->response(INVALID_USER_PASS, "Cannot find user in the database.");
        } else {
            $post = Post::find_by_id($id);
            if($post->delete()) {
                $this->response(SUCCESS_RESPONSE, "Post deleted.");
                http_response_code(SUCCESS_RESPONSE);
            } else {
                $this->throwError(NOT_FOUND, "Error while deleting post");
                http_response_code(NOT_FOUND);
            }
        }
    }
    /*************************************/
}