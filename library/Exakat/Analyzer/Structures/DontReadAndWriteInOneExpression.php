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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class DontReadAndWriteInOneExpression extends Analyzer {
    public function analyze(): void {
        //$a + ($a = 2);
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'variable')
             ->savePropertyAs('self', 'assigned')
             ->inIs('LEFT')
             ->goToFirstExpression()
             ->atomIs(self::EXPRESSION_ATOMS) // This is needed for the previous step!!
             ->atomInsideNoDefinition('Variable')
             ->notSamePropertyAs('self', 'assigned')
             ->samePropertyAs('fullcode', 'variable')
             ->is('isRead', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
