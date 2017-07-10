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

class UnresolvedInstanceof extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsExtClass',
//                     'Composer/IsComposerNsname',
                     'Interfaces/IsExtInterface');
    }

    public function analyze() {
        $classes = $this->loadIni('php_classes.ini', 'classes');
        $classes = $this->makeFullNsPath($classes);

        $interfaces = $this->loadIni('php_interfaces.ini', 'interfaces');
        $interfaces = $this->makeFullNsPath($interfaces);
        
        //general case
        // traits are omitted here
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot('Array')
             ->codeIsNot(array('self', 'static', 'parent'))
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->analyzerIsNot('Classes/IsExtClass')
             ->analyzerIsNot('Interfaces/IsExtInterface')
//             ->analyzerIsNot('Composer/IsComposerNsname')
             ->fullnspathIsNot(array_merge($classes, $interfaces))
             ->back('first');
        $this->prepareQuery();

        // self and static will always work

        // special case for parents
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->codeIs('parent')
             ->goToClass()
             ->raw('not(where( __.out("EXTENDS") ) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
