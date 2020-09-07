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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class MethodSignatureMustBeCompatible extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                    );
    }

    public function analyze(): void {
        // class x { function m() ;}
        // class xx extends x { function m($a) ;}
        // argument name may be arbitrary; argment default too.
        // typehint and number of arguments must always be the same
        $this->atomIs('Method') // No need for magicmethods
             ->isNot('visibility', 'private')
             ->analyzerIsNot('self')
             ->savePropertyAs('count', 'signature')
             ->outIs('OVERWRITE')
             ->notSamePropertyAs('count', 'signature', self::CASE_SENSITIVE)
             ->isNot('visibility', 'private')
             ->back('first');
        $this->prepareQuery();

        // Check if typehint is different between
        // Typehint hierarchy is not checked yet
        $this->atomIs('Method') // No need for magicmethods
             ->isNot('visibility', 'private')
             ->analyzerIsNot('self')
             ->savePropertyAs('count', 'signature')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->collectTypehints('typehints')
             ->back('first')

             ->outIs('OVERWRITE')
             ->isNot('visibility', 'private')
             ->samePropertyAs('count', 'signature', self::CASE_SENSITIVE)
             ->outWithRank('ARGUMENT', 'ranked')
             ->collectTypehints('typehints2')

             ->raw('filter{ typehints2.sort() != typehints.sort();}')
             ->back('first');
        $this->prepareQuery();


        // Check if reference is the same between the versions
        $this->atomIs('Method') // No need for magicmethods
             ->isNot('visibility', 'private')
             ->analyzerIsNot('self')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->savePropertyAs('reference', 'referenced')
             ->back('first')
             ->outIs('OVERWRITE')
             ->isNot('visibility', 'private')
             ->outWithRank('ARGUMENT', 'ranked')
             ->notSamePropertyAs('reference', 'referenced')
             ->back('first');
        $this->prepareQuery();

        // Check if variadic is the same between the versions
        $this->atomIs('Method') // No need for magicmethods
             ->isNot('visibility', 'private')
             ->analyzerIsNot('self')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->savePropertyAs('variadic', 'variadiced')
             ->back('first')
             ->outIs('OVERWRITE')
             ->isNot('visibility', 'private')
             ->outWithRank('ARGUMENT', 'ranked')
             ->notSamePropertyAs('variadic', 'variadiced')
             ->back('first');
        $this->prepareQuery();

        // Check if return typehint is different between
        $this->atomIs('Method') // No need for magicmethods
             ->isNot('visibility', 'private')
             ->analyzerIsNot('self')
             ->collectTypehints('typehints')

             ->outIs('OVERWRITE')
             ->isNot('visibility', 'private')
             ->collectTypehints('typehints2')
             ->raw('filter{ typehints2.sort() != typehints.sort();}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
