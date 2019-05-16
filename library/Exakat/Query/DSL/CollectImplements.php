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


namespace Exakat\Query\DSL;

use Exakat\Query\Query;
use Exakat\Analyzer\Analyzer;

class CollectImplements extends DSL {
    public function run() : Command {
        list($variable) = func_get_args();
        
        $this->assertVariable($variable, self::VARIABLE_WRITE);

        $MAX_LOOPING = self::$MAX_LOOPING;

        $command = new Command('where( 
__.sideEffect{ ' . $variable . ' = []; }
  .repeat( __.out("EXTENDS", "IMPLEMENTS")
  .in("DEFINITION")
  .hasLabel("Class", "Classanonymous", "Interface")
  .filter{!it.sack().contains(it.get().value("fullnspath")) }
  .sack {m,v -> m.add(v.value("fullnspath")); m} )
  .emit( )
  .times(' . self::$MAX_LOOPING . ')
  .hasLabel("Class", "Classanonymous", "Interface")
  .out("EXTENDS", "IMPLEMENTS")
  .sideEffect{ ' . $variable . '.add(it.get().value("fullnspath")) ; }
  .fold() 
)
');
        $command->setSack('[]');
        return $command;
    }
}
?>
