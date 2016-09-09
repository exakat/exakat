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
             ->atomInside('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
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
             ->samePropertyAs('code', 'alias')
             ->back('result');
        $this->prepareQuery();
        
    // case of simple use in a Static structure or instanceof
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside(array('Staticconstant', 'Staticproperty', 'Staticmethodcall', 'Instanceof'))
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of namespace use used in a class/trait
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Use')
             ->hasClassTrait()
             ->outIs('USE')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();
        
    // As nsname prefix
        $this->atomIs('Use')
             ->hasNoClassTrait()
             ->outIs('USE')
             ->analyzerIsNot('self')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Nsname')
             ->hasNoIn(['USE', 'NAME']) // avoid namespace and use itself.
             ->outWithRank('SUBNAME', 0)
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
