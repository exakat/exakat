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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class RegexDelimiter extends Analyzer {
    public function analyze() {
        $pregFunctions = array('\\preg_match_all',
                               '\\preg_match',
                               '\\preg_replace',
                               '\\preg_replace_callback',
                               '\\preg_relace_callback_array',
                               );

        $this->atomFunctionIs($pregFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->outIsIE('CONCAT')
             ->is('rank', 0) // useful if previous is used
             ->atomIs('String')
             ->tokenIs(array('T_CONSTANT_ENCAPSED_STRING', 'T_ENCAPSED_AND_WHITESPACE'))
             ->noDelimiterIsNot('')
             ->raw(pregOptionE::FETCH_DELIMITER)
             ->raw('map{ delimiter; }')
             ->raw('groupCount("gf").cap("gf").sideEffect{ s = it.get().values().sum(); }');
        $types = $this->rawQuery()->toArray()[0];
        
        $storage = array_combine(array_keys($types), array_keys($types));
        
        $store = array();
        $total = 0;
        foreach($storage as $key => $v) {
            $c = empty($types[$v]) ? 0 : $types[$v];
            $store[] = array('key'   => $key,
                             'value' => $c);
            $total += $c;
        }
        Analyzer::$datastore->addRowAnalyzer($this->analyzerQuoted, $store);

        if ($total == 0) {
            return;
        }

        $types = array_filter($types, function ($x) use ($total) { return $x > 0 && $x / $total < 0.1; });
        $types =  array_keys($types);
        if (empty($types)) {
            return;
        }

        $this->atomFunctionIs($pregFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->outIsIE('CONCAT')
             ->is('rank', 0) // useful if previous is used
             ->atomIs('String')
             ->tokenIs(array('T_CONSTANT_ENCAPSED_STRING', 'T_ENCAPSED_AND_WHITESPACE'))
             ->noDelimiterIsNot('')
             ->raw('filter{ it.get().value("noDelimiter").substring(0, 1) in *** }', $types)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
