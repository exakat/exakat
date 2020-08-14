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

namespace Exakat\Analyzer\Psr;

use Exakat\Analyzer\Common\InterfaceUsage;

class Psr7Usage extends InterfaceUsage {
    public function analyze(): void {
        $info = $this->loadJson('psr/psr-7.json');

        // Using the defined interfaces
        $this->interfaces = array();
        foreach($info->interfaces as $interface) {
            $this->interfaces[] = $interface->namespace . '\\' . $interface->name;
        }
        parent::analyze();
    }
}

?>
