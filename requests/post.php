<?php
switch (URL) {
    case "auth":
        $api->authorize();
        break;
    case "register":
        $api->register();
        break;
    case "posts":
        $api->addPost();
        break;
}
