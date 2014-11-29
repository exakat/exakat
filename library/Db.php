<?php

class Db  {
    
    public function __construct($db = 'exakat') {
        $config = \Config::factory();
        
        if ($db == 'exakat') {
            $this->mysqli = new \mysqli($config->mysql_host, 
                                        $config->mysql_exakat_user, 
                                        $config->mysql_exakat_pass,
                                        $config->mysql_exakat_db);
        } elseif ($db == 'wordpress') {
            $this->mysqli = new \mysqli($config->mysql_host, 
                                        $config->mysql_wordpress_user, 
                                        $config->mysql_wordpress_pass,
                                        $config->mysql_wordpress_db);
        } else {
            // nothing really
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

    public function logProgress($project, $percent) {
        $this->mysqli->query("UPDATE `wordpress`.`exakat_projects` SET progress = $percent WHERE name='$project'");
    }
}
?>