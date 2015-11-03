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

class StaticMethodsCalledFromObject extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Classes/MethodDefinition',
                     'Classes/StaticMethods');
    }

    public function analyze() {
        $methods = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Function"]].out("NAME")
                                   .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\MethodDefinition").any()}
                                   .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\StaticMethods").any()}
                                   .transform{it.code.toLowerCase()}
                                   .unique()
GREMLIN
);
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->code($methods)
             ->inIs('METHOD');
        $this->prepareQuery();
    }
}

?>
