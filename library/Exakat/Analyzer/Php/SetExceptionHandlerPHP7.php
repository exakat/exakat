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
namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class SetExceptionHandlerPHP7 extends Analyzer {
    public function analyze() {
        // With function name in a string
        $this->atomFunctionIs('\set_exception_handler')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('String', 'Concatenation'), self::WITH_CONSTANTS)
             ->has('noDelimiter')
             ->regexIsNot('noDelimiter', '::')
             ->hasIn('DEFINITION')
             ->functionDefinition()
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIsNot('\\throwable')
             ->back('first');
        $this->prepareQuery();

        // With class::method name in a string
        $this->atomFunctionIs('\set_exception_handler')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->hasNoOut('CONCAT')
             ->regexIs('noDelimiter', '::')
             ->savePropertyAs('noDelimiter', 'methode')
             ->raw('sideEffect{ methode = methode.tokenize("::")[1]; }')
             ->hasIn('DEFINITION')
             ->classDefinition()
             ->atomIs('Method')
             ->outIs('NAME')
             ->samePropertyAs('fullcode', 'methode', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIsNot('\\throwable')
             ->back('first');
        $this->prepareQuery();

        // With parent:method name in a string

        // With closure
        $this->atomFunctionIs('\set_exception_handler')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Closure')
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIsNot('\\throwable')
             ->back('first');
        $this->prepareQuery();

        // With array (class + method)
        $this->atomFunctionIs('\set_exception_handler')
             ->outWithRank('ARGUMENT', 0)
             ->AtomIs('Arrayliteral')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->savePropertyAs('noDelimiter', 'methode')
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 0)
             ->classDefinition()
             ->outIs('METHOD')
             ->atomIs('Method')
             ->outIs('NAME')
             ->samePropertyAs('fullcode', 'methode', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIsNot('\\throwable')
             ->back('first');
        $this->prepareQuery();

        // With array (object + method)
    }
}

?>
