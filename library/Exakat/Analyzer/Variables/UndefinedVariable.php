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

class UndefinedVariable extends Analyzer {
    public function dependsOn() {
        return array('Variables/IsRead',
                     'Variables/IsModified',
                    );
    }

    public function analyze() {
        // function foo() { $b->c = 2;}
        $this->atomIs('Variabledefinition')
             ->raw(<<<GREMLIN
not(
    where( __.out("DEFINITION").hasLabel("Variable", "Variableobject", "Variablearray").where( __.in("ANALYZED").has("analyzer", within('Variables/IsModified'))))
)
GREMLIN
)
            ->raw(<<<GREMLIN
where( __.out("DEFINITION").hasLabel("Variable", "Variableobject", "Variablearray").where( __.in("ANALYZED").has("analyzer", within('Variables/IsRead'))))
GREMLIN
)
            ->outIs('DEFINITION');
        $this->prepareQuery();
    }
}

?>
