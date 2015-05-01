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

class CouldBeClassConstant extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsModified');
    }
    
    public function analyze() {
        $this->atomIs('Ppp')
             ->hasOut('DEFINE')
             ->filter('it.out("VALUE").has("atom", "Void").any() == false')
             ->filter(' it.out("VALUE").filter{ it.atom == "Void"}.any() == false')
             ->savePropertyAs('propertyname', 'name')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'staticName')
             ->goToClass()
             ->outIs('BLOCK')
             ->raw('filter{it.out.loop(1){true}{it.object.atom == "Property"}.filter{ it.out("OBJECT").has("code", "\$this").any()}
                                                                             .filter{ it.out("PROPERTY").has("code", name).any()}
                                                                             .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\IsModified").any()}
                                                                             .any() == false}')

                // array usage as static property with self or static
             ->raw('filter{it.out.loop(1){true}{it.object.atom == "Staticproperty"}.filter{ it.out("CLASS").filter{ it.code.toLowerCase() in ["static", "self"]}.any()}
                                                                                   .filter{ it.out("PROPERTY").has("code", staticName).any()}
                                                                                   .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\IsModified").any()}
                                                                                   .any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
