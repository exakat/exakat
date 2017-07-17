<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class ShouldPreprocess extends Analyzer {
    public function analyze() {
        $variables = array('Variable', 'Member', 'Staticproperty');
        
        // $a = array(); $a[1] = 2;
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Arrayliteral')
             ->inIs('RIGHT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('INDEX')
             ->isLiteral()
             ->inIs('INDEX')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->back('first');
        $this->prepareQuery();

        // $a->b = array(); $a->b[1] = 2;
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs(array('Member', 'Staticproperty'))
             ->outIs(array('OBJECT', 'CLASS'))
             ->savePropertyAs('fullcode', 'object')
             ->inIs(array('OBJECT', 'CLASS'))
             ->outIs('MEMBER')
             ->savePropertyAs('fullcode', 'property')
             ->inIs('MEMBER')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Arrayliteral')
             ->inIs('RIGHT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('INDEX')
             ->isLiteral()
             ->inIs('INDEX')
             ->outIs('VARIABLE')
             ->atomIs(array('Member', 'Staticproperty'))
             ->outIs(array('OBJECT', 'CLASS'))
             ->samePropertyAs('fullcode', 'object')
             ->inIs(array('OBJECT', 'CLASS'))
             ->outIs('MEMBER')
             ->samePropertyAs('fullcode', 'property')
             ->back('first');
        $this->prepareQuery();

        // same as above with $array[]
        // in case this is the first one in the sequence
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs($variables)
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Arrayliteral')
             ->inIs('RIGHT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->outIs('APPEND')
             ->samePropertyAs('fullcode', 'tableau')
             ->back('first');
        $this->prepareQuery();

        // $a->b = array(); $a->b[] = 2;
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs('Member')
             ->outIs('OBJECT')
             ->savePropertyAs('fullcode', 'object')
             ->inIs('OBJECT')
             ->outIs('MEMBER')
             ->savePropertyAs('fullcode', 'property')
             ->inIs('MEMBER')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Arrayliteral')
             ->inIs('RIGHT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs('Member')
             ->outIs('OBJECT')
             ->samePropertyAs('fullcode', 'object')
             ->inIs('OBJECT')
             ->outIs('MEMBER')
             ->atomIs('Arrayappend')
             ->outIs('APPEND')
             ->samePropertyAs('fullcode', 'property')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
