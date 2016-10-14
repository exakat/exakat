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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UseThis extends Analyzer {
    public function dependsOn() {
        return array('Classes/MethodDefinition');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('Classes/MethodDefinition')
             ->inIs('NAME')
             ->hasNoOut('STATIC')
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->codeIs('$this', true)
             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // Case for statics
        $this->atomIs('Function')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->analyzerIs('Classes/MethodDefinition')
             ->inIs('NAME')
             ->hasOut('STATIC')
             ->outIs('BLOCK')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->savePropertyAs('fullnspath', 'classe')
             ->goToClassTrait()
             ->samePropertyAs('fullnspath', 'classe')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Function')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->analyzerIs('Classes/MethodDefinition')
             ->inIs('NAME')
             ->hasOut('STATIC')
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->savePropertyAs('fullnspath', 'classe')
             ->goToClassTrait()
             ->samePropertyAs('fullnspath', 'classe')
             ->back('first');
        $this->prepareQuery();

    // static constant are excluded.
    }
}

?>
