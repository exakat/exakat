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
namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class CloseNaming extends Analyzer {
    
    public function analyze() {
        $this->atomIs(array('Variable', 'Variablearray', 'Variableobject'))
             ->values('fullcode')
             ->unique();
        $res = $this->rawQuery();

        $variables = $res->toArray();
        if (empty($variables)) {
            return;
        }

        $closeVariables = array();
        foreach($variables as $v1) {
            foreach($variables as $v2) {
                if ($v1 === $v2) { continue; }
                if ($v1 . 's' === $v2) { continue; }
                if ($v1 === $v2 . 's') { continue; }
                
                if (levenshtein($v1, $v2) === 1) {
                    $closeVariables[$v1] = 1;
                    $closeVariables[$v2] = 1;
                }
            }
        }

        $closeVariables = array_keys($closeVariables);
        $closeVariables = array_filter( $closeVariables, function ($x) { return strlen($x) > 3; });
        if (!empty($closeVariables)) {
            $this->atomIs(array('Variable', 'Variablearray', 'Variableobject'))
                 ->is('fullcode', $closeVariables, self::CASE_SENSITIVE);
            $this->prepareQuery();
        }

        $uniques = array();
        foreach($variables as $u) {
            $v = mb_strtolower($u);
            array_collect_by($uniques, $v, $u);
        }

        $uniques = array_filter($uniques, function ($x) { return count($x) > 1; });
        if (!empty($uniques)) {
            $doubles = array_merge(...array_values($uniques));
    
            $this->atomIs(self::$VARIABLES_USER)
                 ->is('fullcode', $doubles, self::CASE_SENSITIVE);
            $this->prepareQuery();
        }

        // Identical, except for _ in the name
        $cleaned = array_map(function ($x) { return strtr('_', '', $x); }, $variables);
        $counts = array_count_values($cleaned);
        $doubles = array_filter($counts, function ($x) { return $x > 1; });
        
        if (!empty($uniques)) {
            $this->atomIs(self::$VARIABLES_USER)
                 ->is('fullcode', $doubles, self::NO_TRANSLATE);
            $this->prepareQuery();
        }

        // Identical, except for numbers
        $cleaned = array_map(function ($x) { return preg_replace('/\d/', '', $x); }, $variables);
        $counts = array_count_values($cleaned);
        $doubles = array_filter($counts, function ($x) { return $x > 1; });

        if (!empty($doubles)) {
            $this->atomIs(self::$VARIABLES_USER)
                 ->is('fullcode', $doubles, self::NO_TRANSLATE);
            $this->prepareQuery();
        }
    }
}

?>
