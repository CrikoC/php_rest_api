<?php
class Post extends DatabaseObject
{
    protected static $table_name = 'posts';
    protected static $db_fields = ['id', 'title', 'body', 'author', 'category_id', 'published_at'];

    public $id;
    public $title;
    public $body;
    public $author;
    public $category_id;
    public $published_at;
}