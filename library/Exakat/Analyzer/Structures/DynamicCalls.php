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

class DynamicCalls extends Analyzer {
    public function analyze() {
        // dynamic constants
        $this->atomFunctionIs('\\constant');
        $this->prepareQuery();

        // $$v variable variables
        $this->atomIs('Variable')
             ->outIs('NAME')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // dynamic functioncall
        $this->atomIs('Functioncall')
             ->outIs('NAME')
             ->tokenIsNot(self::$FUNCTIONS_TOKENS)
             ->back('first');
        $this->prepareQuery();

        // dynamic new
        $this->atomIs('New')
             ->outIs('NEW')
             ->outIs('NAME')
             ->atomIs('Variable')
             ->back('first');
        $this->prepareQuery();

        // $$o->p or $$o->m() are variable variable, not variable object
        // property
        // $o->{$p}
        $this->atomIs('Member')
             ->outIs('MEMBER')
             ->tokenIsNot(array('T_STRING', 'T_OPEN_BRACKET'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Member')
             ->outIs('MEMBER')
             ->atomIs('Block')
             ->back('first');
        $this->prepareQuery();

        // $o->{$m}()
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // static constants
        // use constant() or reflexion

        // static property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR', 'T_OPEN_BRACKET'))
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->back('first');
        $this->prepareQuery();

        // static methods (class part)
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->atomIsNot(array('Identifier', 'Nsname', 'Static', 'Parent', 'Self'))
             ->back('first');
        $this->prepareQuery();

        // static methods (method part)
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->atomIs('Methodcallname')
             ->outIs('NAME')
             ->atomIsNot('Name')
             ->back('first');
        $this->prepareQuery();

// class_alias
// call_user_func_array and co
// classes in names
// support reflection
    }
}

?>
