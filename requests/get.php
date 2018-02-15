<?php

switch (URL) {
    case "profile":
        $api->viewProfile();
        break;
        
    case "posts":
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $api->ViewPost($id);
        } else {
            $api->ViewPosts();
        }
        break;
    case "categories":
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $api->ViewPosts($id);
        } else {
            $api->ViewCategories();
        }      
        break;
}