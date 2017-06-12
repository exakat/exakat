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
namespace Exakat\Analyzer\Exceptions;

use Exakat\Analyzer\Analyzer;

class AlreadyCaught extends Analyzer {
    public function analyze() {
        // Check that the class of on catch is not a parent of of the next catch
        // class A, class B extends A
        // catch(A $a) {} catch (B $b) <= then Catch A is wrong
        $this->atomIs('Try')
             ->outIs('CATCH')
             ->savePropertyAs('rank', 'rank')
             ->outIs('CLASS')
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->inIs('CATCH')
             ->outIs('CATCH')
             ->isMore('rank', 'rank')
             ->outIs('CLASS')
             ->classDefinition()
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('EXTENDS')
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
