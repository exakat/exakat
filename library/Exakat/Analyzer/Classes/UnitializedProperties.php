<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class UnitializedProperties extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                     'Classes/IsModified',
                    );
    }
    
    public function analyze() {
        // Normal Properties (with constructor)
        $this->atomIs(self::$CLASSES_ALL)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->isNot('static', true)
             ->outIs('PPP')
             ->hasNoOut('DEFAULT')
             ->_as('results')
             ->savePropertyAs('propertyname', 'property')
             ->back('first')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs(array('Method', 'Magicmethod'))
             ->analyzerIs('Classes/Constructor')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('BLOCK')
                             ->atomInsideNoDefinition('Member')
                             ->analyzerIs('Classes/IsModified')
                             ->outIs('MEMBER')
                             ->tokenIs('T_STRING')
                             ->samePropertyAs('code', 'property')
                     )
             )
             ->back('results');
        $this->prepareQuery();

        // Normal Properties (without constructor)
        $this->atomIs(self::$CLASSES_ALL)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->isNot('static', true)
             ->outIs('PPP')
             ->hasNoOut('DEFAULT')
             ->_as('results')
             ->savePropertyAs('propertyname', 'property')
             ->back('first')
             ->not(
                $this->side()
                     ->outIs(array('MAGICMETHOD', 'METHOD'))
                     ->atomIs('Magicmethod')
                     ->analyzerIs('Classes/Constructor')
             )
             ->back('results');
        $this->prepareQuery();

        // Static Properties (with constructor)
        $this->atomIs(self::$CLASSES_ALL)
             ->savePropertyAs('fullnspath', 'classe')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('static', true)
             ->outIs('PPP')
             ->hasNoOut('DEFAULT')
             ->_as('results')
             ->savePropertyAs('code', 'property')
             ->back('first')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->analyzerIs('Classes/Constructor')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('BLOCK')
                             ->atomInsideNoDefinition('Staticproperty')
                             ->analyzerIs('Classes/IsModified')
                             ->outIs('CLASS')
                             ->samePropertyAs('fullnspath', 'classe')
                             ->inIs('CLASS')
                             ->outIs('MEMBER')
                             ->samePropertyAs('code', 'property')
                     )
             )
             ->back('results');
        $this->prepareQuery();

        // Static Properties (without constructor)
        $this->atomIs(self::$CLASSES_ALL)
             ->savePropertyAs('fullnspath', 'classe')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('static', true)
             ->outIs('PPP')
             ->hasNoOut('DEFAULT')
             ->_as('results')
             ->savePropertyAs('code', 'property')
             ->back('first')
             ->not(
                $this->side()
                     ->outIs(array('MAGICMETHOD', 'METHOD'))
                     ->atomIs('Magicmethod')
                     ->analyzerIs('Classes/Constructor')
             )
             ->back('results');
        $this->prepareQuery();
    }
}

?>
