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

class UnresolvedInstanceof extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Classes/IsExtClass',
                     'Composer/IsComposerNsname',
                     'Interfaces/IsExtInterface');
    }

    public function analyze() {
        $classes = $this->loadIni('php_classes.ini', 'classes');
        $classes = $this->makeFullNsPath($classes);

        $interfaces = $this->loadIni('php_interfaces.ini', 'interfaces');
        $interfaces = $this->makeFullNsPath($interfaces);
        
        //general case
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->codeIsNot(array('self', 'static', 'parent'))
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR', 'T_STATIC'))
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->analyzerIsNot('Classes/IsExtClass')
             ->analyzerIsNot('Interfaces/IsExtInterface')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->fullnspathIsNot(array_merge($classes, $interfaces))
             ->back('first');
        $this->prepareQuery();

        // special case for parents
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->code('parent')
             ->goToClass()
             ->filter('it.out("EXTENDS").any() == false')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
