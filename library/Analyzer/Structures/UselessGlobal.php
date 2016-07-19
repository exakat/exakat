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
        return ;
        // Global are unused if used only once
        $globalVariables = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Global"]].out("GLOBAL").code.unique()
GREMLIN
);
        $inGlobals = $this->query(<<<GREMLIN
g.V().hasLabel("Variable").has("code", "\\\$GLOBALS").in("VARIABLE").hasLabel("Array").out("INDEX").hasLabel("String").map{ '\$' + it.get().value("noDelimiter")}.unique()
GREMLIN
);
        print_r($inGlobals);
        
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->codeIsNot($superglobals)
             ->code($globalsAsVariable)
             ->eachCounted('it.fullcode', 1);
        $this->prepareQuery();

        $globals = $this->query(<<<GREMLIN
g.V().hasLabel("Global").out("GLOBAL").hasLabel("Variable").has("token", "T_VARIABLE").map{ it.get().value("code").substring(1, it.get().value("code").size())}.unique()
GREMLIN
);
        print_r($globals);die();
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
             ->codeIs($superglobals);
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
