<?php

switch (URL) {
    case "profile":
        
        break;
        
    case "posts":
        $post_id = $_GET['id'];
        
        if(!isset($post_id)) {
            $api->ViewPosts();
        } else {
            $api->ViewPost($post_id);
        }
        
        break;
}