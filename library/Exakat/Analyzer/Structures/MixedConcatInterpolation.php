<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class MixedConcatInterpolation extends Analyzer {
    public function analyze() {
        // $a."b$c";
        $this->atomIs('Concatenation')
            // constant, methodcall and functioncall are ignored as not interpolable.
             ->raw(<<<'GREMLIN'
where( __.out("CONCAT").coalesce( __.hasLabel("Variable"),
                                  __.hasLabel("Array").where( __.out("INDEX").hasLabel("Integer", "String"))
                                                      .where( __.out("VARIABLE").hasLabel("Variablearray")),
                                  __.hasLabel("Member").where(__.out("MEMBER").hasLabel("Name"))
                                                       .where(__.out("OBJECT").hasLabel("Variableobject", "Member")),
                                  __.hasLabel("Identifier").has("fullnspath", "\\PHP_EOL")
                                 )
     )
GREMLIN
             )
             ->outIs('CONCAT')
             ->atomIs('String')
             ->hasOut('CONCAT')
             ->back('first');
        $this->prepareQuery();
        
        // This analysis is currently missing the 2nd level of evertyhing : $a->{$b . $c}->$d....
    }
}

?>
