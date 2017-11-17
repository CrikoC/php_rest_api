<?php
class User extends DatabaseObject
{
    protected static $table_name = 'users';
    protected static $db_fields = ['id', 'username', 'password'];

    public $id;
    public $username;
    public $password;
}