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

namespace Exakat\Analyzer\Complete;

use Exakat\Analyzer\Analyzer;

class CreateMagicMethod extends Analyzer {
    public function dependsOn() {
        return array('Complete/OverwrittenMethods',
                     'Complete/SetParentDefinition',
                     'Complete/SetClassRemoteDefinitionWithTypehint',
                    );
    }

    public function analyze() {

        // Missing : typehinted properties, return typehint, clone

        // link to __call
        $this->atomIs('Methodcall', Analyzer::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              // Others are possible too : $a[1], $b->c, D::$a
             ->atomIs(array('Variableobject', 'This'), Analyzer::WITHOUT_CONSTANTS)
             
             // For variables
             ->optional(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs('Parametername', Analyzer::WITHOUT_CONSTANTS)
                     ->inIs('NAME')
                     ->outIs('TYPEHINT')
                     ->prepareSide()
             )

              ->inIs('DEFINITION')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__call', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->back('first');
        $this->prepareQuery();

        // link to __callStatic
        $this->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
             ->outIs('CLASS')
             ->atomIs(array('Variable', 'This', 'Nsname', 'Identifier', 'Self', 'Parent', 'Static'), Analyzer::WITHOUT_CONSTANTS)

             // For variables
             ->optional(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs('Parametername', Analyzer::WITHOUT_CONSTANTS)
                     ->inIs('NAME')
                     ->outIs('TYPEHINT')
                     ->prepareSide()
             )

              ->inIs('DEFINITION')
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__callstatic', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->back('first');
        $this->prepareQuery();
    }
}

?>
