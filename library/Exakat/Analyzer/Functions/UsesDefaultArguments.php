<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class UsesDefaultArguments extends Analyzer {
    public function analyze() {
        $functions = $this->methods->getFunctionsArgsInterval();

        $positions = array();
        foreach($functions as $function) {
            if ($function['args_min'] == $function['args_max']) { continue; }
            if ($function['args_max'] == 100) { continue; }
            // Only test if the last is missing. This is sufficient
            $positions[$function['args_max'] - 1][] = "'\\$function[name]";
        }
        
        foreach($positions as $position => $f) {
            $this->atomFunctionIs($f)
                 ->noChildWithRank('ARGUMENT', $position)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
