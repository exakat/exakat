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


namespace Data;

class Composer {
    private $sqlite = null;
    private $phar_tmp = null;
    
    public function __construct() {
        $config = \Config::factory();
        
        if ($config->is_phar) {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exMethods').'.sqlite';
            copy($config->dir_root.'/data/composer.sqlite', $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = $config->dir_root.'/data/composer.sqlite';
        }
        $this->sqlite = new \Sqlite3($docPath, SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null) {
            unlink($this->phar_tmp);
        }
    }

    public function getComposerNamespaces($vendor = null) {
        $query = "SELECT namespace AS namespace FROM namespaces WHERE namespace != 'global' ";
        if ($vendor !== null) {
            list($vendor, $component) = explode('/', $vendor);
            $query .= " AND vendor = '$vendor' AND component = '$component'";
        
        }
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = strtolower($row['namespace']);
        }
        
        return $return;
    }

    public function getComposerClasses($vendor = null, $component = null, $version = null) {
        // global namespace is stored with 'global' keyword, so we remove it.
        $query = <<<SQL
SELECT DISTINCT CASE namespace WHEN 'global' THEN classname ELSE namespace || '\\' || classname END AS classname 
    FROM namespaces 
    JOIN classes 
        ON classes.namespace_id = namespaces.id
SQL;
            
        if ($vendor != null) {
            $version = $this->getVersion($vendor, $component, $version);
            $query .= <<<SQL

    JOIN versions
        ON versions.id = namespaces.version_id
    JOIN components
        ON components.id = versions.component_id
    WHERE components.vendor = "$vendor"       AND 
          components.component = "$component" AND
          versions.version = "$version"
            
SQL;
            print $query;
        }

        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = strtolower($row['classname']);
        }
        print_r($return);
                    die();

        return $return;
    }
    
    public function getComposerInterfaces($vendor = null) {
        // global namespace is stored with 'global' keyword, so we remove it.
        $query = "SELECT CASE namespace WHEN 'global' THEN interfacename ELSE namespace || '\\' || interfacename END AS interfacename FROM namespaces 
JOIN interfaces ON interfaces.namespace_id = namespaces.id";
        if ($vendor !== null) {
            list($vendor, $component) = explode('/', $vendor);
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
        // global namespace is stored with 'global' keyword, so we remove it.
        $query = "SELECT CASE namespace WHEN 'global' THEN traitname ELSE namespace || '\\' || traitname END AS traitname FROM namespaces 
JOIN traits ON traits.namespace_id = namespaces.id";
        if ($vendor !== null) {
            list($vendor, $component) = explode('/', $vendor);
            $query .= " WHERE vendor = '$vendor' and component = '$component'";
        
        }
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = strtolower($row['traitname']);
        }
        
        return $return;
    }
    
    public function getVersion($vendor, $component, $version) {
        if (strpos($version, '~') !== false) {
            $min = substr($version, 1);
            $d = explode('.', $min);
            $d[count($d) - 2]++;
            $d[count($d) - 1] = '0';
            $max = join('.', $d);
            $query = <<<SQL
SELECT version 
    FROM versions 
    JOIN components
        ON components.id = versions.component_id
    WHERE components.vendor = "$vendor"       AND 
          components.component = "$component" AND
          versions.name >= "$min" and versions.name < "$max"

SQL;

print $query;die();


        } else {
            // By default, no special chars, so just return the version
            return $version;
        }
    }
}

?>
