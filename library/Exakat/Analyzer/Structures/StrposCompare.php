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

class StrposCompare extends Analyzer {
    public function analyze(): void {
        $operator = $this->loadIni('php_may_return_boolean_or_zero.ini', 'functions');
        $fullnspaths = makeFullnspath($operator);

        // if (.. == strpos(..)) {}
        $this->atomFunctionIs($fullnspaths)
             ->inIs('RIGHT')
             ->atomIs('Comparison')
             ->codeIs(array('==', '!='))
             ->outIs('LEFT')
             ->codeIs(array('0', "''", '""', 'null', 'false'))
             ->back('first')
             ->not(
                $this->side()
                     ->fullnspathIs('\preg_match')
                     ->outWithRank('ARGUMENT', 0)
                     ->not(
                        $this->side()
                             ->outIs('CONCAT')
                             ->atomIs(array('Variable', 'Array', 'Member', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
                     )
             );
        $this->prepareQuery();

        // if (strpos(..) == ..) {}
        $this->atomFunctionIs($fullnspaths)
             ->inIs('LEFT')
             ->atomIs('Comparison')
             ->codeIs(array('==', '!='))
             ->outIs('RIGHT')
             ->codeIs(array('0', "''", '""', 'null', 'false'))
             ->back('first')
             ->not(
                $this->side()
                     ->fullnspathIs('\preg_match')
                     ->outWithRank('ARGUMENT', 0)
                     ->not(
                        $this->side()
                             ->outIs('CONCAT')
                             ->atomIs(array('Variable', 'Array', 'Member', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
                     )
             );
        $this->prepareQuery();

        // if (strpos(..)) {}
        $this->atomFunctionIs($fullnspaths)
             ->inIsIE('CODE')  // parenthesis
             ->inIs('CONDITION')
             ->atomIs(array('Ifthen', 'While', 'Dowhile'))
             ->back('first')
             ->not(
                $this->side()
                     ->fullnspathIs('\preg_match')
                     ->outWithRank('ARGUMENT', 0)
                     ->not(
                        $this->side()
                             ->outIs('CONCAT')
                             ->atomIs(array('Variable', 'Array', 'Member', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
                     )
             );
        $this->prepareQuery();

        // if ($x = strpos(..)) {}
        $this->atomFunctionIs($fullnspaths)
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->inIsIE('CODE')  // parenthesis
             ->inIs('CONDITION')
             ->atomIs(array('Ifthen', 'While', 'Dowhile'))
             ->back('first')
             ->not(
                $this->side()
                     ->fullnspathIs('\preg_match')
                     ->outWithRank('ARGUMENT', 0)
                     ->not(
                        $this->side()
                             ->outIs('CONCAT')
                             ->atomIs(array('Variable', 'Array', 'Member', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
                     )
             );
        $this->prepareQuery();

        // if (($x = strpos(..)) == false) {}
        $this->atomFunctionIs($fullnspaths)
             ->inIs('RIGHT')
             ->as('result')
             ->atomIs('Assignation')
             ->inIs('CODE')
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs('Comparison')
             ->outIs(array('RIGHT', 'LEFT'))
             ->codeIs(array('0', "''", '""', 'null', 'false'))
             ->inIs(array('RIGHT', 'LEFT'))
             ->codeIs(array('==', '!='))
             ->inIs('CONDITION')
             ->atomIs(array('Ifthen', 'While', 'Dowhile'))
             ->back('first')
             ->not(
                $this->side()
                     ->fullnspathIs('\preg_match')
                     ->outWithRank('ARGUMENT', 0)
                     ->not(
                        $this->side()
                             ->outIs('CONCAT')
                             ->atomIs(array('Variable', 'Array', 'Member', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
                     )
             );
        $this->prepareQuery();
    }
}

?>
