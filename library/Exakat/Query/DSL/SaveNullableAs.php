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


class SaveNullableAs extends DSL {
    public function run(): Command {
        assert(func_num_args() <= 1, __METHOD__ . ' should get 1 arguments max, ' . func_num_args() . ' provided.');

        list($variable) = func_get_args();

        $this->assertVariable($variable, self::VARIABLE_WRITE);

        return new Command(<<<GREMLIN
choose(__.or(__.where( __.out("RETURNTYPE", "TYPEHINT").hasLabel("Null") ),
                      __.where( __.out("DEFAULT").hasLabel("Null").not(__.in("LEFT"))) 
                     ),
                      __.sideEffect{ {$variable} = true;},
                      __.sideEffect{ {$variable} = false;}
                   )
GREMLIN
);
    }
}
?>
