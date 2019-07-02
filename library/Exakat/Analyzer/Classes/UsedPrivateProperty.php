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

class UsedPrivateProperty extends Analyzer {
    public function analyze() {
        // property used in a staticproperty \a\b::$b
        $this->atomIs(array('Trait', 'Class', 'Classanonymous'))
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->_as('ppp')
             ->outIs('DEFINITION')
             ->atomIs('Staticproperty')
             ->goToClassTrait(array('Trait', 'Class', 'Classanonymous'))
             ->samePropertyAs('fullnspath', 'fqn')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a normal propertycall with $this $this->b
        $this->atomIs(array('Trait', 'Class', 'Classanonymous'))
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->_as('ppp')
             ->outIs('DEFINITION')
             ->atomIs('Member')
             ->outIs('OBJECT')
             ->isThis()
             ->inIs('OBJECT')
             ->back('ppp');
        $this->prepareQuery();
    }
}
?>
