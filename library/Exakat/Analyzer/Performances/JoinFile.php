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


namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class JoinFile extends Analyzer {
    public function analyze() {
        $this->atomFunctionIs(array('\\join', '\\implode'))
             ->outWithRank('ARGUMENT', 1)
             ->functioncallIs('\\file')
             ->back('first');
        $this->prepareQuery();

        //$lines = file($file);
        //echo implode('',$lines);
        $this->atomFunctionIs('\\file')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'variable')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomInside('Functioncall')
             ->functioncallIs(array('\\join', '\\implode'))
             ->outWithRank('ARGUMENT', 1)
             ->samePropertyAs('fullcode', 'variable')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
