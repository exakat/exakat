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

class Composer {
    private $sqlite = null;
    private $phar_tmp = null;

    public function __construct($config) {
        if ($config->is_phar) {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exMethods') . '.sqlite';
            copy($config->dir_root . '/data/composer.sqlite', $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = $config->dir_root . '/data/composer.sqlite';
        }
        $this->sqlite = new \Sqlite3($docPath, \SQLITE3_OPEN_READONLY);
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

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = strtolower($row['namespace']);
        }

        return $return;
    }

    public function getComposerClasses() {
        // global namespace is stored with 'global' keyword, so we remove it.
        $query = "SELECT DISTINCT CASE namespace WHEN 'global' THEN classname ELSE namespace || '\\' || classname END AS classname 
        FROM namespaces 
        JOIN classes 
            ON classes.namespace_id = namespaces.id";

        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = strtolower($row['classname']);
        }

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

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
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

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = strtolower($row['traitname']);
        }

        return $return;
    }
}

?>
