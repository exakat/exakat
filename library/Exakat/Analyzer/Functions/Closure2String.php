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
        // function ($x) { return strtoupper($x);} => 'foo', 'X::foo'
        // function ($x) use ($a) { return $a->b($x);} = array($var, 'method')
        $this->atomIs(array('Closure', 'Arrowfunction'))
             ->outIs('BLOCK')
             ->optional(
                // for closure only
                $this->side()
                     ->is('count', 1)
                     ->outIs('EXPRESSION')
                     ->atomIs('Return')
                     ->outIs('RETURN')
                     ->prepareSide()
             )
             ->atomIs(array('Functioncall', 'Methodcall', 'Staticmethodcall'))
             // Avoid extra arguments that can't be set from outside
             ->not(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->atomIs(array_merge(self::$CALLS,
                                          array('Array', 'Integer', 'String', 'Nsname', 'Identifier', 'Float', 'Boolean', 'Null')))
             )

             // argument can't be a closure argument
             ->not(
                $this->side()
                     ->outIsIE('METHOD')
                     ->outIs('ARGUMENT')
                     ->atomIs('Variable')
                     ->inIs('DEFINITION')
                     ->inIs('NAME')
                     ->atomIs('Parameter')
             )

             ->back('first');
        $this->prepareQuery();
    }
}

?>
