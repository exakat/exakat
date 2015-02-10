<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Constants;

use Analyzer;

class InconsistantCase extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Boolean")
             ->groupFilter("if (it.code == it.code.toLowerCase()) { x2 = 'lower'; } else if (it.code == it.code.toUpperCase()) { x2 = 'upper'; } else {x2 = 'mixed'; }", 10 / 100);
        $this->prepareQuery();

        $this->atomIs("Null")
             ->groupFilter("if (it.code == it.code.toLowerCase()) { x2 = 'lower'; } else if (it.code == it.code.toUpperCase()) { x2 = 'upper'; } else {x2 = 'mixed'; }", 10 / 100);
        $this->prepareQuery();
    }
}

?>
