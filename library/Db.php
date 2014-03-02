<?php

class Db  {
    
    public function __construct() {
        $this->mysqli = new \mysqli('localhost', 'root', '', 'exakat');
    }
    
    public function insert($table, $cols, $values) {

        foreach($cols as $k => $v) {
            $cols[$k] = "`$v`";
        }
        foreach($values as $k => $v) {
            $values[$k] = "'$v'";
        }

        if (in_array($table, array('projects', 'project_runs'))) {
            $cols[] = '`id`';
            $values[] = 'NULL';
            
            $cols[] = '`date`';
            $values[] = 'NOW()';
        }
        $query = "INSERT INTO `$table` (".join(", ", $cols).") VALUES (".join(", ", $values).")";
        $this->mysqli->query($query);
    }
    
    public function error() {
        return $this->mysqli->error;
    }
    
    public function query($query) {
        return $this->mysqli->query($query);
    }

    public function insert_id() {
        return $this->mysqli->insert_id;
    }
}
?>