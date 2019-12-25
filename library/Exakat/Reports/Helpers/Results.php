<?php
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

namespace Exakat\Reports\Helpers;

use SQLite3Result;

class Results {
    private $count        = -1;
    private $values       = null;
    private $options      = array();

    public function __construct(SQLite3Result $res, $options = array()) {
        $this->res = $res;
        
        $this->options = $options;
        $this->options['phpsyntax'] = $this->options['phpsyntax']  ?? array();
    }

    public function load() : int {
        $this->values = array();
        $this->count  = 0;

        while($row = $this->res->fetchArray(\SQLITE3_ASSOC)) {
            foreach ($this->options['phpsyntax'] as $source => $destination) {
                $row[$destination] = PHPSyntax($row[$source]);
            }
            $this->values[] = $row;
            ++$this->count;
        }

        return $this->count;
    }

    public function isEmpty() : bool {
        if ($this->values === null) {
            $this->load();
        }

        return $this->count === 0;
    }

    public function getCount() : int {
        return $this->count;
    }

    public function getColumn($column) : array {
        if ($this->values === null) {
            $this->load();
        }

        return array_column($this->values, $column);
    }

    public function toArray() : array {
        if ($this->values === null) {
            $this->load();
        }

        return $this->values;
    }

    public function toList(string $col = null) : array {
        if ($this->values === null) {
            $this->load();
        }

        if ($col === null) {
            $col = array_keys($this->values[0])[0];
        }
        
        return array_column($this->values, $col);
    }

    public function toString(string $col = '') : string {
        if ($this->values === null) {
            $this->load();
        }

        if ($col === '') {
            $first = array_keys($this->values[0])[0];
            return $this->values[0][$first];
        } else {
            return $this->values[0][$col] ?? '';
        }
    }

    public function toInt(string $col = '') : int {
        if ($this->values === null) {
            $this->load();
        }

        if ($col === '') {
            $first = array_keys($this->values[0])[0];
            return (int) $this->values[0][$first];
        } else {
            return (int) ($this->values[0][$col] ?? 0);
        }
    }

    public function toHash($key, $value) : array {
        if ($this->values === null) {
            $this->load();
        }

        $return = array();
        foreach ($this->values as $row) {
            $return[$row[$key]] = $row[$value];
        }
        
        return $return;
    }
}

?>