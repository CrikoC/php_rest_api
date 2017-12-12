<?php

switch (URL) {
    case "profile":
        
        break;
      
    case "posts":
        $post_id = $_GET['id'];
        if(empty($post_id)) {
            $api->throwError(405, "Post not found.");       
        }
        
        $api->EditPost($post_id);
        break;
}

