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

namespace Exakat\Reports\Data;

use Exakat\Analyzer\Analyzer;
use Exakat\Reports\Ambassador;
use Exakat\Reports\Reports;

class CloseNaming extends Data {
    public function prepare() {
        $counts = array();
        $begin = microtime(true);
        $res = $this->sqlite->query(<<<SQL
SELECT variable, COUNT(*) AS nb FROM variables 
    WHERE LENGTH(variable) > 3 AND 
          NOT(variable LIKE "{%") GROUP BY variable
SQL
);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[str_replace(array('&', '...'), '', $row['variable'])] = $row['nb'];
        }
        
        $variables = array_keys($counts);
        $variables[] = '$RELATIONITEMS';

        $results = array();
        // Only _ as difference
        foreach($variables as $variable) {
            if (!strpos($variable, '_')) { continue; }
            $v = str_replace('_', '', $variable);
            $r = array_filter( $variables, function($x) use ($v) { return str_replace('_', '', $x) === $v; });
            if (!empty($r)) {
                $results[$variable]['_'] = $r;
            }
        }

        // Only case as difference
        $lowerCase = array_map('mb_strtolower', $variables);
        $groupBy = array_count_values($lowerCase);
        $diff = array_keys(array_filter($groupBy, function($x) { return $x > 1;}));
        foreach($diff as $variable) {
            $r = array_filter( $variables, function($x) use ($variable) { return mb_strtolower($x) === mb_strtolower($variable); });
            if (!empty($r)) {
                $results[$variable]['case'] = $r;
            }
        }

        // Only numbers as difference
        $numbers = preg_grep('/[0-9]/', $variables);
        foreach($numbers as $variable) {
            $v = str_replace(array(0,1,2,3,4,5,6,7,8,9), '', $variable);
            $r = array_filter( $variables, function($x) use ($v) { return str_replace(array(0,1,2,3,4,5,6,7,8,9), '', $x) === $v; });
            if (!empty($r)) {
                $results[$variable]['numbers'] = $r;
            }
        }

        // One char difference
        $sizes = array_fill(4, 200, array());
        foreach($variables as $variable) {
            $sizes[strlen($variable)][] = $variable;
        }
        
        $sizes[] = [];// Extra one for the next loop
        foreach($sizes as $size => $vars) {
            foreach($vars as $var) {
                $r = array_filter( $sizes[$size + 1], function($x) use ($var) { return levenshtein($x, $var) === 1; });
                if (!empty($r)) {
                    $results[$variable]['one'] = $r;
                }
            }
        }

        // Group swap : aka confArray and arrayConf
        foreach($sizes as $size => $vars) {
            foreach($vars as $variable) {
                $r = array_filter( $vars, function($x) use ($variable) { return $this->groupSwap($x, $variable); });
                if (!empty($r)) {
                    $results[$variable]['swap'] = $r;
                }
            }
        }
        
        return $results;
    }

    private function groupSwap($a, $b) {
        $n = strlen($a) - 2;
        if (strpos($b, $a[1]) === false) { return false; }
        for($i = 1; $i < $n; $i++) {
            $c = '$'.substr($a, $i+1).substr($a, 1, $i);
            if ($c === $b) {
                return true;
            }
        }
    }
}

?>