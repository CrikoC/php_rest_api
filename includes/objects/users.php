<?php
class User extends DatabaseObject
{
    protected static $table_name = 'users';
    protected static $db_fields = ['id', 'username', 'password', 'token'];

    public $id;
    public $username;
    public $password;
    public $token;
}