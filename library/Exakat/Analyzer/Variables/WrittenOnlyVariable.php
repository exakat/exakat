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


namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class WrittenOnlyVariable extends Analyzer {
    public function analyze() {
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs(array('ARGUMENT', 'DEFINITION'))
             ->atomIs(array('Parameter', 'Variabledefinition', 'Globaldefinition', 'Staticdefinition')) // static and global ? 
             ->outIsIE('NAME')
             ->not(
                $this->side()
                     ->outIs('DEFINITION')
                     ->raw(<<<'GREMLIN'
coalesce( __.in("VARIABLE", "OBJECT").hasLabel("Array", "Member"),
          __.filter{ true; }
        )
GREMLIN
)
                     ->is('isRead', true)
              )
             ->filter(
                $this->side()
                     ->outIs('DEFINITION')
                     ->atomIs(self::$VARIABLES_USER)
                     ->raw(<<<'GREMLIN'
coalesce( __.in("VARIABLE", "OBJECT").hasLabel("Array", "Member"),
          __.filter{ true; }
        )
GREMLIN
)
                     ->is('isModified', true)
              )
              ->outIs('DEFINITION')
              ->atomIs(self::$VARIABLES_USER);
        $this->prepareQuery();
    }
}

?>
