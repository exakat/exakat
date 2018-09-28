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


namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class OverwrittenLiterals extends Analyzer {
    public function analyze() {
    
        $equal = $this->dictCode->translate(array('='));
        
        if (empty($equal)) {
            return;
        }
        
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('DEFINITION')
             ->atomIs('Variabledefinition')
             ->raw(<<<GREMLIN
     where(
        __.out("DEFINITION").in("LEFT")
          .hasLabel("Assignation").has("code", ***)
          .not(where(__.in("EXPRESSION").in("INIT")))
          .out("RIGHT").hasLabel("Integer", "String", "Real", "Null", "Boolean")
          .count().is(gte(2))
     )

GREMLIN
, $equal[0])
             ->outIs('DEFINITION')
             ->hasParent('Assignation', 'LEFT')
             ->inIs('LEFT')
             ->hasNoParent('For', array('EXPRESSION', 'INIT'))
             ->outIs('LEFT');
        $this->prepareQuery();
    }
}

?>
