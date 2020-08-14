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

class DereferencingAS extends Analyzer {
    protected $phpVersion = '5.3-';

    public function analyze(): void {
        // $x = array(1,2,3)
        // $x[3];
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Arrayliteral') // or some array-returning function
             ->raw('filter{ it.out("ARGUMENT").has("atom", "Void").any() == false}')
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'storage')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomIsNot(self::LOOPS_ALL)
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'storage')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->back('first')
             ->outIs('LEFT');
        $this->prepareQuery();

        // $x = "abc"
        // $x[3];
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('String') // or some array-returning function ?
             ->fullcodeIsNot(array("''", '""'))
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'storage')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomIsNot(self::LOOPS_ALL)
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'storage')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->back('first')
             ->outIs('LEFT');
        $this->prepareQuery();
    }
}

?>
