<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class LocallyUsedProperty extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\StaticVariables');
    }
    
    public function analyze() {
        // normal property
        $this->atomIs('Ppp')
             ->isNot('propertyname', null)
             ->hasNoOut('STATIC')
             ->savePropertyAs('propertyname', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Ppp')
             ->isNot('propertyname', null)
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             // not a static variable for a function/method
             ->hasNoFunction()
             ->analyzerIsNot('Analyzer\\Variables\\StaticVariables')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Ppp')
             ->isNot('propertyname', null)
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             // not a static variable for a function/method
             ->hasNoFunction()
             ->analyzerIsNot('Analyzer\\Variables\\StaticVariables')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('PROPERTY')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();        
        // static property
    }
}

?>
