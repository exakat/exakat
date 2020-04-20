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

use Exakat\Analyzer\Analyzer;

class GoToAllTraits extends DSL {
    public function run(): Command {
        if (func_num_args() === 1) {
            list($self) = func_get_args();
        } else {
            $self = Analyzer::INCLUDE_SELF;
        }

        $MAX_LOOPING = self::$MAX_LOOPING;

        if ($self === Analyzer::EXCLUDE_SELF) {
            $command = new Command(<<<GREMLIN
 as("gotoalltraits")
.repeat( __.out("USE")
          .hasLabel("Usetrait")
          .out("USE")
          .in("DEFINITION")
          //.hasLabel("Trait")
          .simplePath().from("gotoalltraits")
      ).emit( )
      .times($MAX_LOOPING)
      .hasLabel("Trait")
GREMLIN
);
        } elseif ($self === Analyzer::INCLUDE_SELF) {
            $command = new Command(<<<GREMLIN
 as("gotoalltraits")
.emit( )
.repeat( __.out("USE")
          .hasLabel("Usetrait")
          .out("USE")
          .in("DEFINITION")
          .hasLabel("Trait")
          .simplePath().from("gotoalltraits")
        )
        .times($MAX_LOOPING)
        .hasLabel("Trait")
GREMLIN
);
        } else {
            assert(false, 'No such configuration for ' . self::class . ' : use EXCLUDE_SELF or INCLUDE_SELF');
        }

        return $command;
    }
}
?>
