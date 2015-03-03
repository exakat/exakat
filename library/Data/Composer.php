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


namespace Data;

class Composer {
    private $sqlite = null;
    private $phar_tmp = null;
    
    public function __construct() {
        if (substr(__DIR__, 0, 4) == 'phar') {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exMethods').'.sqlite';
            copy('phar://'.basename(dirname(dirname(__DIR__))).'/data/composer.sqlite', $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = dirname(dirname(__DIR__)).'/data/composer.sqlite';
        }
        $this->sqlite = new \Sqlite3($docPath, SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null) {
            unlink($this->phar_tmp);
        }
    }

    public function getComposerNamespaces($vendor = null) {
        $query = "SELECT namespace FROM namespaces";
        if ($vendor !== null) {
            list($vendor, $component) = split('/', $vendor);
            $query .= " WHERE vendor = '$vendor' and component = '$component'";
        
        }
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = strtolower($row['namespace']);
        }
        
        return $return;
    }

    public function getComposerClasses($vendor = null) {
        $query = "SELECT namespace || '\\' || classname AS classname FROM namespaces 
JOIN classes ON classes.namespace_id = namespaces.id";
        if ($vendor !== null) {
            list($vendor, $component) = split('/', $vendor);
            $query .= " WHERE vendor = '$vendor' and component = '$component'";
        
        }
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = strtolower($row['classname']);
        }
        
        return $return;
    }

    public function getComposerInterfaces($vendor = null) {
        $query = "SELECT namespace || '\\' || interfacename AS interfacename FROM namespaces 
JOIN interfaces ON interfaces.namespace_id = namespaces.id";
        if ($vendor !== null) {
            list($vendor, $component) = split('/', $vendor);
            $query .= " WHERE vendor = '$vendor' and component = '$component'";
        
        }
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = strtolower($row['interfacename']);
        }
        
        return $return;
    }

    public function getComposerTraits($vendor = null) {
        $query = "SELECT namespace || '\\' || traitname AS traitname FROM namespaces 
JOIN traits ON traits.namespace_id = namespaces.id";
        if ($vendor !== null) {
            list($vendor, $component) = split('/', $vendor);
            $query .= " WHERE vendor = '$vendor' and component = '$component'";
        
        }
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = strtolower($row['traitname']);
        }
        
        return $return;
    }
}

?>
