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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class MismatchedTernary extends Analyzer {
    public function analyze() {
         $excludedAtoms = array('Array', 
                                'Functioncall', 
                                'Member', 
                                'Methodcall', 
                                'Staticmethodcall',
                                'Staticproperty',
                                'Ternary',
                                'Void', 
                                'Variable', 
                              );
        
        $this->atomIs('Ternary')
             ->codeIs('?')
             ->outIs('THEN')
             ->outIsIE('CODE')
             ->atomIsNot($excludedAtoms)
             ->savePropertyAs('label', 'then')
             ->back('first')
             ->outIs('ELSE')
             ->outIsIE('CODE')
             ->atomIsNot($excludedAtoms)
             ->raw('sideEffect{ if (then == "Concatenation") { then = "String"; } else 
                                if (then == "Addition")      { then = "Integer"; } else 
                                if (then == "Cast")          { then = "Integer"; } 
                                 }')
             ->savePropertyAs('label', 'notthen')
             ->raw('sideEffect{ if (notthen == "Concatenation") { notthen = "String"; } else 
                                if (notthen == "Addition")      { notthen = "Integer"; } else 
                                if (notthen == "Cast")          { notthen = "Integer"; } 
                                 }')
             ->raw('filter{ then != notthen; } ')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
