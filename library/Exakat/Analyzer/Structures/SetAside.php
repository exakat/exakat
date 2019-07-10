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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class SetAside extends Analyzer {
    public function analyze() {
        // $b = $a; $a = 3; $a = $b;
        // local variable
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('DEFINITION')
             ->atomIs('Variabledefinition')
             ->savePropertyAs('code', 'name')
             ->filter(
                $this->side()
                     ->outIs('DEFINITION')
                     ->inIs('LEFT')
                     ->atomIs('Assignation')
                     ->raw('count().is(gte(2))')
             )
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             
             // second variable
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // $b = $this->a; $this->a = 3; $this->a = $b;
        // property
        $this->atomIs(self::$CLASSES_ALL)
             ->outIs('PPP')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->savePropertyAs('propertyname', 'property')
             ->filter(
                $this->side()
                     ->outIs('DEFINITION')
                     ->inIs('LEFT')
                     ->atomIs('Assignation')
                     ->raw('count().is(gte(2))')
             )
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             
             // local variable
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Member')
             ->outIs('MEMBER')
             ->tokenIs('T_STRING')
             ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
             ->goToFunction();
        $this->prepareQuery();
    }
}

?>
