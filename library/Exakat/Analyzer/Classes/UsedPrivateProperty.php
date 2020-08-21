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

class UsedPrivateProperty extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenProperties',
                    );
    }

    public function analyze(): void {
        // property used in a staticproperty \a\b::$b
        // a property must be read to be used.
        $this->atomIs(self::CLASSES_TRAITS)
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->as('ppp')
             ->outIs('DEFINITION')
             ->atomIs('Staticproperty')
             ->is('isRead', true)
             ->goToClassTrait(self::CLASSES_TRAITS)
             ->samePropertyAs('fullnspath', 'fqn')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a normal propertycall with $this $this->b
        // a property must be read to be used.
        $this->atomIs(self::CLASSES_TRAITS)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->as('ppp')
             ->outIs('DEFINITION')
             ->atomIs('Member')
             ->is('isRead', true)
             ->outIs('OBJECT')
             ->isThis()
             ->inIs('OBJECT')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a normal propertycall with $this $this->b, from a trait
        // a property must be read to be used.
        $this->atomIs(self::CLASSES_TRAITS)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->as('ppp')
             ->outIs('OVERWRITE')
             ->hasTrait()
             ->outIs('DEFINITION')
             ->atomIs('Member')
             ->is('isRead', true)
             ->outIs('OBJECT')
             ->isThis()
             ->inIs('OBJECT')
             ->back('ppp');
        $this->prepareQuery();
    }
}
?>
