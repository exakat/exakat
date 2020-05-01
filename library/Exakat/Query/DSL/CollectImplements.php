<?php declare(strict_types = 1);
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


class CollectImplements extends DSL {
    public function run(): Command {
        if (func_num_args() === 1) {
            list($variable) = func_get_args();
        } else {
            $variable = 'interfaces';
        }

        $this->assertVariable($variable, self::VARIABLE_WRITE);

        $MAX_LOOPING = self::$MAX_LOOPING;
        $command = new Command(<<<GREMLIN
where( 
    __.sideEffect{ {$variable} = []; }
      .as("collectimplements")
      .emit( )
      .repeat( __.out("EXTENDS", "IMPLEMENTS")
                 .in("DEFINITION")
                 .hasLabel("Class", "Classanonymous", "Interface")
                 .simplePath().from("collectimplements")
              )
              .times($MAX_LOOPING)
              .hasLabel("Class", "Classanonymous", "Interface")
              .out("EXTENDS", "IMPLEMENTS")
              .sideEffect{ {$variable}.add(it.get().value("fullnspath")) ; }
              .fold() 
)
GREMLIN
);
        return $command;
    }
}
?>
