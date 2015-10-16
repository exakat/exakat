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

class PropertyUsedInternally extends Analyzer\Analyzer {

    public function analyze() {
        // property + $this->property
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->hasNoOut('STATIC')
             ->outIs('DEFINE')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->savePropertyAs('propertyname', 'propertyname')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();

        // property + $this->property in parents
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->hasNoOut('STATIC')
             ->outIs('DEFINE')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->savePropertyAs('propertyname', 'propertyname')
             ->goToClass()
             ->goToAllParents()
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();

        // property + $this->property in parents
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->hasNoOut('STATIC')
             ->outIs('DEFINE')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->savePropertyAs('propertyname', 'propertyname')
             ->goToClass()
             ->goToAllChildren()
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();


        //////////////////////////////////////////////////////////////////
        // static property : inside the self class
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fns')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->analyzerIsNot('self')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->_as('ppp')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'propertyname')
             ->inIsIE('LEFT')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'fns')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();

        // static and used in a class above
        $this->atomIs('Class')
             ->savePropertyAs('classTree', 'classtree')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'propertyname')
             ->goToClass()
             ->goToAllParents()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->isPropertyIn('fullnspath','classtree')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();
        
        // static and used in a class below
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fns')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'propertyname')
             ->goToClass()
             ->goToAllChildren()
             ->savePropertyAs('classTree', 'classtree')
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->isPropertyIn('fullnspath','classtree')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();
    }
}

?>
