<?php declare(strict_types = 1);
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

class Php80OnlyTypeHints extends Analyzer {
    public function analyze(): void {
        // function foo() : null | stringable
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIs('Scalartypehint')
             ->fullnspathIs(array('\\false', '\\null'))
             ->back('first');
        $this->prepareQuery();

        // function foo() : null | stringable
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIs(self::STATIC_NAMES)
             ->fullnspathIs('\\stringable')
             ->back('first');
        $this->prepareQuery();

        // function foo( null | stringable $x) {}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->atomIs('Scalartypehint')
             ->fullnspathIs(array('\\false', '\\null'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->atomIs(self::STATIC_NAMES)
             ->fullnspathIs('\\stringable')
             ->back('first');
        $this->prepareQuery();


    }
}

?>
