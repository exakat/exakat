<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Results {
    protected $sqlite   = null;
    protected $analyzer = '';
    protected $values   = array();
    protected $count    = -1;
    
    public function __construct(\Sqlite3 $sqlite, $analyzer) {
        $this->sqlite = $sqlite;
        $this->analyzer = $analyzer;
    }
    
    public function load(){
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="'.$this->analyzer.'"');
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $row['fullcode'] = PHPSyntax($row['fullcode']);
            $this->values[] = $row;
            ++$this->count;
        }

        return $this->count;
    }

    public function getCount() {
        return $this->count;
    }

    public function getColumn($column) {
        return array_column($this->values, $column);
    }
    
    public function toArray() {
        return $this->values;
    }
}

?>