<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class StaticMethodsCalledFromObject extends Analyzer {
    public function dependsOn() {
        return array('Classes/StaticMethods',
                    );
    }

    public function analyze() {
        $query = <<<GREMLIN
g.V().hasLabel("Method", "Magicmethod")
     .where( __.in("METHOD", "MAGICMETHOD").hasLabel("Class", "Trait") )
     .where( __.out("STATIC") )
     .out("NAME")
     .values("code")
     .unique()
GREMLIN;
        $staticMethods = $this->query($query)->toArray();
        if (empty($staticMethods)) {
            return;
        }

        $query = <<<GREMLIN
g.V().hasLabel("Method")
     .where( __.in("METHOD").hasLabel("Class", "Trait") )
     .not(where( __.out("STATIC") ))
     .out("NAME")
     .values("code")
     .unique()
GREMLIN;
        $normalMethods = $this->query($query)->toArray();
        
        $methods = array_diff($staticMethods, $normalMethods);
        if (empty($staticMethods)) {
            return;
        }

        // $a->staticMethod (Anywhere in the code)
        $this->atomIs('Methodcall')
             ->outIs('OBJECT')
             ->atomIsNot('This')
             ->back('first')
             ->outIs('METHOD')
             ->codeIs($methods, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();

        // $this->staticMethod (In the local class tree)
        $this->atomIs('Methodcall')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->back('first')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('METHOD')
             ->hasOut('STATIC')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
