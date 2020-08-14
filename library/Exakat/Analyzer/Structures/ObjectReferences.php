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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ObjectReferences extends Analyzer {
    public function analyze(): void {
        $scalars = $this->loadIni('php_scalar_types.ini', 'types');

        // f(stdclass &$x)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->is('reference', true)
             ->outIs('TYPEHINT')
             ->atomIsNot(array('Void', 'Null'))
             ->fullnspathIsNot($scalars)
             ->back('first');
        $this->prepareQuery();

        // f(&$x) and $x->y();
        // f(&$x) and $x->y;
        // No assignation with new inside
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->is('reference', true)
             ->savePropertyAs('code', 'variable') // Avoid &
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('NAME')
                             ->outIs('DEFINITION')
                             ->inIs('LEFT')
                             ->atomIs('Assignation') // any assignation will break the reference
                )
             )
             ->inIs('ARGUMENT')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition(array('Methodcall', 'Member'))
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'variable')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as &$b) { $b->method;}
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->is('reference', true)
             ->savePropertyAs('code', 'variable')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition(array('Methodcall', 'Member'))
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'variable');
        $this->prepareQuery();

        // todo $x = new object; then &$x;
    }
}

?>
