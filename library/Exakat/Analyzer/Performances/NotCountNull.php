<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class NotCountNull extends Analyzer {
    public function analyze() {
        $functions = array('\\count',
                           '\\strlen',
                           '\\mb_strlen',
                           );

        // if (count($x) == 0)
        $this->atomFunctionIs($functions)
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIs(array('==', '===', '!=', '!==', '>', '>=', '<', '<='))
             ->_as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Integer')
             ->codeIs("0")
             ->back('results');
        $this->prepareQuery();

        // if (count($x))
        $this->atomFunctionIs($functions)
             ->hasIn('CONDITION');
        $this->prepareQuery();

        // if (count($x) && $x > 2)
        $this->atomFunctionIs($functions)
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Logical')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
