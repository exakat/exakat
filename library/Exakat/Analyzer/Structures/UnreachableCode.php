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

class UnreachableCode extends Analyzer {
    public function dependsOn() {
        return array('Functions/KillsApp',
                    );
    }
    
    public function analyze() {
        // code after a halt_compiler is expected to be unreachable.
        $finalTokens = array('Gotolabel', 'Class', 'Function', 'Interface', 'Trait');

        // anything directly after those
        $this->atomIs(array('Return', 'Throw', 'Break', 'Continue', 'Goto', 'Exit'))
             ->nextSiblings()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        // anything directly after the try of a finally
        $this->atomIs(array('Return', 'Throw', 'Break', 'Continue', 'Goto', 'Exit'))
             ->inIs('EXPRESSION')
             ->inIs('BLOCK')
             ->atomIs('Finally')
             ->inIs('FINALLY')
             ->atomIs('Try')
             ->nextSiblings()
             ->atomIsNot($finalTokens);
        $this->prepareQuery();

        $this->atomFunctionIs('\\assert')
             ->outWithRank('ARGUMENT', 0)
             ->is('boolean', false)
             ->inIs('ARGUMENT')
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
             ->atomIs(array('Return', 'Continue', 'Break', 'Exit'))
             ->back('first')
             ->outIs('ELSE')
             ->outIs('EXPRESSION')
             ->atomIs(array('Return', 'Continue', 'Break', 'Exit'))
             ->back('first')
             ->nextSibling()
             ->atomIsNot($finalTokens)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
