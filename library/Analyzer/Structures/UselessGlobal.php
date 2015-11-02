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
        return array('Variables/VariableUsedOnceByContext',
                     'Structures/UnusedGlobal');
    }
    
    public function analyze() {
        // Global are unused if used only once
        $inGlobals = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Variable"]].has("code", "\\\$GLOBALS").in("VARIABLE").has("atom", "Array").out("INDEX").has("atom", "String").transform{ '\$' + it.noDelimiter}.unique()
GREMLIN
);
        print_r($inGlobals);
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->analyzerIsNot('Structures/UnusedGlobal')
            // search in $GLOBALS
             ->codeIsNot($inGlobals)
             ->eachCounted('it.fullcode', 1);
        $this->prepareQuery();

        $globals = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Global"]].out("GLOBAL").has("atom", "Variable").has("token", "T_VARIABLE").transform{ it.code.substring(1, it.code.size())}.unique()
GREMLIN
);
        print_r($globals);
        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->code('$GLOBALS')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiterIsNot($globals)
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
