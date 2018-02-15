<?php
class User extends DatabaseObject
{
    protected static $table_name = 'users';
    protected static $db_fields = ['id','name','email' , 'username', 'password'];

    public $id;
    public $name;
    public $email;
    public $username;
    public $password;
}