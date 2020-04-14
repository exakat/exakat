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

class GoToAllParentsTraits extends DSL {
    public function run(): Command {
        list($self) = func_get_args();

        $MAX_LOOPING = self::$MAX_LOOPING;
        if ($self === Analyzer::EXCLUDE_SELF) {
            $command = new Command(<<<GREMLIN
as("gotoallparentstraits").repeat( 
    __.coalesce( __.out("USE").out("USE"), __.out("EXTENDS"))
      .in("DEFINITION")
      .simplePath().from("gotoallparentstraits")
      .hasLabel("Class", "Classanonymous", "Trait")
)
.emit( )
.times($MAX_LOOPING)
.hasLabel("Class", "Classanonymous", "Trait")
GREMLIN
);
            $command->setSack(Command::SACK_ARRAY);
        } else {
            $command = new Command(<<<GREMLIN
as("gotoallparentstraits").emit( ).repeat( 
    __.coalesce( __.out("USE").out("USE"), __.out("EXTENDS"))
      .in("DEFINITION")
      .simplePath().from("gotoallparentstraits")
      .hasLabel("Class", "Classanonymous", "Trait")
)
.times($MAX_LOOPING)
.hasLabel("Class", "Classanonymous", "Trait")
GREMLIN
);
            $command->setSack(Command::SACK_ARRAY);
        }

        return $command;
    }
}
?>
