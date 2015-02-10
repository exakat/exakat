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

class StrposCompare extends Analyzer\Analyzer {
    public function analyze() {
        $operator = $this->loadIni('php_may_return_boolean_or_zero.ini');
        $operator = $operator['functions'];
        
        // if (.. == strpos(..)) {}
        $this->atomIs('Functioncall')
             ->_as('result')
             ->code($operator)
             ->inIs('RIGHT')
             ->atomIs('Comparison')
             ->code(array('==', '!='))
             ->outIs('LEFT')
             ->code(array('0', '', 'null', 'false'))
             ->back('result');
        $this->prepareQuery();

        // if (strpos(..) == ..) {}
        $this->atomIs('Functioncall')
             ->_as('result')
             ->code($operator)
             ->inIs('LEFT')
             ->atomIs('Comparison')
             ->code(array('==', '!='))
             ->outIs('RIGHT')
             ->code(array('0', '', 'null', 'false'))
             ->back('result');
        $this->prepareQuery();

        // if (strpos(..)) {}
        $this->atomIs('Functioncall')
             ->_as('result')
             ->code($operator)
             ->inIs('CODE')
             ->inIs('CONDITION')
             ->atomIs('Ifthen')
             ->back('result');
        $this->prepareQuery();

        // if ($x = strpos(..)) {}
        $this->atomIs('Functioncall')
             ->code($operator)
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->_as('result')
             ->inIs('CODE')
             ->inIs('CONDITION')
             ->atomIs('Ifthen')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
