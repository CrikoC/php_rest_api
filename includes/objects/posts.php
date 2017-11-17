<?php
class Post extends DatabaseObject
{
    protected static $table_name = 'posts';
    protected static $db_fields = ['id', 'title', 'body', 'author'];

    public $id;
    public $title;
    public $body;
    public $author;
}