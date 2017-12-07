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

namespace Exakat\Analyzer\Traits;

use Exakat\Analyzer\Analyzer;

class DependantTrait extends Analyzer {
    public function analyze() {
        // Case for $this->method()
        $this->atomIs('Trait')
             ->outIs('METHOD')
             ->outIs('BLOCK')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'method')
             ->goToTrait()
             ->raw('not( where( __.emit(hasLabel("Trait")).repeat( out("USE").hasLabel("Use").out("USE").in("DEFINITION") ).times('.self::MAX_LOOPING.')
                             .out("METHOD").hasLabel("Method").out("NAME").filter{ it.get().value("code") == method } ) )')
             ->back('first');
        $this->prepareQuery();

        // Case for $this->$properties
        $this->atomIs('Trait')
             ->outIs('METHOD')
             ->outIs('BLOCK')
             ->atomInside('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->outIs('MEMBER')
             ->outIsIE('VARIABLE') // for arrays
             ->tokenIs('T_STRING')

             ->savePropertyAs('code', 'property')
             ->goToTrait()
             ->raw('not( where( __.emit().repeat( out("USE").hasLabel("Use").out("USE").in("DEFINITION") ).times('.self::MAX_LOOPING.').hasLabel("Trait")
                             .out("PPP").hasLabel("Ppp").out("PPP").filter{ it.get().value("propertyname") == property } ) )')
             ->back('first');
        $this->prepareQuery();

        // Case for class::$properties
        $this->atomIs('Trait')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('METHOD')
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('MEMBER')
             ->tokenIs('T_VARIABLE')
             ->savePropertyAs('code', 'property')
             ->goToTrait()
             ->raw('not( where( __.emit(hasLabel("Trait")).repeat( out("USE").hasLabel("Use").out("USE").in("DEFINITION") ).times('.self::MAX_LOOPING.')
                             .out("PPP").hasLabel("Ppp").out("PPP").coalesce(__.out("LEFT"), __.filter{ true }).filter{ it.get().value("code") == property } ) )')
             ->back('first');
        $this->prepareQuery();

        // Case for class::methodcall
        $this->atomIs('Trait')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('METHOD')
             ->outIs('BLOCK')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->samePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'method')
             ->goToTrait()
             ->raw('not( where( __.emit(hasLabel("Trait")).repeat( out("USE").hasLabel("Use").out("USE").in("DEFINITION") ).times('.self::MAX_LOOPING.')
                             .out("METHOD").hasLabel("Method").out("NAME").filter{ it.get().value("code") == method } ) )')
             ->back('first');
        $this->prepareQuery();

        // Case for class::methodcall

    }
}

?>
