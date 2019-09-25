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
    public function dependsOn() {
        return array('Complete/OverwrittenMethods',
                    );
    }

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

        // Check if reference is the same between the versions
        $this->atomIs('Method') // No need for magicmethods
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->savePropertyAs('reference', 'referenced')
             ->back('first')
             ->outIs('OVERWRITE')
             ->outWithRank('ARGUMENT', 'ranked')
             ->notSamePropertyAs('reference', 'referenced')
             ->back('first');
        $this->prepareQuery();

        // Check if variadic is the same between the versions
        $this->atomIs('Method') // No need for magicmethods
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->savePropertyAs('variadic', 'variadiced')
             ->back('first')
             ->outIs('OVERWRITE')
             ->outWithRank('ARGUMENT', 'ranked')
             ->notSamePropertyAs('variadic', 'variadiced')
             ->back('first');
        $this->prepareQuery();

        // Check if return typehint is different between
        $this->atomIs('Method') // No need for magicmethods
             ->outIs('RETURNTYPE')
             ->savePropertyAs('fullnspath', 'typehint')
             ->back('first')
             ->outIs('OVERWRITE')
             ->outIs('RETURNTYPE')
             ->notSamePropertyAs('fullnspath', 'typehint')
             ->back('first');
        $this->prepareQuery();
        
        // also checks for reference
        // also checks for ellipsis
    }
}

?>
