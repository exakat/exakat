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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class MethodSignatureMustBeCompatible extends Analyzer {
    public function analyze() {
        // class x { function m() ;}
        // class xx extends x { function m($a) ;}
        // argument name may be arbitrary; argment default too.
        // typehint and number of arguments must always be the same
        $this->atomIs('Method') // No need for magicmethods
             ->savePropertyAs('count', 'signature')
             ->outIs('OVERWRITE')
             ->notSamePropertyAs('count', 'signature', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // Check if typehint is different between
        $this->atomIs('Method') // No need for magicmethods
             ->savePropertyAs('count', 'signature')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->outIs('TYPEHINT')
             ->savePropertyAs('fullnspath', 'typehint')
             ->back('first')
             ->outIs('OVERWRITE')
             ->samePropertyAs('count', 'signature', self::CASE_SENSITIVE)
             ->outWithRank('ARGUMENT', 'ranked')
             ->outIs('TYPEHINT')
             ->notSamePropertyAs('fullnspath', 'typehint')
             ->back('first');
        $this->prepareQuery();

        // no typehint in the original
        $this->atomIs('Method') // No need for magicmethods
             ->savePropertyAs('count', 'signature')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->hasNoOut('TYPEHINT')
             ->back('first')
             ->outIs('OVERWRITE')
             ->samePropertyAs('count', 'signature', self::CASE_SENSITIVE)
             ->outWithRank('ARGUMENT', 'ranked')
             ->hasOut('TYPEHINT')
             ->back('first');
        $this->prepareQuery();

        // no typehint in the parent
        $this->atomIs('Method') // No need for magicmethods
             ->savePropertyAs('count', 'signature')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->hasOut('TYPEHINT')
             ->back('first')
             ->outIs('OVERWRITE')
             ->samePropertyAs('count', 'signature', self::CASE_SENSITIVE)
             ->outWithRank('ARGUMENT', 'ranked')
             ->hasNoOut('TYPEHINT')
             ->back('first');
        $this->prepareQuery();

        // also checks for reference
        // also checks for ellipsis
    }
}

?>
