<?php
// If it's going to need the database, then it's
// probably smart to require it before we start.
class DatabaseObject {
    protected static $table_name;
    protected static $db_fields;


    /************************************/
    /*         GENERAL METHODS          */
    /************************************/
    protected function attributes() {
        $attributes = [];

        foreach(static::$db_fields as $field) {
            if(property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

    public static function find_by_sql($query="") {
        global $db;
        $object_array = [];
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $object_array[] = static::instantiate($result);
        }
        
        return $object_array;
    }

    private static function instantiate($result) {
        $class_name = get_called_class();
        $object = new $class_name;

        foreach ($result as $attribute=>$value) {
            $object->$attribute = $value;
        }

        return $object;
    }
    /************************************/



    /************************************/
    /*             READ                 */
    /************************************/

    //multiple results
    public static function find_all() {
        $query = "SELECT * FROM  ".static::$table_name;
        return static::find_by_sql($query);
    }

    public static function find_limited($num="") {
        $query = "SELECT * FROM ".static::$table_name;
        $query .= " LIMIT $num";
        return static::find_by_sql($query);
    }

    public static function find_by_column($column_name, $column_value) {
        $query = "SELECT * FROM ".static::$table_name;
        $query .= " WHERE $column_name = '".$column_value."'";
        return static::find_by_sql($query);
    }

    //single result
    public static function find_by_id($id="") {
        $query = "SELECT * FROM ".static::$table_name;
        $query .= " WHERE id = $id";
        $result_array = static::find_by_sql($query);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_single_by_column($column_name, $column_value) {
        $query = "SELECT * FROM ".static::$table_name;
        $query .= " WHERE $column_name = '".$column_value."'";
        $result_array = static::find_by_sql($query);
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    /************************************/


    /************************************/
    /*              CREATE              */
    /************************************/
    public function create() {
        global $db;
        $attributes = $this->attributes();

        try {
            $query = "INSERT INTO ".static::$table_name."(";
            $query .= join(", ", array_keys($attributes));
            $query .= ") VALUES ('";
            $query .= join("', '", array_values($attributes));
            $query .= "')";

            $stmt = $db->prepare($query);
            $stmt->execute();

            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    /************************************/

    /************************************/
    /*              UPDATE              */
    /************************************/
    public function update() {
        global $db;
        $attributes = $this->attributes();
        $attribute_pairs = [];

        try {
            foreach($attributes as $key => $value) {
                $attribute_pairs[] = "{$key}='{$value}'";
            }

            $query = "UPDATE ".static::$table_name." SET ";
            $query .= join(", ", $attribute_pairs);
            $query .= " WHERE id = '".$this->id."'";

            $stmt = $db->prepare($query);
            $stmt->execute();

            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    /************************************/


    /************************************/
    /*              DELETE              */
    /************************************/
    public function delete() {
        global $db;
        try {
            $query = "DELETE FROM ".static::$table_name;
            $query .= " WHERE id = '".$this->id."'";

            $stmt = $db->prepare($query);
            $stmt->execute();

            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    /************************************/

    public function upload_image($image_temp, $image) {
        move_uploaded_file($image_temp, SITE_ROOT."/public/includes/images/$image");
    }
}