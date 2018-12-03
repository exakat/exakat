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

class Methods {
    private $sqlite = null;
    private $phar_tmp = null;

    public function __construct($config) {
        if ($config->is_phar) {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exMethods').'.sqlite';
            copy($config->dir_root.'/data/methods.sqlite', $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = $config->dir_root.'/data/methods.sqlite';
        }
        $this->sqlite = new \Sqlite3($docPath, \SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null) {
            unlink($this->phar_tmp);
        }
    }

    public function getMethodsArgsInterval() {
        $query = 'SELECT class, name, args_min, args_max FROM methods WHERE class != "PHP"';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getFunctionsArgsInterval() {
        $query = 'SELECT class, name, args_min, args_max FROM methods WHERE Class = "PHP"';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getFunctionsLastArgsNotBoolean() {
        $query = <<<SQL
SELECT '\\' || lower(methods.name) AS fullnspath, args_max - 1 AS position FROM methods 
JOIN args_type ON args_type.name = methods.name
WHERE methods.class = "PHP" AND
      (args_max = 1 AND not instr(arg0, 'bool') AND arg0 != '') OR   
      (args_max = 2 AND not instr(arg1, 'bool') AND arg1 != '') OR 
      (args_max = 3 AND not instr(arg2, 'bool') AND arg2 != '') OR 
      (args_max = 4 AND not instr(arg3, 'bool') AND arg3 != '')	
SQL;
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row['fullnspath'];
        }

        return $return;
    }

    public function getFunctionsReferenceArgs() {
        $query = "SELECT name AS function, 0 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg0 = 'reference' UNION
                  SELECT name AS function, 1 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg1 = 'reference' UNION
                  SELECT name AS function, 2 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg2 = 'reference' UNION
                  SELECT name AS function, 3 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg3 = 'reference' UNION
                  SELECT name AS function, 4 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg4 = 'reference' UNION
                  SELECT name AS function, 5 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg5 = 'reference'
                  ";
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getFunctionsValueArgs() {
        $query = "SELECT name AS function, 0 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg0 = 'value' UNION
                  SELECT name AS function, 1 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg1 = 'value' UNION
                  SELECT name AS function, 2 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg2 = 'value' UNION
                  SELECT name AS function, 3 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg3 = 'value' UNION
                  SELECT name AS function, 4 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg4 = 'value' UNION
                  SELECT name AS function, 5 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg5 = 'value'
                  ";
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getDeterministFunctions() {
        $query = 'SELECT name FROM methods WHERE determinist = 1';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row['name'];
        }

        return $return;
    }

    public function getNonDeterministFunctions() {
        $query = 'SELECT name FROM methods WHERE determinist = 0';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row['name'];
        }

        return $return;
    }

    public function getInternalParameterType() {
        $return = array();

        $args = array('arg0', 'arg1');
        foreach($args as $id => $arg) {
            $query = <<<SQL
SELECT $arg, lower(GROUP_CONCAT('\\' || name)) AS functions FROM args_type WHERE class='PHP' AND $arg IN ('int', 'array', 'bool','string') GROUP BY $arg
SQL;
            $res = $this->sqlite->query($query);

            $position = array();
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $position[$row[$arg]] = explode(',', $row['functions']);
            }

            $return[$id] = $position;
        }

        return $return;
    }

    public function getBugFixes() {
        $return = array();

        $query = <<<SQL
SELECT * FROM bugfixes ORDER BY SUBSTR(solvedIn72, 5) + 0 DESC, SUBSTR(solvedIn71, 5) + 0 DESC, SUBSTR(solvedIn70, 5) + 0 DESC, SUBSTR(56, 5) + 0 DESC 
SQL;
        $res = $this->sqlite->query($query);

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getFunctionsByReturn() {
        $return = array();

        $query = <<<SQL
SELECT return, lower(GROUP_CONCAT('\\' || name)) AS functions FROM args_type WHERE class='PHP' GROUP BY return
SQL;
        $res = $this->sqlite->query($query);

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['return']] = explode(',', $row['functions']);
        }

        return $return;
    }
}

?>
