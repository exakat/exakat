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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ReuseVariable extends Analyzer {
    public function analyze() {
        $expressions = array('Functioncall',
                             'Methodcall',
                             'Staticmethodcall',
                             'Concatenation',
                             'Parenthesis',
                             'Addition',
                             'Multiplication',
                             'Bitshift',
                             'Power',
                             'Logical',
                             'Not',
                             'Cast',
                             'Arrayliteral',
                             'Sign',
                             'Comparison',
                             'Ternary',
                             'Postplusplus',
                             'Preplusplus',
                             'Coalesce',
                             'Isset',
                             'Empty',
                            );
        // $a = foo($b);
        // if (foo($b)) {}
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs($expressions)
             ->analyzerIsNot('self')
             ->savePropertyAs('fullcode', 'expression')
             ->savePropertyAs('id', 'unique')
             ->savePropertyAs('line', 'row')
             ->isNotEmptyArray()
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInsideNoDefinition($expressions)
             ->fullcodeVariableIs('expression')
             ->notSamePropertyAs('id', 'unique')
             ->isMore('line', 'row');
        $this->prepareQuery();
    }
}

?>
