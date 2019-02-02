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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class NoVariableIsACondition extends Analyzer {
    public function analyze() {
        // if ($a) {}
        $this->atomIs(self::$VARIABLES_ALL)
             ->inIsIE('CODE')
             ->inIs('CONDITION')
             ->atomIsNot('Switch');
        $this->prepareQuery();

        $this->atomIs(self::$VARIABLES_ALL)
             ->inIsIE('CODE')
             ->inIs('EXPRESSION')
             ->inIs('FINAL')
             ->atomIs('For');
        $this->prepareQuery();

        $this->atomIs(self::$VARIABLES_ALL)
             ->inIsIE('CODE')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Logical')
             ->tokenIs(array('T_LOGICAL_AND', 'T_LOGICAL_OR', 'T_LOGICAL_XOR'))
             ->hasNoIn('CONDITION');
        $this->prepareQuery();

        $this->atomIs(self::$VARIABLES_ALL)
             ->inIsIE('CODE')
             ->inIs('NOT')
             ->atomIs('Not')
             ->tokenIs('T_BANG');
        $this->prepareQuery();

    }
}

?>
