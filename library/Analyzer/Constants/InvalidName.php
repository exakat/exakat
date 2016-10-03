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


namespace Analyzer\Constants;

use Analyzer;

class InvalidName extends Analyzer\Analyzer {
    public function analyze() {
        // case-sensitive constants
        $this->atomFunctionIs('\\define')
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             // \ is an acceptable character in constants (NS separator) => \\\\\\\\ (yes, 8 \)
             ->regexIsNot('noDelimiter', '^[a-zA-Z\\\\\\\\_\\\\u007f-\\\\u00ff][a-zA-Z0-9\\\\\\\\_\\\\u007f-\\\\u00ff]*\\$');
        $this->prepareQuery();

        $invalidNames = $this->loadIni('php_keywords.ini', 'keyword');
        $invalidNames = "'".implode("', '", $invalidNames)."'";
        
        // case-insensitive constants
        $this->atomFunctionIs('\\define')
             ->analyzerIsNot('self')
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->regexIs('noDelimiter', '\\\\\\\\')
             // \ is an acceptable character in constants (NS separator) => \\\\\\\\ (yes, 8 \)
             ->filter('['.$invalidNames.'].intersect(it.get().value("noDelimiter").tokenize("\\\\\\\\")).size() > 0');
        $this->prepareQuery();


    }
}

?>
