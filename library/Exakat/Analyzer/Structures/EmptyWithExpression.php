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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class EmptyWithExpression extends Analyzer {
    protected $phpVersion = '5.5+';
    
    public function analyze() {
        // $a = 2; empty($a) ; in a row
        // only works for variables
        $this->atomIs('Empty')
             ->outIs('ARGUMENT')
             ->raw(<<<'GREMLIN'
coalesce( __.hasLabel("Assignation").out("RIGHT"),
          __.filter{ true; }
        )
GREMLIN
)
             ->atomIsNot(array('Null', 'Boolean', 'Integer', 'Float', 'Identifier', 'Nsname', 'Array', 'Variable', 'Member', 'Staticproperty', 'Phpvariable'))
             ->back('first');
        $this->prepareQuery();

        // extends this to array, property, static property

    }
}

?>
