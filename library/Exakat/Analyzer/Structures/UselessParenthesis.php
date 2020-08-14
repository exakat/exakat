<?php declare(strict_types = 1);
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

class UselessParenthesis extends Analyzer {
    // if ( ($condition) )
    public function analyze(): void {
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // clone
        $this->atomIs('Clone')
             ->outIs('CLONE')
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();

        // yield
        $this->atomIs(array('Yield', 'Yieldfrom'))
             ->outIs('YIELD')
             ->atomIs('Parenthesis')
             ->back('first');
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
        $this->atomIs(self::SWITCH_ALL)
             ->outIs('CONDITION')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // $y = (1);
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Parenthesis')
             ->not(
                $this->side()
                     ->outIs('CODE')
                     ->atomIs('Logical')
                     ->tokenIs(array('T_LOGICAL_XOR', 'T_LOGICAL_AND', 'T_LOGICAL_OR'))
             );
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
             ->outIs('ARGUMENT')
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();

        // (expression);
        $this->atomIs('Parenthesis')
             ->hasIn('EXPRESSION');
        $this->prepareQuery();

        // (literal);
        $this->atomIs('Parenthesis')
             ->analyzerIsNot('self')
             ->outIs('CODE')
             ->atomIs(array('Integer', 'Float', 'Boolean', 'Identifier', 'Variable',
                            'Magicconstant', 'Null', 'Functioncall', 'Member', 'Methodcall',
                            'Staticmethodcall', 'Staticconstant', 'Staticproperty'))
             ->back('first');
        $this->prepareQuery();

        //$d = ((($a)+$b)+$c);
        $this->atomIs('Addition')
             ->analyzerIsNot('self')
             ->inIs('CODE')
             ->atomIs('Parenthesis')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Addition')
             ->back('first');
        $this->prepareQuery();

        //$d = ((($a)*$b)*$c);
        $this->atomIs('Multiplication')
             ->analyzerIsNot('self')
             ->inIs('CODE')
             ->atomIs('Parenthesis')
             ->as('results')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Multiplication')
             ->back('results');
        $this->prepareQuery();

        //function foo($c = (PHP_OS == 1 ? 1 : 2) ){}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('DEFAULT')
             ->atomIs('Parenthesis')
             ->hasNoIn('RIGHT')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
