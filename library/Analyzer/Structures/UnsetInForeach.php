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

class UnsetInForeach extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // foreach($a as $v) { unset($v); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE(array('KEY', 'VALUE'))
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $k => $v) { unset($v); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $k => $v) { unset($k); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('KEY')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();


        // foreach($a as $v) { unset($v[1]); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $k => $v) { unset($k[1]); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('KEY')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $k => $v) { unset($v[1]); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
