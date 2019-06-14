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


namespace Exakat\Analyzer\Interfaces;

use Exakat\Analyzer\Analyzer;

class UndefinedInterfaces extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsExtClass',
                     'Interfaces/IsExtInterface',
                     'Composer/IsComposerClass',
                     'Composer/IsComposerInterface',
                     'Modules/DefinedInterfaces',
                     );
    }
    
    public function analyze() {
        $omitted = $this->dependsOn;

        // interface used in a instanceof nor a Typehint but not defined
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->isNot('aliased', true)
             ->atomIsNot(array('Self', 'Parent'))
             ->has('fullnspath')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->isNotIgnored()
             ->analyzerIsNot($omitted);
        $this->prepareQuery();

        $this->atomIs(self::$CONSTANTS_ALL)
             ->hasIn(array('TYPEHINT', 'RETURNTYPE'))
             ->atomIsNot(array('Self', 'Parent'))
             ->has('fullnspath')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noUseDefinition()
             ->isNotIgnored()
             ->analyzerIsNot($omitted);
        $this->prepareQuery();
    }
}

?>
