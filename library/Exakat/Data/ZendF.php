<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Data;

class ZendF {
    private $sqlite = null;
    private $phar_tmp = null;
    
    public function __construct() {
        $config = \Exakat\Config::factory();
        
        if ($config->is_phar) {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exzendf').'.sqlite';
            copy($config->dir_root.'/data/zendf.sqlite', $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = $config->dir_root.'/data/zendf.sqlite';
        }
        $this->sqlite = new \Sqlite3($docPath, SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null) {
            unlink($this->phar_tmp);
        }
    }

    public function getClassByRelease() {
        $query = 'SELECT class, release FROM classes 
                    JOIN namespaces 
                      ON classes.namespace_id = namespaces.id
                    JOIN releases 
                      ON namespaces.release_id = releases.id';
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[$row['release']] = $row['class'];
        }
        
        return $return;
    }
    
    public function getReleaseUsingClasses($classes) {
        $sqlClasses = "'".implode("', '", $classes)."'";
        
        $query = <<<SQL
select count(*) from classes 
JOIN namespaces 
  ON classes.namespace_id = namespaces.id
JOIN releases 
  ON namespaces.release_id = releases.id
where class IN ($sqlClasses)
GROUP BY release
order by count(*) DESC
SQL;
        $res = $this->sqlite->query($query);
        $row = $res->fetchArray(SQLITE3_ASSOC);
        $max = array_pop($row);

        $query = <<<SQL
select release, GROUP_CONCAT(class) AS classes from classes 
JOIN namespaces 
  ON classes.namespace_id = namespaces.id
JOIN releases 
  ON namespaces.release_id = releases.id
where class IN ($sqlClasses)
GROUP BY release
HAVING COUNT(*) = $max
SQL;
        $res = $this->sqlite->query($query);
        
        $return = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[$row['release']] = explode(',', $row['classes']);
        }
        
        return $return;
    }
}

?>
