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
namespace Analyzer\Php;

use Analyzer;

class SetExceptionHandlerPHP7 extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Functions/MarkCallable');
    }
    
    public function analyze() {
        // With function name in a string
        $this->atomFunctionIs('\set_exception_handler')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->regexNot('noDelimiter', '::')
             ->analyzerIs('Functions/MarkCallable')
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('CLASS')
             ->fullnspathIsNot('\\Throwable')
             ->back('first');
        $this->prepareQuery();

        // With class:method name in a string
        $this->atomFunctionIs('\set_exception_handler')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->regex('noDelimiter', '::')
             ->raw('sideEffect{ methode = it.cbMethod }')
             ->analyzerIs('Functions/MarkCallable')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'methode')
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('CLASS')
             ->fullnspathIsNot('\\Throwable')
             ->back('first');
        $this->prepareQuery();

        // With parent:method name in a string

        // With closure
        $this->atomFunctionIs('\set_exception_handler')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('CLASS')
             ->fullnspathIsNot('\\Throwable')
             ->back('first');
        $this->prepareQuery();

        // With array (class + method)
        $this->atomFunctionIs('\set_exception_handler')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('Functioncall')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->outIs('ARGUMENTS')
             ->analyzerIs('Functions/MarkCallable')
             ->inIs('ARGUMENTS')
             ->raw('sideEffect{ if (it.out("ARGUMENTS").out("ARGUMENT").has("rank", 1).has("atom", "String").any()) {
                                     methode = it.out("ARGUMENTS").out("ARGUMENT").has("rank", 1).next().noDelimiter; 
                                }
                            }')
             ->filter('it.out("ARGUMENTS").out("ARGUMENT").has("rank", 0).has("atom", "String").any()') 
             ->raw('transform{ f = it.out("ARGUMENTS").out("ARGUMENT").has("rank", 0).next().noDelimiter; 
                               if (f.substring(0, 1) != "\\\\") { f = "\\\\" + f; }
                                it.filter{ g.idx("classes").get("path", f).any(); }
                                 .transform{ g.idx("classes")[["path": f]].next(); }
                                 .next();
                              }')
              ->outIs('BLOCK')
              ->outIs('ELEMENT')
              ->atomIs('Function')
              ->outIs('NAME')
              ->samePropertyAs('code', 'methode')
              ->inIs('NAME')
              ->outIs('ARGUMENTS')
              ->outIs('ARGUMENT')
              ->atomIs('Typehint')
              ->outIs('CLASS')
              ->fullnspathIsNot('\\Throwable')
              ->back('first');
        $this->prepareQuery();

        // With array (object + method)
    }
}

?>
