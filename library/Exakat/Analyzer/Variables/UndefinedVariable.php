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

namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class UndefinedVariable extends Analyzer {
    public function analyze() {
        // function foo() { echo $b;}
        $this->atomIs('Variabledefinition')
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs(self::$FUNCTIONS_ALL)
                     ->outIs('BLOCK')
                     ->atomInsideNoDefinition(array('Eval', 'Include'))
             )
             // Not from extract
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs(self::$FUNCTIONS_ALL)
                     ->outIs('BLOCK')
                     ->atomInsideNoDefinition('Functioncall')
                     ->functioncallIs('\\extract')
             )

             // Not from foreach
             ->not(
                $this->side()
                     ->outIs('DEFINITION')
                     ->inIs('VALUE')
                     ->atomIs('Foreach')
             )
             ->filter(
                $this->side()
                     ->outIs('DEFINITION')
                     ->atomIs('Variable')
                     ->is('isRead', true)
             )
             ->not(
                $this->side()
                     ->outIs('DEFINITION')
                     ->atomIs('Variable')
                     ->is('isModified', true)
             )
             ->outIs('DEFINITION');
        $this->prepareQuery();

        // function foo() { $b->c = 2;}
        $this->atomIs('Variabledefinition')
             ->analyzerIsNot('self')
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs(self::$FUNCTIONS_ALL)
                     ->outIs('BLOCK')
                     ->atomInsideNoDefinition(array('Eval', 'Include'))
             )
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs(self::$FUNCTIONS_ALL)
                     ->outIs('BLOCK')
                     ->atomInsideNoDefinition('Functioncall')
                     ->functioncallIs('\\extract')
             )
             ->filter(
                 $this->side()
                      ->outIs('DEFINITION')
                      ->atomIs(array('Variableobject', 'Variablearray'))
             )
            ->not(
                $this->side()
                     ->outIs('DEFINITION')
                     ->atomIs('Variable')
                     ->is('isModified', true)
            )
            ->outIs('DEFINITION');
        $this->prepareQuery();


    }
}

?>
