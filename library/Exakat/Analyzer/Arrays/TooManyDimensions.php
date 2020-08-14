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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class TooManyDimensions extends Analyzer {
    protected $maxDimensions = 2;

    public function analyze(): void {
        // $a[1][2][3][4]
        // $a[1][ ][3][4]
        $this->atomIs(array('Variablearray', 'Phpvariable', 'Member', 'Staticproperty'))
             ->hasIn('VARIABLE')
             ->countArrayDimension('l')
             ->raw('filter{ l > ***}', $this->maxDimensions);
        $this->prepareQuery();

        // $a[1][ ][3] = array()
        $this->atomIs(array('Variablearray', 'Phpvariable', 'Member', 'Staticproperty'))
             ->hasIn('VARIABLE')
             ->countArrayDimension('l')
             ->raw('filter{ l > ***}', $this->maxDimensions - 1)
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->as('results')
             ->outIs('RIGHT')
             ->atomIs('Arrayliteral')
             ->back('results');
        $this->prepareQuery();

        // $a[1][ ][3] = array()
        $this->atomIs(array('Variablearray', 'Phpvariable', 'Member', 'Staticproperty'))
             ->hasIn('VARIABLE')
             ->countArrayDimension('l')
             ->raw('filter{ l > ***}', $this->maxDimensions - 1)
            ->inIs('LEFT')
            ->atomIs('Assignation')
            ->as('results')
            ->outIs('RIGHT')
            ->atomIs(self::CALLS)
            ->inIs('DEFINITION')
            ->outIs('RETURNTYPE')
            ->fullnspathIs('\\array')
            ->back('results');
        $this->prepareQuery();

        $returnTypes = $this->methods->getFunctionsByReturn();
        // $a[1][ ][3] = array()
        $this->atomIs(array('Variablearray', 'Phpvariable', 'Member', 'Staticproperty'))
             ->hasIn('VARIABLE')
             ->countArrayDimension('l')
             ->raw('filter{ l > ***}', $this->maxDimensions - 1)
            ->inIs('LEFT')
            ->atomIs('Assignation')
            ->as('results')
            ->outIs('RIGHT')
            ->functioncallIs($returnTypes['array'])
            ->back('results');
        $this->prepareQuery();
    }
}

?>
