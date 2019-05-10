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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class StaticLoop extends Analyzer {
    public function analyze() {
        // foreach with only one value
        $this->atomIs('Foreach')
             ->collectVariable()
             ->outIs('BLOCK')

             // Check that blind variable are not mentionned
             ->checkBlindVariable()

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->whereNonDeterminist()
             ->back('first');
        $this->prepareQuery();

        // for
        $this->atomIs('For')
             ->outIs('INCREMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
             ->collectVariable()
             
             ->outIs('BLOCK')
             // check if the variables are used here
             ->checkBlindVariable()

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->whereNonDeterminist()

             ->back('first');
        $this->prepareQuery();

        // for with complex structures (property, static property, arrays, references... ?)

        // do...while
        $this->atomIs('Dowhile')
             // collect all variables
             ->collectVariable()

             ->outIs('BLOCK')
             // check if the variables are used here
             ->checkBlindVariable()

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->whereNonDeterminist()

             ->back('first');
        $this->prepareQuery();

        // do while with complex structures (property, static property, arrays, references... ?)

        // while
        $this->atomIs('While')

             // collect all variables
             ->collectVariable()
             //->raw($collectVariables)

             ->outIs('BLOCK')
             // check if the variables are used here
             ->checkBlindVariable()

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->whereNonDeterminist()
             ->back('first');
        $this->prepareQuery();

        // while with complex structures (property, static property, arrays, references... ?)
        
        // TODO : handle the case of compact
        // TODO : handle the case of localproperties used in the conditions : with a method call, they may be also updated
        // TODO : same for references
    }
    
    private function whereNonDeterminist() {
        $nonDeterminist = self::$methods->getNonDeterministFunctions();
        $nonDeterminist = makeFullnspath($nonDeterminist);

        $this->not(
            $this->side()
                 ->filter(
                    $this->side()
                         ->atomInsideNoDefinition('Functioncall')
                         ->has('fullnspath')
                         ->fullnspathIs($nonDeterminist)
                 )
        );
        
        return $this;
    }

    private function checkBlindVariable() {
        $this->not(
            $this->side()
                 ->atomInsideNoDefinition(self::$VARIABLES_USER)
                 ->samePropertyAsArray('code', 'blind', self::CASE_SENSITIVE)
        );
        
        return $this;
    }
    
    private function collectVariable() {
        $this->filter(
            $this->side()
                 ->initVariable('blind', '[]')
                 ->outIs(array('CONDITION', 'INCREMENT', 'INIT', 'VALUE'))
                 ->atomInsideNoDefinition(self::$VARIABLES_USER)
                 ->raw('sideEffect{ blind.push(it.get().value("code")) }.fold()')
        );

        return $this;
    }
}

?>
