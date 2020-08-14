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


class IsVisible extends DSL {
    const VISIBLE_ABOVE = 1;
    const VISIBLE_BELOW = 2;
    const ALL_VISIBLE   = array(self::VISIBLE_ABOVE, self::VISIBLE_BELOW);

    public function run(): Command {
        switch (func_num_args()) {
            case 2:
                list($variable, $by) = func_get_args();
                break;

            case 1:
                list($variable) = func_get_args();
                $by = self::VISIBLE_ABOVE;
                break;

            default:
                assert(false, 'wrong number of argument for ' . __METHOD__);
        }

        $this->assertVariable($variable, self::VARIABLE_READ);

        if (!in_array($by, self::ALL_VISIBLE)) {
            $by = self::VISIBLE_ABOVE;
        }

        if ($by === self::VISIBLE_ABOVE) {
            // The incoming variable is located above the current one
            // This is covariant :
            return new Command(<<<GREMLIN
filter{ 
    if (it.get().properties("visibility").any()) { 
        if ($variable == "private") {
            it.get().value("visibility") in ["private", "protected", "public", "none"];
        } else if ($variable == "protected") {
            it.get().value("visibility") in ["protected", "public", "none"];
        } else if ($variable == "public") {
            it.get().value("visibility") in ["public", "none"];
        } else if ($variable == "none") {
            it.get().value("visibility") in ["public", "none"];
        } else {
            false;
        }
    } else { 
        false; 
    }
}
GREMLIN
            );
        } elseif ($by === self::VISIBLE_BELOW) {
            // The incoming variable is located below the current one
            // This is contravariant : it only accepts lesser visibilities
            return new Command(<<<GREMLIN
filter{ 
    if (it.get().properties("visibility").any()) { 
        if ($variable == "private") {
            it.get().value("visibility") in ["private"];
        } else if ($variable == "protected") {
            it.get().value("visibility") in ["private", "protected"];
        } else if ($variable == "public") {
            it.get().value("visibility") in ["private", "protected", "public", "none"];
        } else if ($variable == "none") {
            it.get().value("visibility") in ["private", "protected", "public", "none"];
        } else {
            false;
        }
    } else { 
        false; 
    }
}
GREMLIN
            );
        }
    }
}
?>
