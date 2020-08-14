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

class IncompatibleSignature extends Analyzer {
    protected $phpVersion = '7.4-';

    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                    );
    }

    public function analyze(): void {
        // non-matching reference
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->hasOut('OVERWRITE')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->savePropertyAs('reference', 'referenced')
             ->inIs('ARGUMENT')
             ->outIs('OVERWRITE')
             ->outWithRank('ARGUMENT', 'ranked')
             ->notSamePropertyAs('reference', 'referenced')
             ->back('first');
        $this->prepareQuery();

        // non-matching argument count :
        // abstract : exact count
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->savePropertyAs('count', 'counted')
             ->outIs('OVERWRITE')
             ->is('abstract', true) //then, it is not private
             ->notSamePropertyAs('count', 'counted')
             ->back('first');
        $this->prepareQuery();

        // non-matching argument count :
        // non-abstract : count may be more but not less
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->savePropertyAs('count', 'counted')
             ->outIs('OVERWRITE')
             ->isNot('abstract', true)
             ->isMore('count', 'counted')
             ->back('first');
        $this->prepareQuery();

        // non-matching typehint
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->outIs('TYPEHINT')
             ->savePropertyAs('fullnspath', 'typehint')
             ->inIs('TYPEHINT')
             ->inIs('ARGUMENT')
             ->outIs('OVERWRITE')
             ->outWithRank('ARGUMENT', 'ranked')
             ->outIs('TYPEHINT')
             ->notSamePropertyAs('fullnspath', 'typehint')
             ->back('first');
        $this->prepareQuery();

        // non-matching return typehint
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('RETURNTYPE')
             ->savePropertyAs('fullnspath', 'typehint')
             ->inIs('RETURNTYPE')
             ->outIs('OVERWRITE')
             ->outIs('RETURNTYPE')
             ->notSamePropertyAs('fullnspath', 'typehint')
             ->back('first');
        $this->prepareQuery();

        // non-matching nullable
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->saveNullableAs('nullabled')
             ->inIs('ARGUMENT')
             ->outIs('OVERWRITE')
             ->outWithRank('ARGUMENT', 'ranked')
             ->saveNullableAs('nullabled2')
             ->raw('filter{nullabled != nullabled2; }')
             ->back('first');
        $this->prepareQuery();

        // non-matching return nullable
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->saveNullableAs('nullabled')
             ->outIs('OVERWRITE')
             ->saveNullableAs('nullabled2')
             ->notEqual('nullabled', 'nullabled2')
             ->raw('filter{nullabled != nullabled2; }')
             ->back('first');
        $this->prepareQuery();

        // non-matching visibility
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->savePropertyAs('visibility', 'v')
             ->outIs('OVERWRITE')
             ->raw(<<<'GREMLIN'
filter{ 
    if (it.get().properties("visibility").any()) { 
        if (v == "private") {
            it.get().value("visibility") in ["protected", "none", "public"];
        } else if (v == "protected") {
            it.get().value("visibility") in ["none", "public"];
        } else {
            false;
        }
    } else { 
        visibility != false; 
    }
}
GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
