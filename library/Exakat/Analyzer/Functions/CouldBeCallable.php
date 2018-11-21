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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class CouldBeCallable extends Analyzer {
    public function analyze() {
        $ini = $this->loadIni('php_with_callback.ini');

        foreach($ini as $position => $functions) {
            $rank = substr($position, 9);
            if ($rank[0] === '_') {
                list(, $rank) = explode('_', $position);
            }
        
            // foo($arg) { array_map($arg, '') ; }
            $this->atomIs(self::$FUNCTIONS_ALL)
                 ->outIs('ARGUMENT')
                 ->atomIsNot('Void')
                 ->raw('not(where( __.out("TYPEHINT").has("token", "T_CALLABLE")))')
                 ->_as('results')
                 ->savePropertyAs('code', 'argument')
                 ->back('first')
                 ->outIs('BLOCK')
                 ->atomInsideNoDefinition('Functioncall')
                 ->functioncallIs($functions)
                 ->outWithRank('ARGUMENT', $rank)
                 ->samePropertyAs('code', 'argument', self::CASE_SENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }

        // $arg(...)
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->atomIsNot('Void')
             ->raw('not(where( __.out("TYPEHINT").has("token", "T_CALLABLE")))')
             ->_as('results')
             ->savePropertyAs('code', 'argument')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Functioncall')
             ->outIs('NAME')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'argument', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
