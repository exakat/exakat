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

class NoPublicAccess extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $this->atomIs('Ppp')
             ->hasOut('PUBLIC')
             ->hasNoOut('STATIC')
             ->savePropertyAs('propertyname', 'property')
             ->raw('filter{ g.idx("atoms")[["atom":"Property"]].out("OBJECT").has("atom", "Variable").hasNot("code", "$this").in("OBJECT").out("PROPERTY").has("code", property).any() == false}')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Ppp')
             ->hasOut('PUBLIC')
             ->hasOut('STATIC')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->savePropertyAs('fullnspath', 'fns')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticproperty"]].out("CLASS").filter{it.token in ["T_STRING", "T_NS_SEPARATOR"]}.has("fullnspath", fns).in("CLASS").out("PROPERTY").has("code", property).any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
