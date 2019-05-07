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

namespace Exakat\Reports\Data;

use Exakat\Analyzer\Analyzer;
use Exakat\Reports\Ambassador;
use Exakat\Reports\Reports;

class CloseNaming extends Data {
    public function prepare() {
        $res = $this->sqlite->query(<<<'SQL'
SELECT variable FROM variables 
    WHERE LENGTH(variable) > 3    AND 
          NOT(variable LIKE "{%") AND 
          NOT(variable LIKE "${%") 
          GROUP BY variable
SQL
);
        $variables = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $variables[] = $row['variable'];
        }

        $results = array();
        // Only _ as difference
        foreach($variables as $variable) {
            if (strpos($variable, '_') === false) {
                continue;
            }
            $v = str_replace('_', '', $variable);
            $r = array_filter( $variables, function ($x) use ($v) { return str_replace('_', '', $x) === $v; });
            if (count($r) > 1) {
                $results[$variable]['_'] = array_diff($r, array($variable));
            }
        }

        // Only case as difference
        $lowerCase = array_map('mb_strtolower', $variables);
        $groupBy = array_count_values($lowerCase);
        $diff = array_keys(array_filter($groupBy, function ($x) { return $x > 1;}));
        foreach($diff as $variable) {
            $r = array_filter( $variables, function ($x) use ($variable) { return mb_strtolower($x) === mb_strtolower($variable); });
            if (count($r) > 1) {
                $results[$variable]['case'] = array_diff($r, array($variable));
            }
        }

        // Only numbers as difference
        $numbers = preg_grep('/[0-9]/', $variables);
        foreach($numbers as $variable) {
            $v = str_replace(array(0,1,2,3,4,5,6,7,8,9), '', $variable);
            $r = array_filter( $variables, function ($x) use ($v) { return str_replace(array(0,1,2,3,4,5,6,7,8,9), '', $x) === $v; });
            if (count($r) > 1) {
                $results[$variable]['numbers'] = array_diff($r, array($variable));
            }
        }

        // One char difference
        $sizes = array_fill(4, 200, array());
        foreach($variables as $variable) {
            if (strlen($variable) > 200) {
                continue;
            }
            $sizes[strlen($variable)][] = $variable;
        }
        
        $sizes[] = array();// Extra one for the next loop
        foreach($sizes as $size => $vars) {
            foreach($vars as $variable) {
                $r = array_filter( $sizes[$size + 1], function ($x) use ($variable) { return levenshtein($x, $variable) === 1; });
                if (!empty($r)) {
                    $results[$variable]['one'] = $r;
                }
            }
        }

        // Group swap : aka confArray and arrayConf
        foreach($sizes as $size => $vars) {
            if ($size < 5) { continue; }
            foreach($vars as $variable) {
                $r = array_filter( $vars, function ($x) use ($variable) { return $this->groupSwap($x, $variable); });
                if (!empty($r)) {
                    $results[$variable]['swap'] = $r;
                }
            }
        }
        
        return $results;
    }

    private function groupSwap($a, $b) {
        $n = strlen($a) - 3;
        if (strpos($b, $a[1]) === false) { return false; }
        for($i = 2; $i < $n; $i++) {
            $d = substr($a, $i + 1);
            $e = substr($a, 1, $i);
            if ($d === $e) { continue; }
            $c = '$' . $d . $e;
            if ($c === $b) {
                return true;
            }
        }
    }
}

?>