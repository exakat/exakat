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

namespace Exakat\Tasks\Helpers;


class Fullnspaths {
    private $uses   = array('function'       => array(),
                            'staticmethod'   => array(),
                            'method'         => array(),  // @todo : handling of parents ? of multiple definition?
                            'staticconstant' => array(),
                            'property'       => array(),
                            'staticproperty' => array(),
                            'const'          => array(),
                            'define'         => array(),
                            'class'          => array(),
                            );

    public function set(string $type, string $name, Atom $path): void {
        $this->uses[$type][$name] = $path;
    }

    public function get(string $type, string $name): ?Atom {
        return $this->uses[$type][$name] ?? null;
    }
}