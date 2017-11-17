<?php

/**
 * Created by PhpStorm.
 * User: CrikoC
 * Date: 11/17/2017
 * Time: 6:57 PM
 */
class Login extends DatabaseObject {
    protected static $table_name = 'logins';
    protected static $db_fields = ['id', 'token', 'user_id'];

    public $id;
    public $token;
    public $user_id;
}