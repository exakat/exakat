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

class SemanticTyping extends Analyzer {
    public function analyze() {
        $typed_names = array(
            '$array',
            '$list',

            '$string',
            '$text',
            '$str',
            '$message',
            '$msg',

            '$boolean',
            '$bool',

            '$integer',
            '$int',
            '$addition',
            '$multiplication',
            '$division',
            '$number',
            '$num',
            '$count',
            '$size',
            '$length',

            '$float',
            '$double',
            '$real',
            '$decimal',

            '$object',
            '$obj',

            '$callback',
            '$closure',
            '$function',
            '$method',
            '$callable',
            '$iterable',
        );

        // function foo($closure ) {}
        $this->atomIs(self::$FUNCTIONS)
             ->outIs('ARGUMENT')
             ->as('results')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('results')
             ->outIs('NAME')
             
             ->codeIs($typed_names, self::TRANSLATE, self::CASE_INSENSITIVE);
        $this->prepareQuery();

        // function foo(return $closure ) {}
        $this->atomIs(self::$FUNCTIONS)
             ->outIs('RETURNTYPE')
             ->atomIs('Void')
             ->back('first')
             
             ->outIs('RETURNED')
             ->atomIs('Variable')
             ->codeIs($typed_names, self::TRANSLATE, self::CASE_INSENSITIVE);
        $this->prepareQuery();
    }
}

?>
