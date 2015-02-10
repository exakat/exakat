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


namespace Analyzer\Structures;

use Analyzer;

class DynamicCode extends Analyzer\Analyzer {
    public function analyze() {
        // $$v
        $this->atomIs('Variable')
             ->outIs('NAME')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // $v['a' . 'b']
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIsNot(array('Integer', 'String', 'Identifier', 'Boolean'))
             ->back('first');
        $this->prepareQuery();

        // v('a' . 'b')
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIsNot(array('Integer', 'String', 'Identifier', 'Boolean'))
             ->back('first');
        $this->prepareQuery();

        // $o->$p();
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        //$classname::$methodcall();
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();
        
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        //$functioncall(2,3,3);
        //new $classname(); (also done here)
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR', 'T_ISSET', 'T_ARRAY', 'T_EMPTY', 'T_LIST', 'T_UNSET'))
             ->back('first');
        $this->prepareQuery();

        // functioncall(2 + 2);
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR', 'T_VOID', 'T_INTEGER', 'T_CONSTANT_ENCAPSED_STRING', 'T_VARIABLE', 'T_LNUMBER'))
             ->back('first');
        $this->prepareQuery();
        
        // class_alias, extract
        // functioncall(2 + 2);
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\class_alias', '\\extract', '\\parse_str'));
        $this->prepareQuery();
    }
}

?>
