<?php

class Db  {
    
    public function __construct($db = 'exakat') {
        if ($db == 'exakat') {
            $this->mysqli = new \mysqli('127.0.0.1', 'exakat', 'exakat', 'exakat');
        } else {
            $this->mysqli = new \mysqli('127.0.0.1', 'wordpress', 'wordpress', 'wordpress');
        }
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