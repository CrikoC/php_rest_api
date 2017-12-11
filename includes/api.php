<?php

class Api extends Rest {
    public function __construct() {
        parent::__construct();
    }
    
    /*************************************/
    /*               USERS               */
    /*************************************/
    public function register() {
        $username = $this->validateData("username", $this->data["username"], STRING);
        $password = $this->validateData("pasword", $this->data["pasword"], STRING);
        
        $user = new User;
        
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        if($user->create()) {
            $this->response(SUCCESS_RESPONSE, $data);
        } else {
            $this->throwError(405, "Error while creating user");
        }
    }
    
    public function authorize() {
        // Validate user fields
        $username = $this->validateData("username", $this->data["username"], STRING);
        $password = $this->validateData("password", $this->data["password"], STRING);
        
        //Search user by given username
        $user = User::find_single_by_column("username", $username);
        
        //Check if given password matches the user's password
        if(!password_verify($password, $user->password)) {
            $this->response(INVALID_USER_PASS, "Invalid username or password.");
        } else {
            $payload = [
                'iat'       => time(),
                'iss'       => 'localhost',
                'exp'       => time() + 3600,
                'userId'    => $user->id
            ];
            
            $token = \Firebase\JWT\JWT::encode($payload, SECRET_KEY);
            
            echo $token;
            
            $data = ['token' => $token];
            $this->response(SUCCESS_RESPONSE, $data);
        }
    }
    /*************************************/
    
    
    /*************************************/
    /*               POSTS               */
    /*************************************/
    public function ViewPosts() {
        echo json_encode(Post::find_all());
    }
    
    public function ViewPost($id) {
        $post = Post::find_by_id( $id);
        if(!empty($post)) {
            echo json_encode($post);
            http_response_code(200);
        } else {
            $this->throwError(405, "Could not find post.");
            http_response_code(405);
        }
    }
    
    public function addPost() {
        $title = $this->validateData("title", $this->data["title"], STRING);
        $body = $this->validateData("body", $this->data["body"], STRING);
        
        $this->validateToken();
        $user = User::find_by_id($this->userId);
            
        if($user = "") {
            $this->response(INVALID_USER_PASS, "Cannot find user in the database.");  
        } else {
            $post = new Post;
            
            $post->title = $title;
            $post->body = $body;
            $post->author = $user->username;
            
            if($post->create()) {
                $this->response(SUCCESS_RESPONSE, $data);
            } else {
                $this->throwError(405, "Error while inserting post");
            }
        }
    }
    
    public function EditPost($id) {
        $title = $this->validateData("title", $this->data["title"], STRING);
        $body = $this->validateData("body", $this->data["body"], STRING);

        $this->validateToken();
        $user = User::find_by_id($this->userId);
        
        if($user = "") {
            $this->response(INVALID_USER_PASS, "Cannot find user in the database.");
        } else {
         
            
            if($id == null) {
                $this->throwError(405, "post not found");
            } else {
                $post = Post::find_by_id($id);
                
                $post->title = $title;
                $post->body = $body;
                $post->author = $user->username;
                
                if($post->update()) {
                    $this->response(SUCCESS_RESPONSE, $data);
                } else {
                    $this->throwError(405, "Error while updating post");
                }
            }
        }
    }
    
    public function deletePost($id) {
        $this->validateToken();
        $user = User::find_by_id($this->userId);
        
        if($user = "") {
            $this->response(INVALID_USER_PASS, "Cannot find user in the database.");
        } else {
            $post = Post::find_by_id($id);
            if($post->delete()) {
                echo '{ "Status" : "Post deleted" }';
                http_response_code(200);
            } else {
                $this->throwError(405, "Error while deleting post");
            }
        }
    }
    /*************************************/
}