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

class UndefinedClasses extends Analyzer {
    private $extAnalyzers = array();
    public function dependsOn() {
        $ext = $this->themes->getInstance('Ext/DefinedClasses', $this->gremlin, $this->config);
        $this->extAnalyzers = $ext->getAnalyzerList();

        return array_merge(
                array('Classes/IsExtClass',
                      'Composer/IsComposerNsname',
                      'Interfaces/IsExtInterface',
                    ),
                $this->extAnalyzers);
    }
    
    public function analyze() {
        $omitted = array_merge(array('Composer/IsComposerNsname',
                                     'Classes/IsExtClass',
                                     ),
                                $this->extAnalyzers);

        $omittedAll = $omitted;
        $omittedAll[] = 'Interfaces/IsExtInterface';

        // in a New
        $this->atomIs('New')
             ->outIs('NEW')
             ->analyzerIsNot($omitted)
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->noClassDefinition()
//             ->isNotIgnored()
             ->back('first');
        $this->prepareQuery();

        // in a class::Method()
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot($omitted)
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        // in a parent::Method()
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot($omitted)
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIs('Parent')
             ->fullnspathIs('\\parent')
             ->back('first');
        $this->prepareQuery();

        // in a class::$property
        $this->atomIs('Staticproperty')
             ->analyzerIsNot($omitted)
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->analyzerIsNot('Classes/IsExtClass')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        // in a parent::$property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->atomIs('Parent')
             ->fullnspathIs('\\parent')
             ->back('first');
        $this->prepareQuery();

        // in a class::constante
        $this->atomIs('Staticconstant')
             ->analyzerIsNot($omitted)
             ->outIs('CLASS')
             ->analyzerIsNot('Classes/IsExtClass')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        // in a parent::constante
        $this->atomIs('Staticconstant')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->outIs('CLASS')
             ->atomIs('Parent')
             ->fullnspathIs('\\parent')
             ->back('first');
        $this->prepareQuery();

        // in a class::instanceof
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->analyzerIsNot($omittedAll)
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(self::$RELATIVE_CLASS)
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
             ->analyzerIsNot($omittedAll)
             ->atomIsNot(array('Parent', 'Static', 'Self'))
             ->codeIsNot($types)
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition();
        $this->prepareQuery();

        // in a property typehint f(someClass $c)
        $this->atomIs(self::$CIT)
             ->outIs('PPP')
             ->outIs('TYPEHINT')
             ->analyzerIsNot($omittedAll)
             ->atomIsNot(array('Parent', 'Static', 'Self'))
             ->codeIsNot($types)
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition();
        $this->prepareQuery();

        // Foo::class
        $this->atomIs('Staticclass')
             ->outIs('CLASS')
             ->analyzerIsNot($omittedAll)
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();
    }
}

?>
