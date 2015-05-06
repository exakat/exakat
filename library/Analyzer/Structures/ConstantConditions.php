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

class ConstantConditions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified',
                     'Analyzer\\Constants\\IsPhpConstant');
    }
    
    public function analyze() {

        $data = new \Data\Methods();
        $nonDeterministFunctions = $data->getNonDeterministFunctions();
        
        $this->atomIs('While')
             ->outIs('CONDITION')
             ->atomIsNot(array('Variable', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
             ->noAtomInside(array('Variable', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
             ->back('first');
        $this->prepareQuery();
        
        $this->atomIs('While')
             ->outIs('CONDITION')
             ->atomIs('Functioncall')
             ->code($nonDeterministFunctions)
             ->savePropertyAs('code', 'condition')
             ->back('first')
             // variables are only read
             ->raw('filter{ it.out("BLOCK").out().loop(1){true}{it.object.atom == "Variable"}.has("code", condition).filter{it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any() }.any() == false }');
        $this->prepareQuery();

        $this->atomIs('While')
             ->outIs('CONDITION')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'condition')
             ->back('first')
             // variables are only read
             ->raw('filter{ it.out("BLOCK").out().loop(1){true}{it.object.atom == "Variable"}.has("code", condition).filter{it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any() }.any() == false }');
        $this->prepareQuery();

        $this->atomIs('Ifthen')
             // constant shouldn't be PHP's
             ->raw('filter{it.out("CONDITION").out().loop(1){true}{it.object.atom in ["Identifier", "Nsname"]}.filter{it.in("ANALYZED").has("code", "Analyzer\\\\Constants\\\\IsPhpConstant").any() }.any() == false }')
             ->outIs('CONDITION')
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Ternary')
             // constant shouldn't be PHP's
             ->raw('filter{it.out("CONDITION").out().loop(1){true}{it.object.atom in ["Identifier", "Nsname"]}.filter{it.in("ANALYZED").has("code", "Analyzer\\\\Constants\\\\IsPhpConstant").any() }.any() == false }')
             ->outIs('CONDITION')
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
             ->outIs(array('FINAL', 'INCREMENT'))
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();
        
/*
    One of the variable inside the condition should be modified at some point : in the condition, or in the loop.

    Function calls are kept, but they should be characterized as non-determinist
    (calling with the same arguments may yield different result, such as random or fread)
*/
    }
}

?>
