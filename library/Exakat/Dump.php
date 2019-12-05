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

namespace Exakat;

use Exakat\Config;

class Dump {
    private $sqlite = null;
    
    public function __construct(\Sqlite3 $sqlite) {
        $this->sqlite = $sqlite;
    }

    public function getExtensionList() : array {
        $res = $this->sqlite->query(<<<'SQL'
SELECT analyzer, count(*) AS count FROM results 
    WHERE analyzer LIKE "Extensions/Ext%"
    GROUP BY analyzer
    ORDER BY count(*) DESC
SQL
        );
        
        if ($res === false) {
            print "Error";
            return array(); 
        }

        return $this->fetchResults($res, \SQLITE3_ASSOC);
    }
    
    private function fetchResults(\Sqlite3Result $res, $type = \SQLITE3_ASSOC) : array {
        $return = array();
        
        while ($value = $res->fetchArray($type)) {
            $return[] = $value;
        }
        
        return $return;
    }
    
}

?>
