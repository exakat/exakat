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
namespace Analyzer\Files;

use Analyzer;

class IsComponent extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $this->atomIs('File')
             ->outIs('FILE')
             ->outIs('CODE')
             ->raw('transform{ if( it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").any()) {it.out("ELEMENT").out("BLOCK").next();} else {it;}}')
             ->filter(' it.out("ELEMENT")
                .filter{!(it.atom in ["Use", "Class", "Interface", "Trait", "Include", "Global", "Const", "Visibility"])}
                .filter{ it.atom != "Function"     || it.out("NAME").hasNot("code", "").any() == false } // Function but not closure
                .filter{ it.atom != "Functioncall" || it.has("fullnspath", "\\\\define").any() == false} // Functioncall but define

                .filter{ it.atom != "Ifthen"       || it.out("THEN")
                                                        .out("ELEMENT").filter{!(it.atom in ["Use", "Class", "Interface", "Trait", "Include", "Global", "Visibility"])} // No Const
                                                                       .filter{ it.atom != "Function"     || it.out("NAME").hasNot("code", "").any() == false} // Function but not closure
                                                                       .filter{ it.atom != "Functioncall" || it.has("fullnspath", "\\\\define").any() == false} // Functioncall but define
                                                                       .any()} // Functioncall but define

                .any() == false')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
