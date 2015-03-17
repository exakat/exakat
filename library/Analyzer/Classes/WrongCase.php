<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Classes;

use Analyzer;

class WrongCase extends Analyzer\Analyzer {

    public function analyze() {
// New
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs('T_STRING')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->samePropertyAs('code', 'classe')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs('T_NS_SEPARATOR')
             ->rankIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// staticMethodcall
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', true)
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->rankIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// Staticproperty
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', true)
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->rankIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// Staticconstant
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->isNot('aliased', true)
             ->tokenIs('T_STRING')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->rankIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// Catch
        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', true)
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->rankIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// Typehint
        $this->atomIs('Typehint')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', true)
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        $this->atomIs('Typehint')
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->rankIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

// instance of
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', true)
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->rankIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// use 
        $this->atomIs('Use')
             ->outIs('USE')
             ->savePropertyAs('originclass', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// aliased instanceof 
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', true)
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('originalias', 'classe')
             ->notSamePropertyAs('originalias', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// aliased static constant call 
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', true)
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('originalias', 'classe')
             ->notSamePropertyAs('originalias', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// aliased static property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', true)
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('originalias', 'classe')
             ->notSamePropertyAs('originalias', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// aliased static method
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', true)
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('originalias', 'classe')
             ->notSamePropertyAs('originalias', 'classe', true)
             ->back('first');
        $this->prepareQuery();    

// aliased typehint 
        $this->atomIs('Typehint')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', true)
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('originalias', 'classe')
             ->notSamePropertyAs('originalias', 'classe', true)
             ->back('first');
        $this->prepareQuery();    

// aliased catch
        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', true)
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('originalias', 'classe')
             ->notSamePropertyAs('originalias', 'classe', true)
             ->back('first');
        $this->prepareQuery();    
    }
}

?>
