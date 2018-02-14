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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class pregOptionE extends Analyzer {
        const FETCH_DELIMITER = <<<GREMLIN
sideEffect{ 
    base = it.get().value("noDelimiter").replaceAll("\\\\s", "");
    delimiter = base[0];
    if (delimiter == '\\\\') {
        delimiter = "\\\\\\\\" + base[1];
    }

}
GREMLIN;
        
        const MAKE_DELIMITER_FINAL = <<<GREMLIN
sideEffect{ 
    if (delimiter == "{") { delimiter = "\\\\{"; delimiterFinal = "\\\\}"; } 
    else if (delimiter == "(") { delimiter = "\\\\("; delimiterFinal = "\\\\)"; } 
    else if (delimiter == "[") { delimiter = "\\\\["; delimiterFinal = "\\\\]"; } 
    else if (delimiter == "*") { delimiter = "\\\\*"; delimiterFinal = "\\\\*"; } 
    else if (delimiter == "|") { delimiter = "\\\\|"; delimiterFinal = "\\\\|"; } 
    else if (delimiter == "?") { delimiter = "\\\\?"; delimiterFinal = "\\\\?"; } 
    else if (delimiter == "+") { delimiter = "\\\\+"; delimiterFinal = "\\\\+"; } 
    else { delimiterFinal = delimiter; } 
}
.filter{ delimiter != "\\\\\\\\" }
GREMLIN;

    public function analyze() {
        $functions = '\preg_replace';
        // delimiters
        $delimiters = '=~/|`%#\\$\\*!,@\\\\{\\\\(\\\\[~';
        
        // preg_match with a string
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->isNot('noDelimiter', '')
             ->raw(self::FETCH_DELIMITER)
             ->raw(self::MAKE_DELIMITER_FINAL)
             ->regexIs('noDelimiter', '^(" + delimiter + ").*(" + delimiterFinal + ")([a-df-zA-Z]*?e[a-df-zA-Z]*?)\\$')
             ->back('first');
        $this->prepareQuery();

        // With an interpolated string "a $x b"
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->outWithRank('CONCAT', 0)
             ->isNot('noDelimiter', '')
             ->raw(self::FETCH_DELIMITER)
             ->inIs('CONCAT')
             ->raw(self::MAKE_DELIMITER_FINAL)
             ->regexIs('fullcode', '^.(" + delimiter + ").*(" + delimiterFinal + ")([a-df-zA-Z]*?e[a-df-zA-Z]*?).\\$')
             ->back('first');
        $this->prepareQuery();

        // with a concatenation
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             ->outWithRank('CONCAT', 0)
             ->atomIs('String')
             ->outIsIE('CONCAT')
             ->atomIs('String')
             ->is('rank', 0)
             ->isNot('noDelimiter', '')
             ->raw(self::FETCH_DELIMITER)
             ->inIsIE('CONCAT')
             ->raw(self::MAKE_DELIMITER_FINAL)
             ->regexIs('fullcode', '^.(" + delimiter + ").*(" + delimiterFinal + ")([a-df-zA-Z]*?e[a-df-zA-Z]*?).\\$')
             ->back('first');
        $this->prepareQuery();
// Actual letters used for Options in PHP imsxeuADSUXJ (others may yield an error) case is important

        $this->atomFunctionIs(array('\mb_eregi_replace', '\mb_ereg_replace'))
             ->outWithRank('ARGUMENT', 3)
             ->atomIs('String')
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->regexIs('noDelimiter', 'e')
             ->back('first');
         $this->prepareQuery();
    }
}

?>