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

namespace Exakat\Analyzer\Namespaces;

use Exakat\Analyzer\Analyzer;

class CouldUseAlias extends Analyzer {
    public function analyze() {
        // use a\b as C; and  a\b::D(); 
        $this->atomIs('Newcall')
             ->hasNoIn('NAME')
             ->tokenIs('T_NS_SEPARATOR')
             ->codeIsNot('[')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToNamespace()
             ->outIs(array('BLOCK', 'CODE'))
             ->outIs('EXPRESSION')
             ->atomIs('Usenamespace')
             ->outIs('USE')
             ->raw('filter{ (fnp =~ "^" + it.get().value("fullnspath").replace("\\\\", "\\\\\\\\") + "\\$").getCount() > 0 }')
             ->back('first');
        $this->prepareQuery();

        // use a\b as C; and  a\b\c\d::D(); 
        $this->atomIs('Newcall')
             ->hasNoIn('NAME')
             ->tokenIs('T_NS_SEPARATOR')
             ->codeIsNot('[')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'fnp')
             ->savePropertyAs('fullcode', 'written')
             ->goToNamespace()
             ->outIs(array('BLOCK', 'CODE'))
             ->outIs('EXPRESSION')
             ->atomIs('Usenamespace')
             ->not(
                $this->side()
                     ->outIs('USE')
                     ->raw('filter{ (written.tokenize("\\\\")[0].toLowerCase() == it.get().value("alias"))}')
              )
             ->outIs('USE')
             ->raw('filter{ (fnp =~ "^" + it.get().value("fullnspath").replace("\\\\", "\\\\\\\\") + "..").getCount() > 0 }')
             ->back('first');
        $this->prepareQuery();

        // use a\b as C; and  a\b::D(); 
        $this->atomIs('Nsname')
             ->hasIn(array('CLASS', 'EXTENDS', 'IMPLEMENTS'))
             ->tokenIs('T_NS_SEPARATOR')
             ->codeIsNot('[')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToNamespace()
             ->outIs(array('BLOCK', 'CODE'))
             ->outIs('EXPRESSION')
             ->atomIs('Usenamespace')
             ->outIs('USE')
             ->raw('filter{ (fnp =~ "^" + it.get().value("fullnspath").replace("\\\\", "\\\\\\\\") + "\\$").getCount() > 0 }')
             ->back('first');
        $this->prepareQuery();

        // use function a\b as C; and  a\b(); 
        $this->atomIs('Functioncall')
             ->tokenIs('T_NS_SEPARATOR')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToNamespace()
             ->outIs(array('BLOCK', 'CODE'))
             ->outIs('EXPRESSION')
             ->atomIs('Usenamespace')
             ->hasOut('FUNCTION')
             ->outIs('USE')
             ->raw('filter{ (fnp =~ "^" + it.get().value("fullnspath").replace("\\\\", "\\\\\\\\") + "\\$").getCount() > 0 }')
             ->back('first');
        $this->prepareQuery();

        // use const a\b as C; and  a\b; 
        $this->atomIs('Nsname')
             ->tokenIs('T_NS_SEPARATOR')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToNamespace()
             ->outIs(array('BLOCK', 'CODE'))
             ->outIs('EXPRESSION')
             ->atomIs('Usenamespace')
             ->hasOut('CONST')
             ->outIs('USE')
             ->raw('filter{ (fnp =~ "^" + it.get().value("fullnspath").replace("\\\\", "\\\\\\\\") + "\\$").getCount() > 0 }')
             ->back('first');
        $this->prepareQuery();

        // case for constants ? for functions ? 
    }
}

?>
