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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class DynamicCode extends Analyzer {
    public function analyze() {

        // $$v
        $this->atomIs('Variable')
             ->outIs('NAME')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // $o->$p
        $this->atomIs('Member')
             ->outIs('MEMBER')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        // $o->$p();
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        //$classname::methodcall();
        $this->atomIs('Staticmethodcall')
             ->outIs(array('CLASS', 'METHOD'))
             ->outIsIE('NAME')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR', 'T_STATIC'))
             ->codeIsNot(array('self', 'parent'))
             ->back('first');
        $this->prepareQuery();

        //$functioncall(2,3,3);
        //new $classname(); (also done here)
        $this->atomIs(array('Functioncall', 'Newcall'))
             ->outIs('NAME')
             ->tokenIsNot(array_merge(self::$FUNCTIONS_TOKENS, array('T_INCLUDE', 'T_INCLUDE_ONCE', 'T_REQUIRE', 'T_REQUIRE_ONCE', )))
             ->back('first');
        $this->prepareQuery();

        // class_alias, extract and parse_url
        $this->atomFunctionIs(array('\\class_alias', '\\extract', '\\parse_str'));
        $this->prepareQuery();
    }
}

?>
