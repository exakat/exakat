<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

    public function __construct($path, $is_phar) {
        if ($is_phar) {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exzendf') . '.sqlite';
            copy("$path/zendf.sqlite", $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = "$path/zendf.sqlite";
        }
        $this->sqlite = new \Sqlite3($docPath, \SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null) {
            unlink($this->phar_tmp);
        }
    }

    public function getClassByRelease($release = null) {
        $query = 'SELECT class, release FROM classes 
                    JOIN namespaces 
                      ON classes.namespace_id = namespaces.id
                    JOIN releases 
                      ON namespaces.release_id = releases.id';
        if ($release !== null) {
            $query .= " WHERE releases.release = \"release-$release.0\"";
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

    public function getInterfaceByRelease($release = null) {
        $query = 'SELECT interface, release FROM interfaces 
                    JOIN namespaces 
                      ON interfaces.namespace_id = namespaces.id
                    JOIN releases 
                      ON namespaces.release_id = releases.id';
        $res = $this->sqlite->query($query);
        $return = array();

        if ($release !== null) {
            $return = array($release => array());
            $query .= " WHERE releases.release = \"release-$release.0\"";
        }

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['release']])) {
                $return[$row['release']][] = $row['interface'];
            } else {
                $return[$row['release']] = array($row['interface']);
            }
        }

        return $return;
    }

    public function getTraitByRelease($release = null) {
        $query = 'SELECT trait, release FROM traits 
                    JOIN namespaces 
                      ON traits.namespace_id = namespaces.id
                    JOIN releases 
                      ON namespaces.release_id = releases.id';
        $res = $this->sqlite->query($query);
        $return = array();

        if ($release !== null) {
            $return = array($release => array());
            $query .= " WHERE releases.release = \"release-$release.0\"";
        }

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($return[$row['release']])) {
                $return[$row['release']][] = $row['trait'];
            } else {
                $return[$row['release']] = array($row['trait']);
            }
        }

        return $return;
    }
}

?>
