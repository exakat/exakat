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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class WrongCase extends Analyzer {

    public function analyze() {
        // New
        $this->atomIs('New')
             ->outIs('NEW')
             ->outIsIE('NAME')
             ->atomIs(array('Nsname', 'Identifier', 'Newcallname', 'Newcall'))
             ->getClassName('classe')
             ->inIsIE('NAME')
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

// staticMethodcall
        $this->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticconstant', 'Staticclass'))
             ->outIs('CLASS')
             ->atomIs(array('Nsname', 'Identifier'))
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('DEFINITION')
                             ->atomIs(array('As', 'Nsname', 'Identifier'))
                     )
             )
             ->getClassName('classe')
             ->inIs('DEFINITION')
             ->atomIs(array('Class', 'Interface'))
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

// Catch
        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->atomIs(array('Nsname', 'Identifier'))
             ->getClassName('classe')
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

// Typehint
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->getClassName('classe')
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first')
             ->outIs('ARGUMENT');
        $this->prepareQuery();

// Return Typehint
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->getClassName('classe')
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first')
             ->outIs('ARGUMENT');
        $this->prepareQuery();

// instance of
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->atomIs(array('Nsname', 'Identifier'))
             ->getClassName('classe')
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

// use
        $this->atomIs('Usenamespace')
             ->outIs('USE')
             ->outIsIE('NAME')
             ->getClassName('classe')
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
    
    private function getClassName($name = 'classe') {
        $this->initVariable($name)
             ->raw(<<<GREMLIN
sideEffect{ 
    if (it.get().values('token') == "T_STRING") {
        $name = it.get().value('fullcode');
    } else { // it is a namespace
        $name = it.get().value('fullcode').tokenize('\\\\').last();
    }
}
GREMLIN
);
        return $this;
    }
}

?>
