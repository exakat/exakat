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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UnknownPregOption extends Analyzer {
    public static $functions = array('\preg_match',
                                     '\preg_match_all',
                                     '\preg_replace',
                                     '\preg_replace_callback',
                                     '\preg_filter',
                                     '\preg_grep',
                                     '\preg_split',
                                     );

    public function analyze() {
        // Options list : eimsuxADJSUX (we use all letters, as unknown options are ignored or yield an error)
        $options = '[a-zA-Z]*[^eimsuxADJSUX][a-zA-Z]*';
        
        // preg_match with a string
        $this->atomFunctionIs(self::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->isNot('noDelimiter', '')
             ->raw(pregOptionE::FETCH_DELIMITER)
             ->raw(pregOptionE::MAKE_DELIMITER_FINAL)
             ->raw('filter{ it.get().value("noDelimiter") != delimiter + delimiterFinal ; }')
             ->regexIs('noDelimiter', '^(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.')\\$')
             ->back('first');
        $this->prepareQuery();

        // With an interpolated string "a $x b"
        $this->atomFunctionIs(self::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_QUOTE')
             ->hasOut('CONCAT')
             ->outWithRank('CONCAT', 0)
             ->atomIs('String')
             ->isNot('noDelimiter', '')
             ->raw(pregOptionE::FETCH_DELIMITER)
             ->inIs('CONCAT')
             ->raw(pregOptionE::MAKE_DELIMITER_FINAL)
             ->regexIs('fullcode', '^.(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.').\\$')
             ->back('first');
        $this->prepareQuery();

        // with a concatenation
        $this->atomFunctionIs(self::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             ->_as('concat')
             ->outWithRank('CONCAT', 0)
             ->atomIs('String')
             ->outIsIE('CONCAT') // In case it is an interpolated string
             ->is('rank', 0)     // Same as above, but may be double when there is no interpolation
             ->atomIs('String')
             ->isNot('noDelimiter', '')
             ->raw(pregOptionE::FETCH_DELIMITER)
             ->raw(pregOptionE::MAKE_DELIMITER_FINAL)
             ->back('concat')
             ->regexIs('fullcode', '^.(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.').\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
