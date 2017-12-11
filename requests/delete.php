<?php
switch (URL) {
    case "auth":
     
        break;
    case "users":

        break;
    case "posts":
        $post_id = $_GET['id'];
        $api->deletePost($post_id);
        break;
}
