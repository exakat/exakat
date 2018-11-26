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

class ShouldUseConstants extends Analyzer {
    public function analyze() {
        $functions = $this->loadIni('constant_usage.ini');
        
        $MAX_LOOPING = self::MAX_LOOPING;
        $authorizedAtoms = array('Logical',
                                 'Identifier',
                                 'Nsname',
                                 'Variable',
                                 'Array',
                                 'Member',
                                 'Staticproperty',
                                 'Staticconstant',
                                 'Staticmethodcall',
                                 'Methodcall',
                                 'Parenthesis',
                                 'Void',
                                 );

        $positions = range(0, 6);
        foreach($positions as $position) {
            if(empty($functions["functions{$position}"])) { 
                continue;
            }

            $fullnspath = makeFullNsPath($functions["functions{$position}"]);
            
            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->outIsIE(array('THEN', 'ELSE', 'CODE'))
                 ->atomIsNot($authorizedAtoms)
                 ->back('first');
            $this->prepareQuery();

            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs('Logical')
                 // Skip Ternaries and parenthesis
                 ->raw(<<<GREMLIN
where( 
    __.repeat( __.coalesce(__.out("THEN", "ELSE", "CODE"), filter{true}).out({$this->linksDown}) )
      .emit( ).times($MAX_LOOPING) 
      .hasLabel(without(***))
      )
GREMLIN
,$authorizedAtoms
)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
