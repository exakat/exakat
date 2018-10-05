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


namespace Exakat\Query\DSL;

use Exakat\Query\Query;
use Exakat\Analyzer\Analyzer;

class GoToAllChildren extends DSL {
    public function run() {
        list($self) = func_get_args();

        if ($self === Analyzer::INCLUDE_SELF) {
            return new Command('filter{true}.emit( ).repeat( out("DEFINITION").in("EXTENDS", "IMPLEMENTS") ).times('.self::$MAX_LOOPING.')');
        } else {
            return new Command('repeat( __.out("DEFINITION").in("EXTENDS", "IMPLEMENTS") ).emit( ).times('.self::$MAX_LOOPING.')');
        }
    }
}
?>
