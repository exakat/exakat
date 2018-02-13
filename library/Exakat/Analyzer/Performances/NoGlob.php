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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class NoGlob extends Analyzer {
    public function analyze() {
        // glob() with no second argument (without GLOB_NOSORT)
        $this->atomFunctionIs('\\glob')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // glob() with second argument (without GLOB_NOSORT)
        $this->atomFunctionIs('\\glob')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIsNot('\GLOB_NOSORT')
             ->back('first');
        $this->prepareQuery();

        // glob() with second argument (without GLOB_NOSORT)
        $this->atomFunctionIs('\\glob')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('Logical')
             ->raw('not( where( __.out("LEFT", "RIGHT").hasLabel("Identifier", "Nsname").has("fullnspath", "\\\\GLOB_NOSORT") ) )')
             ->back('first');
        $this->prepareQuery();

        // scandir() wit no second argument (without GLOB_NOSORT)
        $this->atomFunctionIs('\\scandir')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

    }
}

?>
