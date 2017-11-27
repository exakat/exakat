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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Structures\pregOptionE;
use Exakat\Analyzer\Structures\UnknownPregOption;

class UnknownPcre2Option extends Analyzer {
    public function analyze() {
        // Options list : S and X
//        $options = '[a-zA-Z]*[^eimsuxADJU][a-zA-Z]*';
        $options = '[a-zA-Z]*[S][a-zA-Z]*';
        
        // preg_match with a string
        $this->atomFunctionIs(UnknownPregOption::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->isNot('noDelimiter', '')
             ->raw(pregOptionE::FETCH_DELIMITER)
             ->raw(pregOptionE::MAKE_DELIMITER_FINAL)
             ->raw('filter{ it.get().value("noDelimiter").length() >= (delimiter + delimiterFinal).length() }')
             ->raw('filter{ (it.get().value("noDelimiter") =~ delimiter + ".*" + delimiterFinal ).getCount() != 0 }')
             ->regexIs('noDelimiter', '^(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.')\\$')
             ->back('first');
        $this->prepareQuery();

        // With an interpolated string "a $x b"
        $this->atomFunctionIs(UnknownPregOption::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_QUOTE')
             ->hasOut('CONCAT')
             ->outWithRank('CONCAT', 0)
             ->atomIs('String')
             ->isNot('noDelimiter', '')
             ->raw(pregOptionE::FETCH_DELIMITER)
             ->inIs('CONCAT')
             ->raw(pregOptionE::MAKE_DELIMITER_FINAL)
             ->raw('filter{ it.get().value("noDelimiter").length() >= (delimiter + delimiterFinal).length() }')
             ->raw('filter{ (it.get().value("noDelimiter") =~ delimiter + ".*" + delimiterFinal ).getCount() != 0 }')
             ->regexIs('fullcode', '^.(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.').\\$')
             ->back('first');
        $this->prepareQuery();

        // with a concatenation
        $this->atomFunctionIs(UnknownPregOption::$functions)
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
             ->raw('filter{ it.get().value("fullcode").length() >= (delimiter + delimiterFinal).length() + 2 }')
             ->raw('filter{ (it.get().value("fullcode") =~ delimiter + ".*" + delimiterFinal ).getCount() != 0 }')
             ->regexIs('fullcode', '^.(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.').\\$')
             ->back('first');
        $this->prepareQuery();

        // Searching for \y in strings
        // preg_match with a string
        // Those letters will fail. Other will be useful, or fail for good reason (\u needs a codepoint).
        $letters = 'gijkmoqyFIJMOTY';
        $this->atomFunctionIs(UnknownPregOption::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->isNot('noDelimiter', '')
             ->regexIs('noDelimiter', '\\\\\\\\['.$letters.']')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
