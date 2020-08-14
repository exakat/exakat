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

class IsNullable extends DSL {
    const EXPLICIT = true;
    const IMPLICIT = false;

    public function run(): Command {
        switch(func_num_args()) {
            case 1:
                list($nullable) = func_get_args();
                $nullable = in_array($nullable, array(self::EXPLICIT, self::IMPLICIT), \STRICT_COMPARISON) ? $nullable : self::IMPLICT;
                break;

            case 0:
                $nullable = self::IMPLICIT;
                break;

            default:
                assert(func_num_args() == 1, 'Wrong number of argument for ' . __METHOD__ . '. 1 is expected, ' . func_num_args() . ' provided');
        }

        if ($nullable === self::IMPLICIT) {
            return new Command('where( __.out("RETURNTYPE", "TYPEHINT").hasLabel("Null") )');
        } else {
            return new Command('where( __.out("RETURNTYPE", "TYPEHINT").hasLabel("Null").not( __.in("DEFAULT").not( __.in("LEFT"))) )');
        }
    }
}
?>
