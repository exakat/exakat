<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
                     'Structures/UnusedGlobal',
                     'Structures/ImplicitGlobal');
    }
    
    public function analyze() {
        // Global are unused if used only once
        $globalVariables = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Global"]].out("GLOBAL").code.unique()
GREMLIN
);
        $inGlobals = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Array"]].filter{ it.out("VARIABLE").has("code", "\\\$GLOBALS").any()}.out("INDEX").transform{ '\$' + it.noDelimiter}.unique()
GREMLIN
);

        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');
        $superglobalsAsIndex = array_map(function($x) { return substr($x, 1); }, $superglobals);
        
        $globalsAsVariable = array_diff($globalVariables, $inGlobals);
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->codeIsNot($superglobals)
             ->code($globalsAsVariable)
             ->eachCounted('it.fullcode', 1);
        $this->prepareQuery();

        $globalAsIndex = array_map(function($x) { return substr($x, 1); }, array_diff( $inGlobals, $globalVariables));
        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->code('$GLOBALS', true)
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiter($globalAsIndex)
             ->inIs('INDEX')
             ->eachCounted('it.fullcode', 1);
        $this->prepareQuery();

        // $_POST and co are not needed as super globals
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->code($superglobals);
        $this->prepareQuery();

        $this->atomIs('Variable')
             ->code('$GLOBALS', true)
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiter($superglobalsAsIndex);
        $this->prepareQuery();

        // written only ? 
    }
}

?>
