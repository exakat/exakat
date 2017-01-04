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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class FinalByOcramius extends Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $this->atomIs('Class')
             ->hasNoOut('EXTENDS')
             ->raw('sideEffect{ interfaces = []; }')
             ->outIs('IMPLEMENTS')
             ->inIs('DEFINITION')
             ->atomIs('Interface')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->raw('sideEffect{ interfaces.add( it.get().value("code")); }')
             ->back('first')
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{ !(it.get().value("code") in interfaces)}.in("NAME").where( __.out("PROTECTED", "PRIVATE").count().is(eq(0)) ).count().is(eq(0)) )')
             ;
//        $this->printQuery();
        $this->prepareQuery();
    }
}

?>
