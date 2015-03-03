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


namespace Analyzer\Classes;

use Analyzer;

class UndefinedParentMP extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Composer\\IsComposerNsname');
    }
    
    public function analyze() {
        // parent::method()
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->code('parent')
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->raw('filter{ it.as("extension").out("IMPLEMENTS", "EXTENDS")
                              .transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                              .loop("extension"){true}{it.object.atom == "Class"}
                              .out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.out("PRIVATE").any() == false}.out("NAME").has("code", name)
                              .any() == false}')
                // checking parent is not a composer class
             ->raw('filter{ it.as("extension").out("IMPLEMENTS", "EXTENDS")
                              .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Composer\\\\IsComposerNsname").any()}
                              .any() == false;
                              }')

                // checking grand-parents are not a composer class
             ->raw('filter{ it.as("extension").out("IMPLEMENTS", "EXTENDS")
                              .transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                              .loop("extension"){true}{it.object.atom == "Class"}
                              .filter{ it.out("IMPLEMENTS", "EXTENDS").in("ANALYZED").has("code", "Analyzer\\\\Composer\\\\IsComposerNsname").any()}
                              .any() == false;
                              }')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->code('parent')
             ->back('first')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->raw('filter{ it.as("extension").out("IMPLEMENTS", "EXTENDS")
                              .transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                              .loop("extension"){true}{it.object.atom == "Class"}
                              .out("BLOCK").out("ELEMENT").has("atom", "Ppp").filter{ it.out("PROTECTED").any() == false}.out("DEFINE").has("code", name)
                              .any() == false}')
                // checking parent is not a composer class
             ->raw('filter{ it.as("extension").out("IMPLEMENTS", "EXTENDS")
                              .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Composer\\\\IsComposerNsname").any()}
                              .any() == false;
                              }')

                // checking grand-parents are not a composer class
             ->raw('filter{ it.as("extension").out("IMPLEMENTS", "EXTENDS")
                              .transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                              .loop("extension"){true}{it.object.atom == "Class"}
                              .filter{ it.out("IMPLEMENTS", "EXTENDS").in("ANALYZED").has("code", "Analyzer\\\\Composer\\\\IsComposerNsname").any()}
                              .any() == false;
                              }')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
