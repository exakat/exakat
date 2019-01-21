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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class ShouldUseConstants extends Analyzer {
    public function analyze() {
        $functions = $this->loadIni('constant_usage.ini');
        
        // todo : support 0 as a valid value
        $authorizedAtoms = array('Logical', 'Addition',
                                 'Identifier',
                                 'Nsname',
                                 'Variable',
                                 'Array',
                                 'Member',
                                 'Staticproperty',
                                 'Staticconstant',
                                 'Staticmethodcall',
                                 'Methodcall',
                                 'Functioncall',
                                 'Ternary',
                                 'Parenthesis',
                                 'Void',
                                 );

        $positions = range(0, 6);
        foreach($positions as $position) {
            if(empty($functions["functions{$position}"])) {
                continue;
            }

            $fullnspath = makeFullNsPath($functions["functions{$position}"]);
            
            // Simple eliminations
            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->outIsIE(array('THEN', 'ELSE', 'CODE'))
                 ->atomIsNot($authorizedAtoms)
                 ->back('first');
            $this->prepareQuery();

            // Simple errors
            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->outIsIE(array('THEN', 'ELSE', 'CODE'))
                 ->atomIs(array('Logical', 'Addition'))
                 ->tokenIsNot(array('T_OR', 'T_PLUS'))
                 ->back('first');
            $this->prepareQuery();

            // Complex combinaisons, with logical, parenthesis or ternaries
            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->outIsIE(array('THEN', 'ELSE', 'CODE'))
                 ->atomIs(array('Logical', 'Addition'))
                 ->tokenIs(array('T_OR', 'T_PLUS'))
                 // Skip Ternaries and parenthesis
                 ->filter(
                    $this->side()
                         ->followExpression()
                         ->atomIsNot($authorizedAtoms)
                 )
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
