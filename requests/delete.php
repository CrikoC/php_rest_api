<?php
switch (URL) {
    case "profile":
        $api->deleteUser();
        break;
    case "posts":
        if(isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $api->deletePost($post_id);
        } else {
            $api->throwError(405, "Post not found.");
        }
        break;
    case "categories":
        if(isset($_GET['id'])) {
            $cat_id = $_GET['id'];
            $api->deleteCategory($cat_id);
        } else {
            $api->throwError(405, "Category not found.");
        }
        break;
}
