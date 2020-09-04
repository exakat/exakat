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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class CouldBeParentMethod extends Analyzer {
    protected $minChildren = 4;

    public function analyze(): void {
        // class x {}
        // class y extends x { function foo() {}}
        $this->atomIs('Class')
             ->hasOut('DEFINITION')
             ->collectMethods('parent')
             ->initVariable('a', '[]')
             ->filter(
                $this->side()
                     ->atomIs('Class')
                     ->goToAllChildren(self::EXCLUDE_SELF)
                     ->collectMethods('children')
                     ->raw('sideEffect{ a = a + children;}.fold()')
             )
             ->raw(<<<GREMLIN
sideEffect{
    counts = a.countBy{ it }.findAll{ it.value >= {$this->minChildren};};
    l = counts.keySet() - parent;
}
GREMLIN
)
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('METHOD')
             ->outIs('NAME')
             ->raw('filter{ it.get().value("lccode") in l;}')
             ->inIs('NAME');
        $this->prepareQuery();
    }
}

?>
