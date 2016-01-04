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

class LocallyUsedProperty extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Variables/StaticVariables');
    }
    
    public function analyze() {
        // normal property
        $this->atomIs('Visibility')
             ->hasNoOut('STATIC')
             ->outIs('DEFINE')
             ->_as('ppp')
             ->isNot('propertyname', null)
             ->savePropertyAs('propertyname', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'property')
             ->back('ppp');
        $this->prepareQuery();

        // static property in an variable static::$c
        $this->atomIs('Visibility')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->_as('ppp')
             ->hasNoFunction()
             ->analyzerIsNot('Variables/StaticVariables')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'property')
             ->back('ppp');
        $this->prepareQuery();

        // static property in an array
        $this->atomIs('Visibility')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->_as('ppp')
             ->isNot('propertyname', null)
             // not a static variable for a function/method
             ->hasNoFunction()
             ->analyzerIsNot('Variables/StaticVariables')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'property')
             ->back('ppp');
        $this->prepareQuery();
    }
}

?>
