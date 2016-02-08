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


namespace Analyzer\Constants;

use Analyzer;

class MultipleConstantDefinition extends Analyzer\Analyzer {
    public function analyze() {

        $thirdArgIsTrue  = 'it.out("ARGUMENT").filter{it.rank == 2}.any() && it.out("ARGUMENT").filter{it.rank == 2}.filter{it.code.toLowerCase() == "true"}.any()';
        $thirdArgIsFalse = 'it.out("ARGUMENT").filter{it.rank == 2}.any() == false || it.out("ARGUMENT").filter{it.rank == 2}.filter{it.code.toLowerCase() == "false"}.any()';

        // case-insensitive constants with Define
        // Search for definitions and count them
        $csDefinitions = $this->query(<<<GREMLIN
m = [:];

g.idx('atoms')[['atom':'Functioncall']].has("atom", 'Functioncall').as("first").has("atom", 'Functioncall').filter{ it.inE.filter{ it.label in ['METHOD', 'NEW']}.any() == false}.filter{it.token in ['T_STRING', 'T_NS_SEPARATOR', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_PRINT', 'T_LIST'] }.filter{it.fullnspath.toLowerCase() == '\\\\define'}.out('ARGUMENTS')
             .filter{ $thirdArgIsFalse }
             .out('ARGUMENT').filter{it.rank == 0}.noDelimiter;
GREMLIN
);

        $cisDefinitions = $this->query(<<<GREMLIN
m = [:];

g.idx('atoms')[['atom':'Functioncall']].has("atom", 'Functioncall').as("first").has("atom", 'Functioncall').filter{ it.inE.filter{ it.label in ['METHOD', 'NEW']}.any() == false}.filter{it.token in ['T_STRING', 'T_NS_SEPARATOR', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_PRINT', 'T_LIST'] }.filter{it.fullnspath.toLowerCase() == '\\\\define'}.out('ARGUMENTS')
             .filter{ $thirdArgIsTrue }
             .out('ARGUMENT').filter{it.rank == 0}.transform{ it.noDelimiter.toLowerCase()};
             
GREMLIN
);

        // two definitions are case sensitive
        $this->atomFunctionIs('\\define')
             ->outIs('ARGUMENTS')
             ->filter($thirdArgIsFalse)
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->isPropertyIn('noDelimiter', $csDefinitions)
             ->eachCounted('it.noDelimiter', 2, '>=');
        $this->prepareQuery();

        // one definition is case insensitive
        $this->atomFunctionIs('\\define')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->isPropertyIn('noDelimiter', $cisDefinitions, false)
             ->eachCounted('it.noDelimiter.toLowerCase()', 2, '>=');
        $this->prepareQuery();

        // case-sensitive constants with other const
        $this->atomIs('Const')
             ->hasNoClassTrait()
             ->outIs('CONST')
             ->outIs('LEFT')
             ->raw('groupCount(m){it.code}.aggregate().filter{m[it.code] > 1}');
        $this->prepareQuery();


        // Const and defined constants
        // constants with const with define(,,[false])
        $this->atomIs('Const')
             ->hasNoClass()
             ->outIs('CONST')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'constant')
             // Find in define() calls (exact match)
             ->filter('g.idx("atoms")[["atom":"Functioncall"]].filter{it.token in ["T_STRING", "T_NS_SEPARATOR", " T_EVAL", "T_ISSET", "T_EXIT", "T_UNSET", "T_ECHO", "T_PRINT", "T_LIST"] }.filter{ it.inE.filter{ it.label in ["METHOD", "NEW"]}.any() == false}.filter{it.fullnspath.toLowerCase() == "\\\\define"}.out("ARGUMENTS")
                        .filter{'.$thirdArgIsFalse.'}
                        .out("ARGUMENT").filter{it.rank == 0}.has("atom", "String").filter{ it.out("CONTAINS").any() == false}.has("noDelimiter", constant).any()');
        $this->prepareQuery();

        // constants with const with define(,,true)
        $this->atomIs('Const')
             ->hasNoClass()
             ->outIs('CONST')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'constant')
             // Find in define() calls (case insensitive match)
             ->filter('g.idx("atoms")[["atom":"Functioncall"]].filter{it.token in ["T_STRING", "T_NS_SEPARATOR", "T_EVAL", "T_ISSET", "T_EXIT", "T_UNSET", "T_ECHO", "T_PRINT", "T_LIST"] }.filter{ it.inE.filter{ it.label in ["METHOD", "NEW"]}.any() == false}.filter{it.fullnspath.toLowerCase() == "\\\\define"}.out("ARGUMENTS")
                        .filter{'.$thirdArgIsTrue.'}
                        .out("ARGUMENT").filter{it.rank == 0}.filter{it.noDelimiter.toLowerCase() == constant.toLowerCase()}.any()');
        $this->prepareQuery();

        // define(,,true) with const
        $this->atomFunctionIs('\\define')
             ->outIs('ARGUMENTS')
             ->filter($thirdArgIsTrue)
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->savePropertyAs('noDelimiter', 'constant')
             // Find in define() calls (case insensitive match)
             ->filter('g.idx("atoms")[["atom":"Const"]]
                        .filter{ it.in.loop(1){!(it.object.atom in ["Class", "Trait"])}{it.object.atom in ["Class", "Trait"]}.any() == false}
                        .out("CONST").out("LEFT").filter{it.code.toLowerCase() == constant.toLowerCase()}.any()');
        $this->prepareQuery();

        // define(,,[false]) with const
        $this->atomFunctionIs('\\define')
             ->outIs('ARGUMENTS')
             ->filter($thirdArgIsFalse)
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->savePropertyAs('noDelimiter', 'constant')
             // Find in define() calls (case insensitive match)
             ->filter('g.idx("atoms")[["atom":"Const"]]
                        .filter{ it.in.loop(1){!(it.object.atom in ["Class", "Trait"])}{it.object.atom in ["Class", "Trait"]}.any() == false}
                        .out("CONST").out("LEFT").has("code", constant).any()')
                        ;
        $this->prepareQuery();

    }
}

?>
