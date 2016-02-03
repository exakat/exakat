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

class pregOptionE extends Analyzer\Analyzer {
    public function analyze() {
        // delimiters
        $delimiters = '=~/|`%#\\$!,@\\\\{\\\\(\\\\[';

        // preg_match with a string
        $this->atomFunctionIs('\preg_replace')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->raw(' sideEffect{ 
    delimiter = it.noDelimiter[0]; 
    if (delimiter == "{") { delimiter = "\\\\{"; delimiterFinal = "\\\\}"; } 
    else if (delimiter == "(") { delimiter = "\\\\("; delimiterFinal = "\\\\)"; } 
    else if (delimiter == "[") { delimiter = "\\\\["; delimiterFinal = "\\\\]"; } 
    else { delimiterFinal = delimiter; } 
}')
             ->regex('noDelimiter', '^(" + delimiter + ").*(" + delimiterFinal + ")(.*e.*)\\$')
             ->back('first');
        $this->prepareQuery();

        // With an interpolated string "a $x b"
        $this->atomFunctionIs('\preg_replace')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->tokenIs('T_QUOTE')
             ->hasOut('CONTAINS')
             ->raw(' sideEffect{ 
    delimiter = it.out("CONTAINS").out("CONCAT").has("rank", 0).next().noDelimiter[0]; 
    if (delimiter == "{") { delimiter = "\\\\{"; delimiterFinal = "\\\\}"; } 
    else if (delimiter == "(") { delimiter = "\\\\("; delimiterFinal = "\\\\)"; } 
    else if (delimiter == "[") { delimiter = "\\\\["; delimiterFinal = "\\\\]"; } 
    else { delimiterFinal = delimiter; } 
}')
             ->regex('fullcode', '^.(" + delimiter + ").*(" + delimiterFinal + ")(.*e.*).\\$')
             ->back('first');
        $this->prepareQuery();

        // with a concatenation
        $this->atomFunctionIs('\preg_replace')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->atomIs('Concatenation')
             ->raw(' sideEffect{ 
    delimiter = it.out("CONCAT").has("rank", 0).next().noDelimiter[0]; 
    if (delimiter == "{") { delimiter = "\\\\{"; delimiterFinal = "\\\\}"; } 
    else if (delimiter == "(") { delimiter = "\\\\("; delimiterFinal = "\\\\)"; } 
    else if (delimiter == "[") { delimiter = "\\\\["; delimiterFinal = "\\\\]"; } 
    else { delimiterFinal = delimiter; } 
}')
             ->regex('fullcode', '^.(" + delimiter + ").*(" + delimiterFinal + ")(.*e.*).\\$')
             ->back('first');
        $this->prepareQuery();
// Actual letters used for Options in PHP imsxeuADSUXJ (others may yield an error) case is important
    }
}

?>
