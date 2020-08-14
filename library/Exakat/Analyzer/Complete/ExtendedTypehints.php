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

namespace Exakat\Analyzer\Complete;

use Exakat\Analyzer\Analyzer;

class ExtendedTypehints extends Complete {
    public function dependsOn(): array {
        return array('Complete/SetParentDefinition',
                    );
    }
    public function analyze(): void {
       // returntype, contravariant (Interface => Class)
       // returntype, contravariant (Interface => Class => subclass)
       // returntype, contravariant (Interface => Subinterface => Class => subclass)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIsNot('Void')
             ->as('result')
             ->inIs('DEFINITION')
             ->atomIs(array('Interface', 'Class'))
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->raw('not(where(__.out("DEFINITION").where(eq("result"))))')
             ->addETo('DEFINITION', 'result');
        $this->prepareQuery();

        // arguments
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->as('result')
             ->inIs('DEFINITION')
             ->atomIs(array('Interface', 'Class'))
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->raw('not(where(__.out("DEFINITION").where(eq("result"))))')
             ->addETo('DEFINITION', 'result');
        $this->prepareQuery();

        // properties
        $this->atomIs('Ppp')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->as('result')
             ->inIs('DEFINITION')
             ->atomIs(array('Interface', 'Class'))
             ->goToAllChildren(Analyzer::EXCLUDE_SELF)
             ->raw('not(where(__.out("DEFINITION").where(eq("result"))))')
             ->addETo('DEFINITION', 'result');
        $this->prepareQuery();

        // variables?

        // special case for static (PHP 8.0)
    }
}

?>
