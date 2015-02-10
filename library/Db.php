<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


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
        } elseif ($db == 'remote') {
            $this->mysqli = new \mysqli($config->mysql_remote_host, 
                                        $config->mysql_remote_user, 
                                        $config->mysql_remote_pass,
                                        $config->mysql_remote_db);
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
        $query = "INSERT INTO `$table` (".implode(", ", $cols).") VALUES (".implode(", ", $values).")";
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
