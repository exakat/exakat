<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class UnresolvedInstanceof extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsExtClass',
                     'Interfaces/IsExtInterface',
                    );
    }

    public function analyze() {
        $classes = $this->loadIni('php_classes.ini', 'classes');
        $classes = makeFullNsPath($classes);

        $interfaces = $this->loadIni('php_interfaces.ini', 'interfaces');
        $interfaces = makeFullNsPath($interfaces);
        
        //general case
        // traits are omitted here
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(array('Array', 'Boolean', 'Null', 'Self', 'Static', 'Parent'))
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->analyzerIsNot('Classes/IsExtClass')
             ->analyzerIsNot('Interfaces/IsExtInterface')
             ->fullnspathIsNot(array_merge($classes, $interfaces))
             ->back('first');
        $this->prepareQuery();

        // self and static always work

        // special case for parents
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->atomIs('Parent')
             ->goToClass()
             ->hasNoOut('EXTENDS')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
