<?php declare(strict_types = 1);
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

class VardumpUsage extends Analyzer {
    public function analyze(): void {
        $debugFunctions       = array('var_dump', 'print_r', 'var_export');
        $returnDebugFunctions = array('\\print_r', '\\var_export');

        // print_r (but not print_r($a, 1))
        $this->atomFunctionIs($debugFunctions)
             ->outWithRank('ARGUMENT', 1)
             ->is('boolean', false)
             ->atomIsNot(self::CONTAINERS)
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs('\\var_dump')
             ->back('first');
        $this->prepareQuery();

        // (well, we need to check if the result string is not printed now...)
        $this->atomFunctionIs($returnDebugFunctions)
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // echo '<pre>'.print_r($a, 1);
        $this->atomIs(array('Echo', 'Print'))
             ->atomInsideNoDefinition('Functioncall')
             ->functioncallIs($returnDebugFunctions)
             ->back('first');
        $this->prepareQuery();

//         call_user_func_array('var_dump', )
        $this->atomIs('Functioncall')
             ->functioncallIs(array('\\call_user_func_array', '\\call_user_func'))
             ->outWithRank('ARGUMENT', 0)
             ->noDelimiterIs($debugFunctions)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
