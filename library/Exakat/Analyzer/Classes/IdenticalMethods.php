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

class IdenticalMethods extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                    );
    }

    public function analyze(): void {
        // class a           { public function foo() { /some code/ } }
        // class b extends a { public function foo() { /some code/ } }
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->isNot('abstract', true)
             ->hasNoInterface()
             ->savePropertyAs('fullcode', 'signature')
             ->raw(<<<'GREMLIN'
where(
    __.sideEffect{ original = []; }.out('BLOCK').out('EXPRESSION').order().by("rank").sideEffect{ original.add(it.get().value('fullcode'));}.fold() 
)
GREMLIN
)
             ->inIs('OVERWRITE')
             ->atomIs(self::FUNCTIONS_METHOD)
             ->samePropertyAs('fullcode', 'signature')
             ->raw(<<<'GREMLIN'
where(
    __.sideEffect{ copy = []; }.out('BLOCK').out('EXPRESSION').order().by("rank").sideEffect{ copy.add(it.get().value('fullcode'));}.fold() 
)
.filter{ original.join(',') == copy.join(',');}
GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
