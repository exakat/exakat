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


namespace Analyzer\Functions;

use Analyzer;

class MarkCallable extends Analyzer\Analyzer {
    public function analyze() {
        $ini = $this->loadIni('php_with_callback.ini');
        foreach($ini as $key => &$lists) {
            foreach($lists as $id => &$function) {
                $function = '\\' . $function;
            }
        }

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
    i = it.noDelimiter.indexOf("::");
    if (i > 0) {
        it.cbClass = it.noDelimiter.substring(0, i).toLowerCase();
        if (it.cbClass.toString()[0] != "\\\\") {it.cbClass = "\\\\" + it.cbClass;};
        it.cbMethod = it.noDelimiter.substring(2 + i).toLowerCase();
    } else {
        it.fullnspath = it.noDelimiter.toLowerCase().replaceAll( "\\\\\\\\\\\\\\\\", "\\\\\\\\" ); if (it.fullnspath == "" || it.fullnspath.toString()[0] != "\\\\") {it.fullnspath = "\\\\" + it.fullnspath;};
    }
}
GREMLIN;

        // callable is in # position
        foreach($positions as $position) {
            $this->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspath($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs('String')
                 ->tokenIsNot('T_QUOTE')
                 ->raw($apply);
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank('last')
             ->atomIs('String')
             ->raw($apply);
        $this->prepareQuery();

        // callable is in 2nd to last
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank('2last')
             ->atomIs('String')
             ->raw($apply);
        $this->prepareQuery();

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // array('Class', 'method');

        $apply = 'sideEffect{
    theArray.cbClass = it.out("ARGUMENT").has("rank", 0).next().noDelimiter.toLowerCase().replaceAll( "\\\\\\\\\\\\\\\\", "\\\\\\\\" );
    if (theArray.cbClass.toString()[0] != "\\\\") {theArray.cbClass = "\\\\" + theArray.cbClass;};

    theArray.cbMethod = it.out("ARGUMENT").has("rank", 1).next().noDelimiter.toLowerCase();
    
    // case for "parent::method";
    i = theArray.cbMethod.indexOf("::");
    theArray.i = i;
    if (i > 0) {
        theArray.cbMethod = theArray.cbMethod.substring(i + 2);
        
        // we assume it is only parent.
        if (g.idx("classes")[["path":theArray.cbClass]].any() == false) {
            // No such class
        } else if (g.idx("classes")[["path":theArray.cbClass]].out("EXTENDS").any()) {
            theArray.cbClass = g.idx("classes")[["path":theArray.cbClass]].out("EXTENDS").next().fullnspath;
        } else {
            theArray.cbParentClass = theArray.cbParentClass + "\\\\parent";
        }
    }
}';

        $arrayContainsTwoStrings = 'filter{it.out("ARGUMENT").has("atom", "String").filter{ it.out("CONTAINS").any() == false}.count() >= 2}';

        // callable is in # position
        foreach($positions as $position) {
            $this->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspath($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs('Functioncall')
                 ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
                 ->raw('sideEffect{ theArray = it; }')
                 ->outIs('ARGUMENTS')
                 ->raw($arrayContainsTwoStrings)
                 ->raw($apply);
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank('last')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->raw('sideEffect{ theArray = it; }')
             ->outIs('ARGUMENTS')
             ->raw($arrayContainsTwoStrings)
             ->raw($apply);
        $this->prepareQuery();

        // callable is in 2nd to last
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank('2last')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->raw('sideEffect{ theArray = it; }')
             ->outIs('ARGUMENTS')
             ->raw($arrayContainsTwoStrings)
             ->raw($apply);
        $this->prepareQuery();

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // array($object, 'method'); Also, [$object, 'method']
        $apply = 'sideEffect{
    theArray.cbMethod = it.out("ARGUMENT").has("rank", 1).next().noDelimiter.toLowerCase();
}';

        $firstArgIsAVariable = 'filter{it.out("ARGUMENT").has("rank", 0).has("atom", "Variable").any()}';
        $secondArgIsAString = 'filter{it.out("ARGUMENT").has("rank", 1).has("atom", "String").filter{ it.out("CONTAINS").any() == false}.any()}';
        
        // callable is in # position
        foreach($positions as $position) {
            $this->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspath($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs('Functioncall')
                 ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
                 ->raw('sideEffect{ theArray = it; }')
                 ->outIs('ARGUMENTS')
                 // 1rst array argument is a $this
                 ->raw($firstArgIsAVariable )
                 // 2nd array argument is a real string
                 ->raw($secondArgIsAString)
                 ->raw($apply);
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank('last')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->raw('sideEffect{ theArray = it; }')
             ->outIs('ARGUMENTS')
             ->raw($firstArgIsAVariable)
             ->raw($secondArgIsAString)
             ->raw($apply);
        $this->prepareQuery();

        // callable is in 2nd to last
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank('2last')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->raw('sideEffect{ theArray = it; }')
             ->outIs('ARGUMENTS')
             ->raw($firstArgIsAVariable)
             ->raw($secondArgIsAString)
             ->raw($apply);
        $this->prepareQuery();
    }
}

?>
