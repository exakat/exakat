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

class IsNotFamily extends Analyzer {
    public function analyze() {
        // Staticmethodcall
        // Inside the class
        $this->atomIs('Staticmethodcall')
             ->hasClass()
             ->outIs('CLASS')
             ->atomIsNot(array('Self', 'Parent', 'Static'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToClass()
             ->atomIs('Class')
             ->notSamePropertyAs('fullnspath', 'fnp')
             ->raw('not( where( __.emit().repeat( __.out("EXTENDS").in("DEFINITION") ).times('.self::MAX_LOOPING.')
                             .filter{ it.get().value("fullnspath") == fnp }
                        ) )')
             ->back('first');
        $this->prepareQuery();

        // Case of anonymous classes
        $this->atomIs('Staticmethodcall')
             ->hasClass()
             ->outIs('CLASS')
             ->atomIsNot(array('Self', 'Parent', 'Static'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToClass()
             ->atomIs('Classanonymous')
             ->back('first');
        $this->prepareQuery();
        
        // All non-in-class calls are OK
        $this->atomIs('Staticmethodcall')
             ->hasNoClassTrait();
        $this->prepareQuery();
    }
}

?>
