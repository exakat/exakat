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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class WrongCase extends Analyzer {

    public function dependsOn() {
        return array('Complete/PropagateCalls',
                     'Complete/SetClassMethodRemoteDefinition',
                    );
    }
    
    public function analyze() {
        // function foo() {}
        // FOO();
        $this->atomIs('Functioncall')
             ->outIs('NAME')
             ->atomIs(array('Nsname', 'Identifier', 'Name'))
             ->getFunctionName('name')
             ->inIs('NAME')
             ->inIs('DEFINITION')
             ->atomIs('Function') // avoid Closure, Arrowfunctions
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'name', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // class x {function foo() {} }
        // $b->FOO();
        $this->atomIs(array('Methodcall', 'Staticmethodcall'))
             ->outIs('METHOD')
             ->atomIs('Methodcallname')
             ->outIs('NAME')
             ->atomIs('Name')
             ->getFunctionName('name')
             ->inIs('NAME')
             ->inIs('METHOD')
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'name', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }

    private function getFunctionName($name = 'name') {
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
