<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Classes;

use Analyzer;

class IsNotFamily extends Analyzer\Analyzer {
    public function analyze() {
        // Staticmethodcall
        // Inside the class
        $this->atomIs('Staticmethodcall')
             ->hasClass()
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToClass()
             ->notSamePropertyAs('fullnspath', 'fnp')
             ->raw('where( __.emit().repeat( __.out("EXTENDS").in("DEFINITION") ).times('.self::MAX_LOOPING.')
                             .filter{ it.get().value("fullnspath") == fnp }
                             .count().is(eq(0))
                          )')
             ->back('first');
        $this->prepareQuery();

        // All non-in-class calls are OK
        $this->atomIs('Staticmethodcall')
             ->hasNoClass()
             ->hasNoTrait()
             ->outIs('CLASS')
             ->analyzerIsNot('self')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
