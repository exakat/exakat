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

class UselessParenthesis extends Analyzer {
    // if ( ($condition) )
    public function analyze() {
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // clone
        $this->atomIs('Clone')
             ->outIs('CLONE')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // yield
        $this->atomIs(array('Yield', 'Yieldfrom'))
             ->outIs('YIELD')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // while
        $this->atomIs('While')
             ->outIs('CONDITION')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // dowhile
        $this->atomIs('Dowhile')
             ->outIs('CONDITION')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // switch
        $this->atomIs('Switch')
             ->outIs('NAME')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // $y = (1);
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // ($y) == (1);
        $this->atomIs('Comparison')
             ->outIs(array('RIGHT', 'LEFT'))
             ->atomIs('Parenthesis')
             ->outIs('CODE')
             ->atomIsNot('Assignation')
             ->inIs('CODE');
        $this->prepareQuery();

        // ($a = $b) == $c : NOT A CASE
        $this->atomIs('Comparison')
             ->outIs('RIGHT')
             ->atomIs('Parenthesis')
             ->outIs('CODE')
             ->atomIs('Assignation')
             ->inIs('CODE');
        $this->prepareQuery();
        
        // f(($x))
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Parenthesis');
        $this->prepareQuery();
        
        // (expression);
        $this->atomIs('Parenthesis')
             ->hasIn('EXPRESSION');
        $this->prepareQuery();

        // (literal);
        $this->atomIs('Parenthesis')
             ->outIs('CODE')
             ->atomIs(array('Integer', 'Real', 'Boolean', 'Identifier', 'Variable',
                            'Magicconstant', 'Null', 'Functioncall', 'Member', 'Methodcall',
                            'Staticmethodcall', 'Staticconstant', 'Staticproperty'));
        $this->prepareQuery();

        //$d = ((($a)+$b)+$c);
        $this->atomIs('Addition')
             ->inIs('CODE')
             ->atomIs('Parenthesis')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Addition');
        $this->prepareQuery();

        //$d = ((($a)*$b)*$c);
        $this->atomIs('Multiplication')
             ->inIs('CODE')
             ->atomIs('Parenthesis')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Multiplication');
        $this->prepareQuery();

        //function foo($c = (PHP_OS == 1 ? 1 : 2) ){}
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->outIs('DEFAULT')
             ->atomIs('Parenthesis');
        $this->prepareQuery();
        
    }
}

?>
