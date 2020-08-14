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

class Php80VariableSyntax extends Analyzer {
    protected $phpVersion = '8.0+';

    public function analyze(): void {
        // __FUNCTION__[0]
        $this->atomIs('Magicconstant')
             ->inIs('VARIABLE')
             ->atomIs('Array');
        $this->prepareQuery();

        // "A$b"[0]
        $this->atomIs('String')
             ->hasOut('CONCAT')
             ->inIs('VARIABLE')
             ->atomIs('Array');
        $this->prepareQuery();

        // "A$b"[0]
        $this->atomIs('Staticconstant')
             ->inIs('CLASS')
             ->atomIs('Staticproperty');
        $this->prepareQuery();

        // new ($expression)
        // $x instanceof ($y.$z)
        $this->atomIs(array('Instanceof', 'New'))
             ->outIs(array('CLASS', 'NEW'))
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
