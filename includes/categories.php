<?php
class Category extends DatabaseObject
{
    protected static $table_name = 'categories';
    protected static $db_fields = ['id', 'name', 'body'];

    public $id;
    public $name;
    public $body;
}