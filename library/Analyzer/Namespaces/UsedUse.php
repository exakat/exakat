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


namespace Analyzer\Namespaces;

use Analyzer;

class UsedUse extends Analyzer\Analyzer {

    public function analyze() {

//////////////////////////////////////////////////////////////////////////////////////////
// case of use without alias nor namespacing (use A), single or multiple declaration
//////////////////////////////////////////////////////////////////////////////////////////
    // case of simple subuse in a new with alias :  use a\b; new b\c()
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('New')
             ->outIs('NEW')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a new with alias :  use a; new a()
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->atomIs(array('Identifier', 'Nsname'))
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->atomInside('New')
             ->outIs('NEW')
             ->tokenIs('T_STRING')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in Typehint
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Typehint')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in Catch 
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Catch')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();
        
    // case of alias use in extends or implements
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'alias')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Class')
             ->outIs(array('EXTENDS', 'IMPLEMENTS'))
             ->isNot('alias', null)
             ->samePropertyAs('alias', 'alias')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static constant
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticconstant')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static property
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static method
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a instanceof
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Instanceof')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
