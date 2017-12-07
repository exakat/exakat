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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UsedPrivateProperty extends Analyzer {

    public function analyze() {
        // property used in a staticproperty \a\b::$b
        $this->atomIs('Ppp')
             ->hasOut('PRIVATE')

             ->outIs('PPP')
             ->_as('ppp')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'property')
             ->goToClassTrait()
             ->hasName()
             ->savePropertyAs('fullnspath', 'classe')
             ->outIs('METHOD')
             ->raw('where( __.repeat( __.out('.$this->linksDown.') ).emit( hasLabel("Staticproperty") ).times('.self::MAX_LOOPING.')
                             .out("CLASS").filter{ it.get().value("token") in ["T_STRING", "T_NS_SEPARATOR", "T_STATIC" ] }.filter{ it.get().value("fullnspath") == classe }.in("CLASS")
                             .out("MEMBER").filter{ it.get().value("code") == property }
                             .count().is(neq(0)) )')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a static property static::$b[] or self::$b[]
        $this->atomIs(array('Class', 'Trait'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->hasOut('PRIVATE')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->_as('ppp')
             ->savePropertyAs('code', 'x')
             ->back('first')
             ->outIs('METHOD')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->fullnspathIs('fnp')
             ->inIs('CLASS')
             ->outIs('MEMBER')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'x')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a normal methodcall with $this $this->b()
        $this->atomIs(array('Class', 'Trait'))
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->hasOut('PRIVATE')
             ->outIs('PPP')
             ->savePropertyAs('propertyname', 'x')
             ->_as('ppp')
             ->back('first')
             ->outIs('METHOD')
             ->atomInside('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->outIs('MEMBER')
             ->samePropertyAs('code', 'x')
             ->back('ppp');
        $this->prepareQuery();
    }
}
?>
