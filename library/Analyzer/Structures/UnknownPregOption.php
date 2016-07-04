<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Structures;

use Analyzer;

class UnknownPregOption extends Analyzer\Analyzer {
    public function analyze() {
        // Options list : eimsuxADJSUX (we use all letters, as unknown options are ignored or yield an error)
        $options = '[a-zA-Z]*[^eimsuxADJSUX][a-zA-Z]*';
        
        // function list
        $functions = array('\preg_match', '\preg_match_all', '\preg_replace', '\preg_replace_callback', '\preg_filter', '\preg_split', '\preg_quote', '\preg_grep');

        $prepareDelimiters = ' sideEffect{ 
         if (delimiter == "{") { delimiter = "\\\\{"; delimiterFinal = "\\\\}"; } 
    else if (delimiter == "(") { delimiter = "\\\\("; delimiterFinal = "\\\\)"; } 
    else if (delimiter == "[") { delimiter = "\\\\["; delimiterFinal = "\\\\]"; } 
    else if (delimiter == "|") { delimiter = "\\\\|"; delimiterFinal = "\\\\|"; } 
    else if (delimiter == "/") { delimiter = "\\\\/"; delimiterFinal = "\\\\/"; } 
    else                       { delimiterFinal = delimiter; } 
}';

        // preg_match with a string
        $this->atomFunctionIs($functions)
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->raw('sideEffect{ delimiter = it.get().value("noDelimiter").substring(0, 1); }')
             ->raw($prepareDelimiters)
             ->regexIs('noDelimiter', '^(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.')\\$')
             ->back('first');
        $this->prepareQuery();

        // With an interpolated string "a $x b"
        $this->atomFunctionIs($functions)
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_QUOTE')
             ->hasOut('CONCAT')
             ->outWithRank('CONCAT', 0)
             ->raw('sideEffect{ delimiter = it.get().value("noDelimiter").substring(0, 1); }')
             ->inIs('CONCAT')
             ->raw($prepareDelimiters)
             ->regexIs('fullcode', '^.(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.').\\$')
             ->back('first');
        $this->prepareQuery();

        // with a concatenation
        $this->atomFunctionIs($functions)
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             ->outWithRank('CONCAT', 0)
             ->raw('sideEffect{ delimiter = it.get().value("noDelimiter").substring(0, 1); }')
             ->inIs('CONCAT')
             ->raw($prepareDelimiters)
             ->regexIs('fullcode', '^.(" + delimiter + ").*(?<!\\\\\\\\)(" + delimiterFinal + ")('.$options.').\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
