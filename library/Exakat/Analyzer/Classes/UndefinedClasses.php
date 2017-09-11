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

class UndefinedClasses extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsExtClass',
                     'Composer/IsComposerNsname',
                     'Interfaces/IsExtInterface');
    }
    
    public function analyze() {
        // in a New
        $this->atomIs('New')
             ->outIs('NEW')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Classes/IsExtClass')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        // in a class::Method()
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Classes/IsExtClass')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        // in a parent::Method()
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIs('parent')
             ->fullnspathIs('undefined')
             ->back('first');
        $this->prepareQuery();

        // in a class::$property
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Classes/IsExtClass')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        // in a parent::$property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIs('parent')
             ->fullnspathIs('undefined')
             ->back('first');
        $this->prepareQuery();

        // in a class::constante
        $this->atomIs('Staticconstant')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Classes/IsExtClass')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        // in a parent::constante
        $this->atomIs('Staticconstant')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIs('parent')
             ->fullnspathIs('undefined')
             ->back('first');
        $this->prepareQuery();

        // in a class::instanceof
        $this->atomIs('Instanceof')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Classes/IsExtClass')
             ->analyzerIsNot('Interfaces/IsExtInterface')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        // in a typehint f(someClass $c)
        $types = $this->loadIni('php_reserved_types.ini', 'type');
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->codeIsNot(array_merge(array('self', 'parent', 'static'), $types))
             ->tokenIsNot(array('T_ARRAY', 'T_STATIC', 'T_CALLABLE'))
             ->analyzerIsNot('Classes/IsExtClass')
             ->analyzerIsNot('Interfaces/IsExtInterface')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition();
        $this->prepareQuery();
    }
}

?>
