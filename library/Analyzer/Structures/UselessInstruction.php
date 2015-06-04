<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class UselessInstruction extends Analyzer\Analyzer {
    public function analyze() {
        // Structures that should be put somewhere, and never left alone
        $this->atomIs('Sequence')
             ->outIs('ELEMENT')
             ->atomIs(array('Array', 'Addition', 'Multiplication', 'Property', 'Staticproperty', 'Boolean',
                            'Magicconstant', 'Staticconstant', 'Integer', 'Float', 'Sign', 'Nsname',
                            'Identifier', 'String', 'Instanceof', 'Bitshift', 'Logical', 'Comparison', 'Null',
                            'Heredoc', 'Power', 'Spaceship', 'Coalesce'))
             ->noAtomInside('Functioncall');
        $this->prepareQuery();
        
        // -$x = 3
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Sign');
        $this->prepareQuery();

        // closures that are not assigned to something (argument or variable)
        $this->atomIs('Sequence')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->is('lambda', true);
        $this->prepareQuery();

        // return $a++;
        $this->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Postplusplus')
             ->back('first');
        $this->prepareQuery();

        // array_merge($a);
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\array_merge', '\\array_merge_recursive', '\\array_replace'))
             ->isLess('args_count', 2)
             ->back('first');
        $this->prepareQuery();

        // foreach(@$a as $b);
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Noscream');
        $this->prepareQuery();

        // @$x = 3;
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Noscream')
             ->outIs('AT')
             ->atomIs('Variable')
             ->inIs('AT');
        $this->prepareQuery();

        // Closure with some operations
        $this->atomIs('Function')
             ->inIs('LEFT')
             ->atomIs(array('Addition', 'Multiplication'))
             ->back('first');
        $this->prepareQuery();

        // $x = 'a' . function ($a) {} (Concatenating a closure...)
        $this->atomIs('Function')
             ->inIs('CONCAT')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();

        // New in a instanceof (with/without parenthesis)
        $this->atomIs('New')
             ->inIsIE(array('CODE', 'LEFT'))
             ->inIs('CLASS')
             ->atomIs('Instanceof')
             ->back('first');
        $this->prepareQuery();

        // Empty string in a concatenation
        $this->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->code(array("''", '""'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
