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

class UnresolvedClasses extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsExtClass',
                     'Composer/IsComposerNsname',
                     'Composer/IsComposerClass',
                     'Composer/IsComposerInterface',
                     );
    }

    public function analyze() {
        $classes = $this->loadIni('php_classes.ini', 'classes');
        $classes = makeFullNsPath($classes);
        
        $this->atomIs('New')
             ->outIs('NEW')
             ->outIs('NAME')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot('Array')
             ->inIs('NAME')
             ->codeIsNot(array('self', 'parent', 'static'))
             ->noClassDefinition()
             ->fullnspathIsNot($classes);
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->atomIsNot(array('Self', 'Parent', 'Static'))
             ->noClassDefinition()
             ->analyzerIsNot('Classes/IsExtClass')
             ->analyzerIsNot('Composer/IsComposerNsname')
             ->analyzerIsNot('Composer/IsComposerInterface')
             ->analyzerIsNot('Composer/IsComposerClass')
             ->fullnspathIsNot($classes);
        $this->prepareQuery();
        
        // also add property/constant/methods/catch/try/typehint
    }
}

?>
