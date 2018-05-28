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
            $category_id = $_GET['category_id'];
            $api->ViewPosts($category_id);
        }
        break;
    case "categories":
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $api->ViewCategory($id);
        } else {
            $api->ViewCategories();
        }      
        break;
}