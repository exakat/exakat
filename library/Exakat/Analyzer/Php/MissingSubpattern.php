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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class MissingSubpattern extends Analyzer {
     private $pregFunctions = array('\\preg_match_all',
                                    '\\preg_match',
                                    '\\preg_replace',
                                    '\\preg_replace_callback',
                                    '\\preg_relace_callback_array',
                                    );

    public function analyze() {

        //preg_match('/(a)b?/', 'adc', $r)
        $this->atomFunctionIs($this->pregFunctions)
             ->hasChildWithRank('ARGUMENT', 2) // subpatterns are captured
             ->outWithRank('ARGUMENT', 0)
             ->has('noDelimiter')
             ->regexIs('noDelimiter', '\\\\)\\\\?[^\\\\(]*[^a-zA-Z][a-zA-Z]*\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
