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
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->hasNoOut('STATIC')
             ->savePropertyAs('propertyname', 'propertyname')
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
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->hasNoOut('STATIC')
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
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->hasNoOut('STATIC')
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
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'propertyname')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->tokenIsNot('T_STATIC')
             ->samePropertyAs('fullnspath', 'fns')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();

        // static and above (static:: (special case, as fns will point to local class))
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fns')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'propertyname')
             ->goToClass()
             ->goToAllParents()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs('T_STATIC')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();

        // static and above (not - static::)
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fns')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'propertyname')
             ->goToClass()
             ->goToAllParents()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->tokenIsNot('T_STATIC')
             ->samePropertyAs('fullnspath', 'fns')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();

        // static property + class below
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fns')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'propertyname')
             ->goToClass()
             ->goToAllChildren()
             ->savePropertyAs('fullnspath', 'fnslocal')
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->tokenIsNot('T_STATIC')
             ->raw('filter{it.fullnspath in [fns, fnslocal]}')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();
        
        // static and below (static:: )
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fns')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'propertyname')
             ->goToClass()
             ->goToAllChildren()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs('T_STATIC')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();
    }
}

?>
