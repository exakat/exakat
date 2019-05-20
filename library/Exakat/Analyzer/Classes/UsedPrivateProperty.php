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
        $this->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->_as('ppp')
             ->savePropertyAs('code', 'property')
             ->goToClassTrait(array('Trait', 'Class'))
             ->savePropertyAs('fullnspath', 'classe')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->filter(
                $this->side()
                     ->atomInsideNoDefinition('Staticproperty')
                     ->outIs('CLASS')
                     ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR', 'T_STATIC'))
                     ->fullnspathIs('classe')
                     ->inIs('CLASS')
                     ->outIs('MEMBER')
                     ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
             )
             ->back('ppp');
        $this->prepareQuery();

        // property used in a static property static::$b[] or self::$b[]
        $this->atomIs(array('Class', 'Trait'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->_as('ppp')
             ->savePropertyAs('code', 'x')
             ->back('first')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomInsideNoDefinition('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->fullnspathIs('fnp')
             ->inIs('CLASS')
             ->outIs('MEMBER')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'x', self::CASE_SENSITIVE)
             ->back('ppp');
        $this->prepareQuery();

        // property used in a normal propertycall with $this $this->b
        $this->atomIs(array('Class', 'Trait'))
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->savePropertyAs('propertyname', 'x')
             ->_as('ppp')
             ->back('first')
             ->outIs('DEFINITION')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->atomIs('Member')
             ->outIs('MEMBER')
             ->samePropertyAs('code', 'x', self::CASE_SENSITIVE)
             ->back('ppp');
        $this->prepareQuery();
    }
}
?>
