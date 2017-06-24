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

class VardumpUsage extends Analyzer {
    public function analyze() {
        $debugFunctions       = array('var_dump', 'print_r', 'var_export');
        $returnDebugFunctions = array('\\print_r', '\\var_export');
        
        // print_r (but not print_r($a, 1))
        $this->atomFunctionIs($debugFunctions)
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 1)
             ->is('boolean', false)
             ->atomIsNot(self::$CONTAINERS)
             ->back('first');
        $this->prepareQuery();
        
        $this->atomFunctionIs('\\var_dump')
             ->back('first');
        $this->prepareQuery();

        // (well, we need to check if the result string is not printed now...)
        $this->atomFunctionIs($returnDebugFunctions)
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // echo '<pre>'.print_r($a, 1);
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_ECHO', 'T_PRINT'))
             ->outIs('ARGUMENTS')
             ->atomInside('Functioncall')
             ->functioncallIs($returnDebugFunctions)
             ->outIs('ARGUMENTS')
             ->back('first');
        $this->prepareQuery();
        
//         call_user_func_array('var_dump', )
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->functioncallIs(array('\\call_user_func_array', '\\call_user_func'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->tokenIsNot('T_QUOTE')
             ->noDelimiterIs($debugFunctions)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
