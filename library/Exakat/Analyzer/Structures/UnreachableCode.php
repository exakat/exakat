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

class UnreachableCode extends Analyzer {
    public function dependsOn() {
        return array('Functions/KillsApp');
    }
    
    public function analyze() {
        // code after a halt_compiler is expected to be unreachable.
        $finalTokens = array('Gotolabel', 'Class', 'Function', 'Interface', 'Trait');

        $this->atomIs('Return')
             ->nextSiblings()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        $this->atomIs('Throw')
             ->nextSiblings()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        $this->atomIs('Break')
             ->nextSiblings()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        $this->atomIs('Continue')
             ->nextSiblings()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        $this->atomIs('Goto')
             ->nextSiblings()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        $this->atomFunctionIs(array('\\exit', '\\die'))
             ->nextSiblings()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->functionDefinition()
             ->analyzerIs('Functions/KillsApp')
             ->back('first')
             ->nextSibling()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        $this->atomIs('Ifthen')
             ->outIs('THEN')
             ->outIs('EXPRESSION')
             ->atomIs(array('Return', 'Continue', 'Break'))
             ->back('first')
             ->outIs('ELSE')
             ->outIs('EXPRESSION')
             ->atomIs(array('Return', 'Continue', 'Break'))
             ->back('first')
             ->nextSibling()
             ->atomIsNot($finalTokens)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
