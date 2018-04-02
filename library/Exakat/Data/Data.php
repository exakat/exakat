<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

use Exakat\Config;

class Data {
    protected $name = '';

    private $sqlite = null;
    private $phar_tmp = null;

    public function __construct($name, $path, $is_phar) {
        $this->name = $name;
        
        if ($is_phar) {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), $name).'.sqlite';
            copy($path.'/'.$name.'.sqlite', $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = $path.'/'.$name.'.sqlite';
        }
        $this->sqlite = new \Sqlite3($docPath, \SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null) {
            unlink($this->phar_tmp);
        }
    }

    public function getVersions($component = null) {
        $query = 'SELECT version AS version FROM versions';
        $query .= " ORDER BY 1";
        $res = $this->sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $return[] = $row[0];
        }

        return $return;
    }

    public function getCIT($component, $version = null) {
        $query = 'SELECT namespaces.name || "\" || cit.name AS className, version FROM cit 
                    JOIN namespaces 
                      ON cit.namespaceId = namespaces.id
                    JOIN versions 
                      ON namespaces.versionId = versions.id ';
        if ($version !== null) {
            $query .= " WHERE versions.version = \"$version\"";
        }

        $res = $this->sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['version']])) {
                $return[$row['version']][] = $row['className'];
            } else {
                $return[$row['version']] = array($row['className']);
            }
        }

        return $return;
    }
    
    public function getClasses($component, $version = null) {
        $query = 'SELECT namespaces.name || "\" || cit.name AS className, version FROM cit 
                    JOIN namespaces 
                      ON cit.namespaceId = namespaces.id
                    JOIN versions 
                      ON namespaces.versionId = versions.id 
                    WHERE cit.type = "class"';
        if ($version !== null) {
            $query .= " AND versions.version = \"$version\"";
        }

        $res = $this->sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['version']])) {
                $return[$row['version']][] = $row['className'];
            } else {
                $return[$row['version']] = array($row['className']);
            }
        }

        return $return;
    }

    public function getInterfaces($component, $version = null) {
        $query = 'SELECT namespaces.name || "\" || cit.name AS interfaceName, version FROM cit 
                    JOIN namespaces 
                      ON cit.namespaceId = namespaces.id
                    JOIN versions 
                      ON namespaces.versionId = versions.id 
                    WHERE cit.type = "interface"';
        if ($version !== null) {
            $query .= " AND versions.version = \"$version\"";
        }

        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['version']])) {
                $return[$row['version']][] = $row['interfaceName'];
            } else {
                $return[$row['version']] = array($row['interfaceName']);
            }
        }

        return $return;
    }

    public function getTraits($component, $version = null) {
        $query = 'SELECT namespaces.name || "\" || cit.name AS traitName, version FROM cit 
                    JOIN namespaces 
                      ON cit.namespaceId = namespaces.id
                    JOIN versions 
                      ON namespaces.versionId = versions.id 
                    WHERE cit.type = "trait" AND
                          namespaces.name != "" ';
        if ($version !== null) {
            $query .= " AND versions.version = \"$version\"";
        }

        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['version']])) {
                $return[$row['version']][] = $row['traitName'];
            } else {
                $return[$row['version']] = array($row['traitName']);
            }
        }

        return $return;
    }

    public function getNamespaces($component, $version = null) {
        $query = 'SELECT namespaces.name as namespaceName, version FROM namespaces 
                    JOIN versions 
                      ON namespaces.versionId = versions.id 
                  WHERE namespaces.name != "" ';
        if ($version !== null) {
            $query .= " AND versions.version = \"$version\"";
        }

        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['version']])) {
                $return[$row['version']][] = $row['namespaceName'];
            } else {
                $return[$row['version']] = array($row['namespaceName']);
            }
        }

        return $return;
    }
    
}

?>
