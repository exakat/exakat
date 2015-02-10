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

class Methods {
    private $sqlite = null;
    
    public function __construct() {
        $this->sqlite = new \sqlite3('./data/methods.sqlite');
    }

    public function getMethodsArgsInterval() {
        $query = "SELECT class, name, args_min, args_max FROM methods";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
        }
        
        return $return;
    }

    public function getFunctionsArgsInterval() {
        $query = "SELECT class, name, args_min, args_max FROM methods WHERE Class = 'PHP'";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
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
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
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
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
        }
        
        return $return;
    }

    public function getStochasticFunctions() {
        $query = "SELECT name FROM methods WHERE stochastic = 'true'";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
        }
        
        return $return;
    }

    public function getNonStochasticFunctions() {
        $query = "SELECT name FROM methods WHERE stochastic != 0";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row['name'];
        }
        
        return $return;
    }
}

?>
