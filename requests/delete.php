<?php
switch (URL) {
    case "profile":
        $api->deleteUser();
        break;
    case "posts":
        $post_id = $_GET['id'];
        $api->deletePost($post_id);
        break;
}
