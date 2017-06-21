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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class MarkCallable extends Analyzer {
    public function analyze() {
        $atoms = 'String';
        
        $ini = $this->loadIni('php_with_callback.ini');
        foreach($ini as &$lists) {
            foreach($lists as &$function) {
                $function = '\\' . $function;
            }
            unset($function);
        }
        unset($lists);

        $positions = array(0, 1, 2, 3, 4, 5, 6);

        /* Supports :
            string as callable : array_map($array, 'string');
            array with static call : array_map($array, array('string', 'string'));
            call_user_func('MyClass::myCallbackMethod');
            
           Don't support :
call_user_func(array('B', 'parent::who')); // A
call_user_func(array('B', 'parent::who')); // check with USE too

call_user_func(array($obj, 'myCallbackMethod'));

        */

        ////////////////////////////////////////////////////////////////////////////////////////////////
        // working with functions (not methods)

        $apply = <<<GREMLIN
sideEffect{
    i = it.get().value('noDelimiter').indexOf("::");
    if (i > 0) {
        cbClass = it.get().value('noDelimiter').substring(0, i).toLowerCase();
        if (cbClass.toString()[0] != "\\\\") {
            cbClass = "\\\\" + cbClass;
        };
        it.get().property('fullnspath', cbClass);
        it.get().property('cbMethod', it.get().value('noDelimiter').substring(2 + i).toLowerCase());
    } else {
        fullnspath = it.get().value('noDelimiter').toLowerCase();//.replaceAll( "\\\\\\\\", "\\\\" );
        if (fullnspath == "" || fullnspath.toString()[0] != "\\\\") { 
            fullnspath = "\\\\" + fullnspath;
        };
        it.get().property('fullnspath', fullnspath.replaceAll( "\\\\\\\\\\\\\\\\", "\\\\\\\\" ));
    }
}
GREMLIN;

        // callable is in # position
        foreach($positions as $position) {
            $this->atomFunctionIs($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs($atoms)
                 ->tokenIsNot('T_QUOTE')
                 ->raw($apply);
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomFunctionIs($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 'last')
             ->atomIs($atoms)
             ->raw($apply);
        $this->prepareQuery();
        
        // callable is in 2nd to last
        $this->atomFunctionIs($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', '2last')
             ->atomIs($atoms)
             ->raw($apply);
        $this->prepareQuery();

        ////////////////////////////////////////////////////////////////////////////////////////////////
        // working with functions (not methods) : containers
        $atoms = array('Variable', 'Array', 'Member', 'Staticproperty');

        // callable is in # position
        foreach($positions as $position) {
            $this->atomFunctionIs($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs($atoms);
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomFunctionIs($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 'last')
             ->atomIs($atoms);
        $this->prepareQuery();
        
        // callable is in 2nd to last
        $this->atomFunctionIs($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', '2last')
             ->atomIs($atoms);
        $this->prepareQuery();

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // array('Class', 'method');

        $apply = <<<GREMLIN
out("ARGUMENTS").out('ARGUMENT').has('rank', 0).sideEffect{ cbClassNode = it.get(); }
.in("ARGUMENT").out('ARGUMENT').has('rank', 1).sideEffect{ cbMethodNode = it.get(); }.in("ARGUMENT")
.sideEffect{
    cbClass = cbClassNode.value('noDelimiter').toLowerCase(); //.replaceAll( "\\\\\\\\", "\\\\" );
    if (cbClass.toString()[0] != "\\\\") {
        cbClass = "\\\\" + cbClass;
    };
    cbMethod = cbMethodNode.value('noDelimiter').toLowerCase();

    theArrayNode.property('cbClass', cbClass);
    theArrayNode.property('cbMethod', cbMethod);
}

GREMLIN;

        $arrayContainsTwoStrings = <<<GREMLIN
where( __.out('ARGUMENTS').out('ARGUMENT').count().is(eq(2)) )
.where( __.out('ARGUMENTS').out('ARGUMENT').hasLabel('String').where( __.out('CONCAT').count().is(eq(0))).count().is(eq(2)) )

GREMLIN;

        // callable is in # position
        foreach($positions as $position) {
            $this->atomFunctionIs($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs('Arrayliteral')
                 ->raw('sideEffect{ theArrayNode = it.get(); }')
                 ->raw($arrayContainsTwoStrings)
                 ->raw($apply);
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomFunctionIs($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 'last')
             ->atomIs('Arrayliteral')
             ->raw('sideEffect{ theArrayNode = it.get(); }')
             ->raw($arrayContainsTwoStrings)
             ->raw($apply);
        $this->prepareQuery();

        // callable is in 2nd to last
        $this->atomFunctionIs($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', '2last')
             ->atomIs('Arrayliteral')
             ->raw('sideEffect{ theArrayNode = it.get(); }')
             ->raw($arrayContainsTwoStrings)
             ->raw($apply);
        $this->prepareQuery();

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // array($object, 'method'); Also, [$object, 'method']
        $apply = <<<GREMLIN
out("ARGUMENTS").out('ARGUMENT').has('rank', 0).sideEffect{ cbObjectNode = it.get(); }
.in("ARGUMENT").out('ARGUMENT').has('rank', 1).sideEffect{ cbMethodNode = it.get(); }.in("ARGUMENT")
.sideEffect{
    // 
    theArrayNode.property("cbObject", cbObjectNode.value("code"));
    theArrayNode.property("cbMethod", cbMethodNode.value("noDelimiter").toLowerCase());
}

GREMLIN;

        $firstArgIsAVariable = 'where ( __.out("ARGUMENTS").out("ARGUMENT").has("rank", 0).hasLabel("Variable"))';
        $secondArgIsAString = 'where ( __.out("ARGUMENTS").out("ARGUMENT").has("rank", 1).hasLabel("String").where( __.out("CONCAT").count().is(eq(0))) )';
        
        // callable is in # position
        foreach($positions as $position) {
            $this->atomFunctionIs($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs('Arrayliteral')
                 ->raw('sideEffect{ theArrayNode = it.get(); }')
                 // 1rst array argument is a $this
                 ->raw($firstArgIsAVariable )
                 // 2nd array argument is a real string
                 ->raw($secondArgIsAString)
                 ->raw($apply);
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomFunctionIs($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 'last')
             ->atomIs('Arrayliteral')
             ->raw('sideEffect{ theArrayNode = it.get(); }')
             ->raw($firstArgIsAVariable)
             ->raw($secondArgIsAString)
             ->raw($apply);
        $this->prepareQuery();

        // callable is in 2nd to last
        $this->atomFunctionIs($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', '2last')
             ->atomIs('Arrayliteral')
             ->raw('sideEffect{ theArrayNode = it.get(); }')
             ->raw($firstArgIsAVariable)
             ->raw($secondArgIsAString)
             ->raw($apply);
        $this->prepareQuery();

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Closures

        // callable is in # position
        foreach($positions as $position) {
            $this->atomFunctionIs($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs('Closure');
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomFunctionIs($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 'last')
             ->atomIs('Closure');
        $this->prepareQuery();

        // callable is in 2nd to last
        $this->atomFunctionIs($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', '2last')
             ->atomIs('Closure');
        $this->prepareQuery();
    }
}

?>
