<?php
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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class ForeachObject extends Analyzer {
    public function analyze() {
        // foreach ($array as $o -> $b) {}
        $this->atomIs('Foreach')
             ->hasNoOut('INDEX') // if index is here, it is all good

             ->outIs('VALUE')
             ->atomIs('Member')
             ->back('first');
        $this->prepareQuery();

        // foreach ($array as $o -> $b[0]) {}
        $this->atomIs('Foreach')
             ->hasNoOut('INDEX') // if index is here, it is all good

             ->outIs('VALUE')
             ->atomIsNot('Member')
             ->outIs(array('VARIABLE', 'OBJECT'))
             ->atomIs('Member')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
