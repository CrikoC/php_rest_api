<?php
switch (URL) {
    case "auth":
        $api->authorize();
        break;
    case "register":
        $api->register();
        break;
    case "categories":
        $api->addCategory();
        break;
    case "posts":
        $api->addPost();
        break;
}
