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
    public function analyze() {

        $properties = $this->query('g.idx("atoms")[["atom":"Property"]].out("OBJECT").has("atom", "Variable").hasNot("code", \'$this\').in("OBJECT").out("PROPERTY").transform{ it.code.toLowerCase(); }.unique()');
        $this->atomIs('Visibility')
             ->hasOut('PUBLIC')
             ->hasNoOut('STATIC')
             ->outIs('DEFINE')
             ->_as('ppp')
             ->isPropertyNotIn('propertyname', $properties)
             ->back('ppp');
        $this->prepareQuery();

        $staticproperties = $this->query('g.idx("atoms")[["atom":"Staticproperty"]].filter{it.out("CLASS").filter{it.code in ["self", "static"]}.any() == false}.transform{ z = it.out("PROPERTY").has("token", "T_VARIABLE").next().code; it.out("CLASS").next().fullnspath + "::" + z.substring(1, z.size() ).toLowerCase() }.unique()');
        $staticproperties = "['". join("', '", $staticproperties)."']";
        $staticproperties = str_replace('\\', '\\\\', $staticproperties);
        
        if (strlen($staticproperties) > 10000) {
            display('Warning : '.__CLASS__.' staticproperties are too long');
        }
        
        $this->atomIs('Visibility')
             ->hasOut('PUBLIC')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->_as('ppp')
             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('ppp')
             ->filter('!(fnp + "::" + it.propertyname in '.$staticproperties.')');
        $this->prepareQuery();
    }
}

?>
