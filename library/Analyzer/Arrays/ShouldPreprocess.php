<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Arrays;

use Analyzer;

class ShouldPreprocess extends Analyzer\Analyzer {
    public function analyze() {
        $variables = array('Variable', 'Property', 'Staticproperty');
        
        // $a = array(); $a[1] = 2;
        $this->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->inIs('RIGHT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->back('first');
        $this->prepareQuery();

        // $a->b = array(); $a->b[1] = 2;
        $this->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs(array('Property', 'Staticproperty'))
             ->outIs(array('OBJECT', 'CLASS'))
             ->savePropertyAs('fullcode', 'tableauClass')
             ->inIs(array('OBJECT', 'CLASS'))
             ->outIs('PROPERTY')
             ->savePropertyAs('fullcode', 'tableauProperty')
             ->inIs('PROPERTY')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->inIs('RIGHT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs(array('Property', 'Staticproperty'))
             ->outIs(array('OBJECT', 'CLASS'))
             ->samePropertyAs('fullcode', 'tableauClass')
             ->inIs(array('OBJECT', 'CLASS'))
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'tableauProperty')
             ->back('first');
        $this->prepareQuery();

        // same as above with $array[]
        // in case this is the first one in the sequence
        $this->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs($variables)
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->inIs('RIGHT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->outIsIE('PROPERTY')
             ->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->atomIs($variables)
             ->samePropertyAs('fullcode', 'tableau')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
