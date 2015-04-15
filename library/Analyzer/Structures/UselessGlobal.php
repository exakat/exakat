<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Structures;

use Analyzer;

class UselessGlobal extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\VariableUsedOnceByContext',
                     'Analyzer\\Structures\\UnusedGlobal');
    }
    
    public function analyze() {
        // Global are unused if used only once
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->analyzerIsNot('Analyzer\\Structures\\UnusedGlobal')
             ->savePropertyAs('code', 'variable')
            // search in $GLOBALS
             ->raw('filter{ g.idx("atoms")[["atom":"Variable"]].has("code", "\$GLOBALS").in("VARIABLE").has("atom", "Array").out("INDEX").has("atom", "String").has("noDelimiter", variable.substring(1, variable.size())).any() == false }')
             ->eachCounted('it.fullcode', 1);
        $this->prepareQuery();

        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->code('$GLOBALS')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->atomIs('String')
             ->raw('filter{ index = it.noDelimiter; g.idx("atoms")[["atom":"Global"]].out("GLOBAL").has("atom", "Variable").filter{ it.code ==  "\\$" + index}.any() == false}')
             ->inIs('INDEX')
             ->eachCounted('it.fullcode', 1);
        $this->prepareQuery();

        // $_POST and co are not needed as super globals
        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->code($superglobals);
        $this->prepareQuery();
        
        // used only once
        
        // written only
    }
}

?>
