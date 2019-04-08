<?php
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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class Closure2String extends Analyzer {
    public function analyze() {
        // function ($x) { return strtoupper($x);}
        $this->atomIs('Closure')
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs(array('Functioncall', 'Staticmethodcall'))
             ->not(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->atomIs(array_merge(self::$CALLS,
                                          array('Array', 'Integer', 'String', 'Nsname', 'Identifier', 'Real', 'Boolean', 'Null')))
             )

             ->outIsIE('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        // function ($x) use ($a) { return $a->b($x);}
        // array($a, 'b')
        $this->atomIs('Closure')
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->inIs('METHOD')

             ->not(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->atomIs(array_merge(self::$CALLS,
                                          array('Array', 'Integer', 'String', 'Nsname', 'Identifier', 'Real', 'Boolean', 'Null')))
              )
              
              ->outIs('OBJECT')
              ->raw('coalesce( __.in("DEFINITION").in("USE"),
                               __.hasLabel("This")
              )')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
