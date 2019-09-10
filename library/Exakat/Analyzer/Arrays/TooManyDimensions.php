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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class TooManyDimensions extends Analyzer {
    protected $maxDimensions = 2;
    
    public function analyze() {
        // $a[1][2][3][4]
        // $a[1][ ][3][4]
        $this->atomIs(array('Variablearray', 'Phpvariable', 'Member', 'Staticproperty'))
             ->hasIn('VARIABLE')
             ->raw(<<<GREMLIN
 sideEffect{ l = 0;}
.repeat( __.in("VARIABLE", "APPEND").hasLabel("Array", "Arrayappend").sideEffect{ l = l + 1;}).emit()
.filter{ l > $this->maxDimensions }
GREMLIN
);
        $this->prepareQuery();

        // $a[1][ ][3] = array()
        $this->atomIs(array('Variablearray', 'Phpvariable', 'Member', 'Staticproperty'))
             ->hasIn('VARIABLE')
             ->raw(<<<GREMLIN
 sideEffect{ l = 0;}
.repeat( __.in("VARIABLE", "APPEND").hasLabel("Array", "Arrayappend").sideEffect{ l = l + 1;}).emit()
.filter{ l > $this->maxDimensions - 1 }
GREMLIN
)
            ->inIs('LEFT')
            ->atomIs('Assignation')
            ->_as('results')
            ->outIs('RIGHT')
            ->atomIs('Arrayliteral')
            ->back('results');
        $this->prepareQuery();

        // $a[1][ ][3] = array()
        $this->atomIs(array('Variablearray', 'Phpvariable', 'Member', 'Staticproperty'))
             ->hasIn('VARIABLE')
             ->raw(<<<GREMLIN
 sideEffect{ l = 0;}
.repeat( __.in("VARIABLE", "APPEND").hasLabel("Array", "Arrayappend").sideEffect{ l = l + 1;}).emit()
.filter{ l > $this->maxDimensions - 1 }
GREMLIN
)
            ->inIs('LEFT')
            ->atomIs('Assignation')
            ->_as('results')
            ->outIs('RIGHT')
            ->atomIs(self::$CALLS)
            ->inIs('DEFINITION')
            ->outIs('RETURNTYPE')
            ->fullnspathIs('\\array')
            ->back('results');
        $this->prepareQuery();
        
        $returnTypes = self::$methods->getFunctionsByReturn();
        // $a[1][ ][3] = array()
        $this->atomIs(array('Variablearray', 'Phpvariable', 'Member', 'Staticproperty'))
             ->hasIn('VARIABLE')
             ->raw(<<<GREMLIN
 sideEffect{ l = 0;}
.repeat( __.in("VARIABLE", "APPEND").hasLabel("Array", "Arrayappend").sideEffect{ l = l + 1;}).emit()
.filter{ l > $this->maxDimensions - 1 }
GREMLIN
)
            ->inIs('LEFT')
            ->atomIs('Assignation')
            ->_as('results')
            ->outIs('RIGHT')
            ->functioncallIs($returnTypes['array'])
            ->back('results');
        $this->prepareQuery();
    }
}

?>
