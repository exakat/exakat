<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class ZendF3 {
    protected $sqlite = null;
    protected $phar_tmp = null;

    public function __construct($path, $is_phar) {
        if ($is_phar) {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exzendf3').'.sqlite';
            copy($path.'/zendf3.sqlite', $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = $path.'/zendf3.sqlite';
        }
        $this->sqlite = new \Sqlite3($docPath, \SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null) {
            unlink($this->phar_tmp);
        }
    }

    public function getVersions($component = null) {
        $query = 'SELECT DISTINCT replace(release, "release-","") AS version FROM releases';
        if ($component !== null) {
            $query .= "  JOIN components 
                      ON releases.component_id = components.id 
 WHERE components.component = \"".$component."\"";
        }
        $query .= " ORDER BY 1";
        $res = $this->sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $return[] = $row[0];
        }

        return $return;
    }
    
    public function getClasses($component, $release = null) {
        $query = 'SELECT namespaces.namespace || "\" || class AS class, release FROM classes 
                    JOIN namespaces 
                      ON classes.namespace_id = namespaces.id
                    JOIN releases 
                      ON namespaces.release_id = releases.id 
                    JOIN components 
                      ON releases.component_id = components.id 
                    WHERE components.component = "'.$component.'"';
        if ($release !== null) {
            $query .= " AND releases.release = \"release-$release.0\"";
        }

        $res = $this->sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['release']])) {
                $return[$row['release']][] = $row['class'];
            } else {
                $return[$row['release']] = array($row['class']);
            }
        }

        return $return;
    }

    public function getInterfaces($component, $release = null) {
        $query = 'SELECT namespaces.namespace || "\" || interface AS interface, release FROM interfaces 
                    JOIN namespaces 
                      ON interfaces.namespace_id = namespaces.id
                    JOIN releases 
                      ON namespaces.release_id = releases.id
                    JOIN components 
                      ON releases.component_id = components.id 
                    WHERE components.component = "'.$component.'"';
        if ($release !== null) {
            $query .= " AND releases.release = \"release-$release.0\"";
        }

        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['release']])) {
                $return[$row['release']][] = $row['interface'];
            } else {
                $return[$row['release']] = array($row['interface']);
            }
        }

        return $return;
    }

    public function getTraits($component, $release = null) {
        $query = 'SELECT namespaces.namespace || "\" || trait AS trait, release FROM traits 
                    JOIN namespaces 
                      ON traits.namespace_id = namespaces.id
                    JOIN releases 
                      ON namespaces.release_id = releases.id
                    JOIN components 
                      ON releases.component_id = components.id 
                    WHERE components.component = "'.$component.'"';
        if ($release !== null) {
            $query .= " AND releases.release = \"release-$release.0\"";
        }

        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['release']])) {
                $return[$row['release']][] = $row['trait'];
            } else {
                $return[$row['release']] = array($row['trait']);
            }
        }

        return $return;
    }

    public function getDeprecated($component = null, $release = null) {
        $where = array();
        if ($component !== null) {
            $where[] = 'components.component = "'.$component.'"';
        }
        if ($release !== null) {
            $where[] = "releases.release = \"release-$release.0\"";
        }
        if (empty($where)) {
            $where = '';
        } else {
            $where = ' WHERE '.implode(' AND ', $where);
        }
        
        
        $query = 'SELECT type, cit, name, namespaces.namespace, release FROM deprecated 
                    JOIN namespaces 
                      ON deprecated.namespace_id = namespaces.id
                    JOIN releases 
                      ON namespaces.release_id = releases.id
                    JOIN components 
                      ON releases.component_id = components.id 
                    '.$where.' 
                    GROUP BY type, cit, name';

        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $type = $row['type'];
            unset($row['type']);

            $release = $row['release'];
            unset($row['release']);

            if (isset($return[$type][$release])) {
                $return[$type][$release][] = $row;
            } elseif (isset($return[$type])) {
                $return[$type][$release] = array($row);
            } else {
                $return[$type] = array($release => array($row));
            }
        }

        return $return;
    }

}

?>
