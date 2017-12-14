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
}
