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

use Exakat\Config;

class Methods {
    private $sqlite = null;
    private $phar_tmp = null;

    const STRICT = true;
    const LOOSE  = false;

    public function __construct(Config $config) {
        if ($config->is_phar) {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exMethods') . '.sqlite';
            copy($config->dir_root . '/data/methods.sqlite', $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = $config->dir_root . '/data/methods.sqlite';
        }
        $this->sqlite = new \Sqlite3($docPath, \SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null && file_exists($this->phar_tmp)) {
            unlink($this->phar_tmp);
        }
    }

    public function getPhpFunctions(): array {
        $query = 'SELECT name FROM methods WHERE class = "PHP"';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getPhpClasses(): array {
        $query = 'SELECT DISTINCT class FROM methods WHERE class != "PHP"';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getMethodsArgsInterval(): array {
        $query = 'SELECT class, name, args_min, args_max FROM methods WHERE class != "PHP"';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getFunctionsArgsInterval(): array {
        $query = 'SELECT class, name, args_min, args_max FROM methods WHERE Class = "PHP"';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getFunctionsLastArgsNotBoolean(): array {
        $query = <<<'SQL'
SELECT '\' || lower(methods.name) AS fullnspath, args_max - 1 AS position FROM methods 
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

    public function getFunctionsReferenceArgs(): array {
        $query = <<<'SQL'
SELECT name AS function, 0 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg0 = 'reference' UNION
SELECT name AS function, 1 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg1 = 'reference' UNION
SELECT name AS function, 2 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg2 = 'reference' UNION
SELECT name AS function, 3 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg3 = 'reference' UNION
SELECT name AS function, 4 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg4 = 'reference' UNION
SELECT name AS function, 5 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg5 = 'reference'
SQL;
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getFunctionsValueArgs(): array {
        $query = <<<'SQL'
SELECT name AS function, 0 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg0 = 'value' UNION
SELECT name AS function, 1 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg1 = 'value' UNION
SELECT name AS function, 2 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg2 = 'value' UNION
SELECT name AS function, 3 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg3 = 'value' UNION
SELECT name AS function, 4 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg4 = 'value' UNION
SELECT name AS function, 5 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg5 = 'value'
SQL;
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getDeterministFunctions(): array {
        $query = 'SELECT name FROM methods WHERE determinist = 1';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row['name'];
        }

        return $return;
    }

    public function getNonDeterministFunctions(): array {
        $query = 'SELECT name FROM methods WHERE determinist = 0';
        $res = $this->sqlite->query($query);
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row['name'];
        }

        return $return;
    }

    public function getInternalParameterType(): array {
        $return = array();

        $args = array('arg0', 'arg1');
        foreach($args as $id => $arg) {
            $query = <<<SQL
SELECT $arg, lower(GROUP_CONCAT('\' || name)) AS functions FROM args_type WHERE class='PHP' AND $arg IN ('int', 'array', 'bool','string') GROUP BY $arg
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

    public function getFunctionsByArgType(string $typehint = 'int', $strict = self::STRICT): array {
        $return = array_fill(0, 10, array());

        if ($strict === self::LOOSE) {
            $search = " LIKE '%$typehint%'";
        } elseif ($strict === self::STRICT) {
            $search = " = '$typehint'";
        } else {
            // Default is strict
            $search = " = '$typehint'";
        }

        $query = <<<SQL
SELECT name AS function, 0 AS position FROM args_type WHERE Class = 'PHP' AND arg0 $search UNION
SELECT name AS function, 1 AS position FROM args_type WHERE Class = 'PHP' AND arg1 $search UNION
SELECT name AS function, 2 AS position FROM args_type WHERE Class = 'PHP' AND arg2 $search UNION
SELECT name AS function, 3 AS position FROM args_type WHERE Class = 'PHP' AND arg3 $search UNION
SELECT name AS function, 4 AS position FROM args_type WHERE Class = 'PHP' AND arg4 $search UNION
SELECT name AS function, 5 AS position FROM args_type WHERE Class = 'PHP' AND arg5 $search UNION
SELECT name AS function, 6 AS position FROM args_type WHERE Class = 'PHP' AND arg6 $search UNION
SELECT name AS function, 7 AS position FROM args_type WHERE Class = 'PHP' AND arg7 $search UNION
SELECT name AS function, 8 AS position FROM args_type WHERE Class = 'PHP' AND arg8 $search UNION
SELECT name AS function, 9 AS position FROM args_type WHERE Class = 'PHP' AND arg9 $search 
SQL;
        $res = $this->sqlite->query($query);

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($return, (int) $row['position'], '\\' . mb_strtolower($row['function']));
        }

        return $return;
    }

    public function getBugFixes(): array {
        $return = array();

        $query = <<<'SQL'
SELECT * FROM bugfixes ORDER BY SUBSTR(solvedIn72, 5) + 0 DESC, SUBSTR(solvedIn71, 5) + 0 DESC, SUBSTR(solvedIn70, 5) + 0 DESC, SUBSTR(56, 5) + 0 DESC 
SQL;
        $res = $this->sqlite->query($query);

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getFunctionsByReturn(bool $singleTypeOnly = self::LOOSE): array {
        $return = array();

        if ($singleTypeOnly === true) {
            $where = ' AND return NOT LIKE "%,%"';
        } else {
            $where = '';
        }

        $query = <<<SQL
SELECT return, lower(GROUP_CONCAT('\' || name)) AS functions 
    FROM args_type 
    WHERE class='PHP'         AND 
          return IS NOT NULL $where
    GROUP BY return
SQL;
        $res = $this->sqlite->query($query);

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $types = explode(',', $row['return']);
            foreach($types as $type) {
                array_collect_by($return, $type, explode(',', $row['functions']));
            }
        }

        foreach($return as &$list) {
            $list = array_merge(...$list);
        }

        return $return;
    }

    public function getFunctionsByReturnType(string $type = 'int', bool $singleTypeOnly = self::STRICT): array {
        $return = array();

        if ($singleTypeOnly === self::STRICT) {
            $where = ' AND return NOT LIKE "%,%"';
        } else {
            $where = '';
        }

        $query = <<<SQL
SELECT return, lower(GROUP_CONCAT('\' || name)) AS functions 
    FROM args_type 
    WHERE class='PHP'         AND 
          return LIKE '%$type%' AND
          return IS NOT NULL $where
    GROUP BY return
SQL;
        $res = $this->sqlite->query($query);

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = explode(',', $row['functions']);
        }

        $return = array_merge(...$return);

        return $return;
    }
}

?>
