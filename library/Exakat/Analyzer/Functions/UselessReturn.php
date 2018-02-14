<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class UselessReturn extends Analyzer {
    public function analyze() {
        // return in special functions
        $this->atomIs('Magicmethod')
             ->hasClassTrait() // avoid interfaces
             ->outIs('NAME')
             ->codeIs(array('__construct', '__destruct', '__set', '__clone', '__unset', '__wakeup'))
             ->inIs('NAME')

             // returning null or void is OK to terminate the function
             // May be spot this at other level than 1 of the function (this means after a test or a special branch)
             ->outIs('BLOCK')
             ->atomInsideNoAnonymous('Return')
             ->outIs('RETURN')
             ->atomIsNot('Void')
             ->back('first');
        $this->prepareQuery();

        // function that finally returns void. (the last return is useless)
        $this->atomIs(array('Function', 'Closure'))
             ->outIs('BLOCK')
             ->outWithRank('EXPRESSION', 'last')
             ->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
        
// @todo : spot such functions
//Also `__autoload`, methods used for autoloading and methods registered for shutdown, have no need to return anything.

    }
}

?>
