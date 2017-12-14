<?php

switch (URL) {
    case "profile":
        $api->EditProfile();
        break;
      
    case "posts":
        if(isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $api->EditPost($post_id);
        } else {
            $api->throwError(405, "Post not found.");  
        }
       
        break;
}

